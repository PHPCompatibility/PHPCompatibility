<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility;

use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff as PHPCS_Sniff;
use PHP_CodeSniffer\Util\Tokens;
use PHPCompatibility\Helpers\TestVersionTrait;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\Namespaces;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHPCSUtils\Utils\Scopes;
use PHPCSUtils\Utils\TextStrings;

/**
 * Base class from which all PHPCompatibility sniffs extend.
 *
 * @since 5.6
 */
abstract class Sniff implements PHPCS_Sniff
{
    use TestVersionTrait;

    /**
     * Returns the fully qualified class name for a new class instantiation.
     *
     * Returns an empty string if the class name could not be reliably inferred.
     *
     * @since 7.0.3
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of a T_NEW token.
     *
     * @return string
     */
    public function getFQClassNameFromNewToken(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return '';
        }

        if ($tokens[$stackPtr]['code'] !== \T_NEW) {
            return '';
        }

        $start = $phpcsFile->findNext(Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        if ($start === false) {
            return '';
        }

        // Bow out if the next token is a variable as we don't know where it was defined.
        if ($tokens[$start]['code'] === \T_VARIABLE) {
            return '';
        }

        // Bow out if the next token is the class keyword.
        if ($tokens[$start]['code'] === \T_ANON_CLASS || $tokens[$start]['code'] === \T_CLASS) {
            return '';
        }

        $find = [
            \T_NS_SEPARATOR,
            \T_STRING,
            \T_NAMESPACE,
            \T_WHITESPACE,
        ];

        $end       = $phpcsFile->findNext($find, ($start + 1), null, true, null, true);
        $className = $phpcsFile->getTokensAsString($start, ($end - $start));
        $className = \trim($className);

        return $this->getFQName($phpcsFile, $stackPtr, $className);
    }


    /**
     * Returns the fully qualified name of the class that the specified class extends.
     *
     * Returns an empty string if the class does not extend another class or if
     * the class name could not be reliably inferred.
     *
     * @since 7.0.3
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of a T_CLASS token.
     *
     * @return string
     */
    public function getFQExtendedClassName(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return '';
        }

        if ($tokens[$stackPtr]['code'] !== \T_CLASS
            && $tokens[$stackPtr]['code'] !== \T_ANON_CLASS
            && $tokens[$stackPtr]['code'] !== \T_INTERFACE
        ) {
            return '';
        }

        $extends = ObjectDeclarations::findExtendedClassName($phpcsFile, $stackPtr);
        if (empty($extends) || \is_string($extends) === false) {
            return '';
        }

        return $this->getFQName($phpcsFile, $stackPtr, $extends);
    }


    /**
     * Returns the class name for the static usage of a class.
     * This can be a call to a method, the use of a property or constant.
     *
     * Returns an empty string if the class name could not be reliably inferred.
     *
     * @since 7.0.3
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of a T_NEW token.
     *
     * @return string
     */
    public function getFQClassNameFromDoubleColonToken(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return '';
        }

        if ($tokens[$stackPtr]['code'] !== \T_DOUBLE_COLON) {
            return '';
        }

        // Nothing to do if previous token is a variable as we don't know where it was defined.
        if ($tokens[$stackPtr - 1]['code'] === \T_VARIABLE) {
            return '';
        }

        // Nothing to do if 'parent' or 'static' as we don't know how far the class tree extends.
        if (\in_array($tokens[$stackPtr - 1]['code'], [\T_PARENT, \T_STATIC], true)) {
            return '';
        }

        // Get the classname from the class declaration if self is used.
        if ($tokens[$stackPtr - 1]['code'] === \T_SELF) {
            $classDeclarationPtr = $phpcsFile->findPrevious(\T_CLASS, $stackPtr - 1);
            if ($classDeclarationPtr === false) {
                return '';
            }
            $className = $phpcsFile->getDeclarationName($classDeclarationPtr);
            return $this->getFQName($phpcsFile, $classDeclarationPtr, $className);
        }

        $find = [
            \T_NS_SEPARATOR,
            \T_STRING,
            \T_NAMESPACE,
            \T_WHITESPACE,
        ];

        $start = $phpcsFile->findPrevious($find, $stackPtr - 1, null, true, null, true);
        if ($start === false || isset($tokens[($start + 1)]) === false) {
            return '';
        }

        $start     = ($start + 1);
        $className = $phpcsFile->getTokensAsString($start, ($stackPtr - $start));
        $className = \trim($className);

        return $this->getFQName($phpcsFile, $stackPtr, $className);
    }


    /**
     * Get the Fully Qualified name for a class/function/constant etc.
     *
     * Checks if a class/function/constant name is already fully qualified and
     * if not, enrich it with the relevant namespace information.
     *
     * @since 7.0.3
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the token.
     * @param string                      $name      The class / function / constant name.
     *
     * @return string
     */
    public function getFQName(File $phpcsFile, $stackPtr, $name)
    {
        if (\strpos($name, '\\') === 0) {
            // Already fully qualified.
            return $name;
        }

        // Remove the namespace keyword if used.
        if (\strpos($name, 'namespace\\') === 0) {
            $name = \substr($name, 10);
        }

        $namespace = Namespaces::determineNamespace($phpcsFile, $stackPtr);

        if ($namespace === '') {
            return '\\' . $name;
        } else {
            return '\\' . $namespace . '\\' . $name;
        }
    }


    /**
     * Is the class/function/constant name namespaced or global ?
     *
     * @since 7.0.3
     *
     * @param string $FQName Fully Qualified name of a class, function etc.
     *                       I.e. should always start with a `\`.
     *
     * @return bool True if namespaced, false if global.
     *
     * @throws \PHP_CodeSniffer\Exceptions\RuntimeException If the name in the passed parameter
     *                                                      is not fully qualified.
     */
    public function isNamespaced($FQName)
    {
        if (\strpos($FQName, '\\') !== 0) {
            throw new RuntimeException('$FQName must be a fully qualified name');
        }

        return (\strpos(\substr($FQName, 1), '\\') !== false);
    }


    /**
     * Get the stack pointer for a return type token for a given function.
     *
     * Compatible layer for older PHPCS versions which don't recognize
     * return type hints correctly.
     *
     * Expects to be passed T_RETURN_TYPE, T_FUNCTION or T_CLOSURE token.
     *
     * @since 7.1.2
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the token.
     *
     * @return int|false Stack pointer to the return type token or false if
     *                   no return type was found or the passed token was
     *                   not of the correct type.
     */
    public function getReturnTypeHintToken(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr]['code'] === \T_RETURN_TYPE) {
            return $stackPtr;
        }

        if ($tokens[$stackPtr]['code'] !== \T_FUNCTION && $tokens[$stackPtr]['code'] !== \T_CLOSURE) {
            return false;
        }

        if (isset($tokens[$stackPtr]['parenthesis_closer']) === false) {
            return false;
        }

        // Allow for interface and abstract method declarations.
        $endOfFunctionDeclaration = null;
        if (isset($tokens[$stackPtr]['scope_opener'])) {
            $endOfFunctionDeclaration = $tokens[$stackPtr]['scope_opener'];
        } else {
            $nextSemiColon = $phpcsFile->findNext(\T_SEMICOLON, ($tokens[$stackPtr]['parenthesis_closer'] + 1), null, false, null, true);
            if ($nextSemiColon !== false) {
                $endOfFunctionDeclaration = $nextSemiColon;
            }
        }

        if (isset($endOfFunctionDeclaration) === false) {
            return false;
        }

        $hasColon = $phpcsFile->findNext(
            [\T_COLON],
            ($tokens[$stackPtr]['parenthesis_closer'] + 1),
            $endOfFunctionDeclaration
        );
        if ($hasColon === false) {
            return false;
        }

        /*
         * - Prior to PHPCS 3.3.0, the return type would mostly be tokenized as T_RETURN_TYPE.
         *   As of PHPCS 3.3.0, the T_RETURN_TYPE token is defined, but no longer in use.
         *   The token will now be tokenized as T_STRING, T_SELF or T_CALLABLE.
         * - An `array` (return) type declaration was tokenized as `T_ARRAY_HINT` in PHPCS 2.3.3 - 3.2.3
         *   to prevent confusing sniffs looking for array declarations.
         *   As of PHPCS 3.3.0 `array` as a type declaration will be tokenized as `T_STRING`.
         */
        $unrecognizedTypes = [
            \T_CALLABLE,
            \T_SELF,
            \T_PARENT,
            \T_STRING,
        ];

        return $phpcsFile->findPrevious($unrecognizedTypes, ($endOfFunctionDeclaration - 1), $hasColon);
    }


    /**
     * Get the complete return type declaration for a given function.
     *
     * Cross-version compatible way to retrieve the complete return type declaration.
     *
     * For a classname-based return type, PHPCS, as well as the Sniff::getReturnTypeHintToken()
     * method will mark the classname as the return type token.
     * This method will find preceeding namespaces and namespace separators and will return a
     * string containing the qualified return type declaration.
     *
     * Expects to be passed a T_RETURN_TYPE token or the return value from a call to
     * the Sniff::getReturnTypeHintToken() method.
     *
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the return type token.
     *
     * @return string The name of the return type token.
     */
    public function getReturnTypeHintName(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // In older PHPCS versions, the nullable indicator will turn a return type colon into a T_INLINE_ELSE.
        $colon = $phpcsFile->findPrevious([\T_COLON, \T_FUNCTION, \T_CLOSE_PARENTHESIS], ($stackPtr - 1));
        if ($colon === false
            || ($tokens[$colon]['code'] !== \T_COLON)
        ) {
            // Shouldn't happen, just in case.
            return '';
        }

        $returnTypeHint = '';
        for ($i = ($colon + 1); $i <= $stackPtr; $i++) {
            // As of PHPCS 3.3.0+, all tokens are tokenized as "normal", so T_CALLABLE, T_SELF etc are
            // all possible, just exclude anything that's regarded as empty and the nullable indicator.
            if (isset(Tokens::$emptyTokens[$tokens[$i]['code']])) {
                continue;
            }

            if ($tokens[$i]['code'] === \T_NULLABLE) {
                continue;
            }

            $returnTypeHint .= $tokens[$i]['content'];
        }

        return $returnTypeHint;
    }


    /**
     * Get an array of just the type hints from a function declaration.
     *
     * Expects to be passed T_FUNCTION or T_CLOSURE token.
     *
     * Strips potential nullable indicator and potential global namespace
     * indicator from the type hints before returning them.
     *
     * @since 7.1.4
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the token.
     *
     * @return array Array with type hints or an empty array if
     *               - the function does not have any parameters
     *               - no type hints were found
     *               - or the passed token was not of the correct type.
     */
    public function getTypeHintsFromFunctionDeclaration(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr]['code'] !== \T_FUNCTION && $tokens[$stackPtr]['code'] !== \T_CLOSURE) {
            return [];
        }

        $parameters = FunctionDeclarations::getParameters($phpcsFile, $stackPtr);
        if (empty($parameters) || \is_array($parameters) === false) {
            return [];
        }

        $typeHints = [];

        foreach ($parameters as $param) {
            if ($param['type_hint'] === '') {
                continue;
            }

            // Strip off potential nullable indication.
            $typeHint = \ltrim($param['type_hint'], '?');

            // Strip off potential (global) namespace indication.
            $typeHint = \ltrim($typeHint, '\\');

            if ($typeHint !== '') {
                $typeHints[] = $typeHint;
            }
        }

        return $typeHints;
    }


    /**
     * Determine whether an arbitrary T_STRING token is the use of a global constant.
     *
     * @since 8.1.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the T_STRING token.
     *
     * @return bool
     */
    public function isUseOfGlobalConstant(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return false;
        }

        // Is this one of the tokens this function handles ?
        if ($tokens[$stackPtr]['code'] !== \T_STRING) {
            return false;
        }

        $next = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($next !== false
            && ($tokens[$next]['code'] === \T_OPEN_PARENTHESIS
                || $tokens[$next]['code'] === \T_DOUBLE_COLON)
        ) {
            // Function call or declaration.
            return false;
        }

        // Array of tokens which if found preceding the $stackPtr indicate that a T_STRING is not a global constant.
        $tokensToIgnore  = [
            \T_NAMESPACE  => true,
            \T_USE        => true,
            \T_EXTENDS    => true,
            \T_IMPLEMENTS => true,
            \T_NEW        => true,
            \T_FUNCTION   => true,
            \T_INSTANCEOF => true,
            \T_INSTEADOF  => true,
            \T_GOTO       => true,
            \T_AS         => true,
            \T_PUBLIC     => true,
            \T_PROTECTED  => true,
            \T_PRIVATE    => true,
        ];
        $tokensToIgnore += Tokens::$ooScopeTokens;
        $tokensToIgnore += Collections::objectOperators();

        $prev = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if ($prev !== false && isset($tokensToIgnore[$tokens[$prev]['code']]) === true) {
            // Not the use of a constant.
            return false;
        }

        if ($prev !== false
            && $tokens[$prev]['code'] === \T_NS_SEPARATOR
            && $tokens[($prev - 1)]['code'] === \T_STRING
        ) {
            // Namespaced constant of the same name.
            return false;
        }

        if ($prev !== false
            && $tokens[$prev]['code'] === \T_CONST
            && Scopes::isOOConstant($phpcsFile, $prev) === true
        ) {
            // Class constant declaration of the same name.
            return false;
        }

        /*
         * Deal with a number of variations of use statements.
         */
        for ($i = $stackPtr; $i > 0; $i--) {
            if ($tokens[$i]['line'] !== $tokens[$stackPtr]['line']) {
                break;
            }
        }

        $firstOnLine = $phpcsFile->findNext(Tokens::$emptyTokens, ($i + 1), null, true);
        if ($firstOnLine !== false && $tokens[$firstOnLine]['code'] === \T_USE) {
            $nextOnLine = $phpcsFile->findNext(Tokens::$emptyTokens, ($firstOnLine + 1), null, true);
            if ($nextOnLine !== false) {
                if (($tokens[$nextOnLine]['code'] === \T_STRING && $tokens[$nextOnLine]['content'] === 'const')) {
                    $hasNsSep = $phpcsFile->findNext(\T_NS_SEPARATOR, ($nextOnLine + 1), $stackPtr);
                    if ($hasNsSep !== false) {
                        // Namespaced const (group) use statement.
                        return false;
                    }
                } else {
                    // Not a const use statement.
                    return false;
                }
            }
        }

        return true;
    }


    /**
     * Determine whether the tokens between $start and $end together form a positive number
     * as recognized by PHP.
     *
     * The outcome of this function is reliable for `true`, `false` should be regarded as
     * "undetermined".
     *
     * Note: Zero is *not* regarded as a positive number.
     *
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $start       Start of the snippet (inclusive), i.e. this
     *                                                 token will be examined as part of the
     *                                                 snippet.
     * @param int                         $end         End of the snippet (inclusive), i.e. this
     *                                                 token will be examined as part of the
     *                                                 snippet.
     * @param bool                        $allowFloats Whether to only consider integers, or also floats.
     *
     * @return bool True if PHP would evaluate the snippet as a positive number.
     *              False if not or if it could not be reliably determined
     *              (variable or calculations and such).
     */
    public function isPositiveNumber(File $phpcsFile, $start, $end, $allowFloats = false)
    {
        $number = $this->isNumber($phpcsFile, $start, $end, $allowFloats);

        if ($number === false) {
            return false;
        }

        return ($number > 0);
    }


    /**
     * Determine whether the tokens between $start and $end together form a negative number
     * as recognized by PHP.
     *
     * The outcome of this function is reliable for `true`, `false` should be regarded as
     * "undetermined".
     *
     * Note: Zero is *not* regarded as a negative number.
     *
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $start       Start of the snippet (inclusive), i.e. this
     *                                                 token will be examined as part of the
     *                                                 snippet.
     * @param int                         $end         End of the snippet (inclusive), i.e. this
     *                                                 token will be examined as part of the
     *                                                 snippet.
     * @param bool                        $allowFloats Whether to only consider integers, or also floats.
     *
     * @return bool True if PHP would evaluate the snippet as a negative number.
     *              False if not or if it could not be reliably determined
     *              (variable or calculations and such).
     */
    public function isNegativeNumber(File $phpcsFile, $start, $end, $allowFloats = false)
    {
        $number = $this->isNumber($phpcsFile, $start, $end, $allowFloats);

        if ($number === false) {
            return false;
        }

        return ($number < 0);
    }

    /**
     * Determine whether the tokens between $start and $end together form a number
     * as recognized by PHP.
     *
     * The outcome of this function is reliable for "true-ish" values, `false` should
     * be regarded as "undetermined".
     *
     * @link https://3v4l.org/npTeM
     *
     * Mainly intended for examining variable assignments, function call parameters, array values
     * where the start and end of the snippet to examine is very clear.
     *
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $start       Start of the snippet (inclusive), i.e. this
     *                                                 token will be examined as part of the
     *                                                 snippet.
     * @param int                         $end         End of the snippet (inclusive), i.e. this
     *                                                 token will be examined as part of the
     *                                                 snippet.
     * @param bool                        $allowFloats Whether to only consider integers, or also floats.
     *
     * @return int|float|bool The number found if PHP would evaluate the snippet as a number.
     *                        The return type will be int if $allowFloats is false, if
     *                        $allowFloats is true, the return type will be float.
     *                        False will be returned when the snippet does not evaluate to a
     *                        number or if it could not be reliably determined
     *                        (variable or calculations and such).
     */
    protected function isNumber(File $phpcsFile, $start, $end, $allowFloats = false)
    {
        $stringTokens = Tokens::$heredocTokens + Tokens::$stringTokens;

        $validTokens             = [];
        $validTokens[\T_LNUMBER] = true;
        $validTokens[\T_TRUE]    = true; // Evaluates to int 1.
        $validTokens[\T_FALSE]   = true; // Evaluates to int 0.
        $validTokens[\T_NULL]    = true; // Evaluates to int 0.

        if ($allowFloats === true) {
            $validTokens[\T_DNUMBER] = true;
        }

        $maybeValidTokens = $stringTokens + $validTokens;

        $tokens         = $phpcsFile->getTokens();
        $searchEnd      = ($end + 1);
        $negativeNumber = false;

        if (isset($tokens[$start], $tokens[$searchEnd]) === false) {
            return false;
        }

        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, $start, $searchEnd, true);
        while ($nextNonEmpty !== false
            && ($tokens[$nextNonEmpty]['code'] === \T_PLUS
            || $tokens[$nextNonEmpty]['code'] === \T_MINUS)
        ) {
            if ($tokens[$nextNonEmpty]['code'] === \T_MINUS) {
                $negativeNumber = ($negativeNumber === false) ? true : false;
            }

            $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($nextNonEmpty + 1), $searchEnd, true);
        }

        if ($nextNonEmpty === false || isset($maybeValidTokens[$tokens[$nextNonEmpty]['code']]) === false) {
            return false;
        }

        $content = false;
        if ($tokens[$nextNonEmpty]['code'] === \T_LNUMBER
            || $tokens[$nextNonEmpty]['code'] === \T_DNUMBER
        ) {
            $content = (float) $tokens[$nextNonEmpty]['content'];
        } elseif ($tokens[$nextNonEmpty]['code'] === \T_TRUE) {
            $content = 1.0;
        } elseif ($tokens[$nextNonEmpty]['code'] === \T_FALSE
            || $tokens[$nextNonEmpty]['code'] === \T_NULL
        ) {
            $content = 0.0;
        } elseif (isset($stringTokens[$tokens[$nextNonEmpty]['code']]) === true) {

            if ($tokens[$nextNonEmpty]['code'] === \T_START_HEREDOC
                || $tokens[$nextNonEmpty]['code'] === \T_START_NOWDOC
            ) {
                // Skip past heredoc/nowdoc opener to the first content.
                $firstDocToken = $phpcsFile->findNext([\T_HEREDOC, \T_NOWDOC], ($nextNonEmpty + 1), $searchEnd);
                if ($firstDocToken === false) {
                    // Live coding or parse error.
                    return false;
                }

                $stringContent = $content = $tokens[$firstDocToken]['content'];

                // Skip forward to the end in preparation for the next part of the examination.
                $nextNonEmpty = $phpcsFile->findNext([\T_END_HEREDOC, \T_END_NOWDOC], ($nextNonEmpty + 1), $searchEnd);
                if ($nextNonEmpty === false) {
                    // Live coding or parse error.
                    return false;
                }
            } else {
                // Gather subsequent lines for a multi-line string.
                for ($i = $nextNonEmpty; $i < $searchEnd; $i++) {
                    if ($tokens[$i]['code'] !== $tokens[$nextNonEmpty]['code']) {
                        break;
                    }
                    $content .= $tokens[$i]['content'];
                }

                $nextNonEmpty  = --$i;
                $content       = TextStrings::stripQuotes($content);
                $stringContent = $content;
            }

            /*
             * Regexes based on the formats outlined in the manual, created by JRF.
             * @link https://www.php.net/manual/en/language.types.float.php
             */
            $regexInt   = '`^\s*[0-9]+`';
            $regexFloat = '`^\s*(?:[+-]?(?:(?:(?P<LNUM>[0-9]+)|(?P<DNUM>([0-9]*\.(?P>LNUM)|(?P>LNUM)\.[0-9]*)))[eE][+-]?(?P>LNUM))|(?P>DNUM))`';

            $intString   = \preg_match($regexInt, $content, $intMatch);
            $floatString = \preg_match($regexFloat, $content, $floatMatch);

            // Does the text string start with a number ? If so, PHP would juggle it and use it as a number.
            if ($allowFloats === false) {
                if ($intString !== 1 || $floatString === 1) {
                    if ($floatString === 1) {
                        // Found float. Only integers targetted.
                        return false;
                    }

                    $content = 0.0;
                } else {
                    $content = (float) \trim($intMatch[0]);
                }
            } else {
                if ($intString !== 1 && $floatString !== 1) {
                    $content = 0.0;
                } else {
                    $content = ($floatString === 1) ? (float) \trim($floatMatch[0]) : (float) \trim($intMatch[0]);
                }
            }

            // Allow for different behaviour for hex numeric strings between PHP 5 vs PHP 7.
            if ($intString === 1 && \trim($intMatch[0]) === '0'
                && \preg_match('`^\s*(0x[A-Fa-f0-9]+)`', $stringContent, $hexNumberString) === 1
                && $this->supportsBelow('5.6') === true
            ) {
                // The filter extension still allows for hex numeric strings in PHP 7, so
                // use that to get the numeric value if possible.
                // If the filter extension is not available, the value will be zero, but so be it.
                if (\function_exists('filter_var')) {
                    $filtered = \filter_var($hexNumberString[1], \FILTER_VALIDATE_INT, \FILTER_FLAG_ALLOW_HEX);
                    if ($filtered !== false) {
                        $content = $filtered;
                    }
                }
            }
        }

        // OK, so we have a number, now is there still more code after it ?
        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($nextNonEmpty + 1), $searchEnd, true);
        if ($nextNonEmpty !== false) {
            return false;
        }

        if ($negativeNumber === true) {
            $content = -$content;
        }

        if ($allowFloats === false) {
            return (int) $content;
        }

        return $content;
    }


    /**
     * Determine whether the tokens between $start and $end together form a numberic calculation
     * as recognized by PHP.
     *
     * The outcome of this function is reliable for `true`, `false` should be regarded as "undetermined".
     *
     * Mainly intended for examining variable assignments, function call parameters, array values
     * where the start and end of the snippet to examine is very clear.
     *
     * @since 9.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $start     Start of the snippet (inclusive), i.e. this
     *                                               token will be examined as part of the
     *                                               snippet.
     * @param int                         $end       End of the snippet (inclusive), i.e. this
     *                                               token will be examined as part of the
     *                                               snippet.
     *
     * @return bool
     */
    protected function isNumericCalculation(File $phpcsFile, $start, $end)
    {
        $arithmeticTokens = Tokens::$arithmeticTokens;

        $skipTokens   = Tokens::$emptyTokens;
        $skipTokens[] = \T_MINUS;
        $skipTokens[] = \T_PLUS;

        // Find the first arithmetic operator, but skip past +/- signs before numbers.
        $nextNonEmpty = ($start - 1);
        do {
            $nextNonEmpty       = $phpcsFile->findNext($skipTokens, ($nextNonEmpty + 1), ($end + 1), true);
            $arithmeticOperator = $phpcsFile->findNext($arithmeticTokens, ($nextNonEmpty + 1), ($end + 1));
        } while ($nextNonEmpty !== false && $arithmeticOperator !== false && $nextNonEmpty === $arithmeticOperator);

        if ($arithmeticOperator === false) {
            return false;
        }

        $tokens      = $phpcsFile->getTokens();
        $subsetStart = $start;
        $subsetEnd   = ($arithmeticOperator - 1);

        while ($this->isNumber($phpcsFile, $subsetStart, $subsetEnd, true) !== false
            && isset($tokens[($arithmeticOperator + 1)]) === true
        ) {
            $subsetStart  = ($arithmeticOperator + 1);
            $nextNonEmpty = $arithmeticOperator;
            do {
                $nextNonEmpty       = $phpcsFile->findNext($skipTokens, ($nextNonEmpty + 1), ($end + 1), true);
                $arithmeticOperator = $phpcsFile->findNext($arithmeticTokens, ($nextNonEmpty + 1), ($end + 1));
            } while ($nextNonEmpty !== false && $arithmeticOperator !== false && $nextNonEmpty === $arithmeticOperator);

            if ($arithmeticOperator === false) {
                // Last calculation operator already reached.
                if ($this->isNumber($phpcsFile, $subsetStart, $end, true) !== false) {
                    return true;
                }

                return false;
            }

            $subsetEnd = ($arithmeticOperator - 1);
        }

        return false;
    }


    /**
     * Determine whether the tokens between $start and $end could together represent a variable.
     *
     * @since 9.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile          The file being scanned.
     * @param int                         $start              Starting point stack pointer. Inclusive.
     *                                                        I.e. this token should be taken into
     *                                                        account.
     * @param int                         $end                End point stack pointer. Exclusive.
     *                                                        I.e. this token should not be taken
     *                                                        into account.
     * @param int                         $targetNestingLevel The nesting level the variable should be at.
     *
     * @return bool
     */
    public function isVariable(File $phpcsFile, $start, $end, $targetNestingLevel)
    {
        static $tokenBlackList, $bracketTokens;

        // Create the token arrays only once.
        if (isset($tokenBlackList, $bracketTokens) === false) {
            $tokenBlackList  = [
                \T_OPEN_PARENTHESIS => \T_OPEN_PARENTHESIS,
                \T_STRING_CONCAT    => \T_STRING_CONCAT,
            ];
            $tokenBlackList += Tokens::$assignmentTokens;
            $tokenBlackList += Tokens::$equalityTokens;
            $tokenBlackList += Tokens::$comparisonTokens;
            $tokenBlackList += Tokens::$operators;
            $tokenBlackList += Tokens::$booleanOperators;
            $tokenBlackList += Tokens::$castTokens;

            /*
             * List of brackets which can be part of a variable variable.
             *
             * Key is the open bracket token, value the close bracket token.
             */
            $bracketTokens = [
                \T_OPEN_CURLY_BRACKET  => \T_CLOSE_CURLY_BRACKET,
                \T_OPEN_SQUARE_BRACKET => \T_CLOSE_SQUARE_BRACKET,
            ];
        }

        $tokens = $phpcsFile->getTokens();

        // If no variable at all was found, then it's definitely a no-no.
        $hasVariable = $phpcsFile->findNext(\T_VARIABLE, $start, $end);
        if ($hasVariable === false) {
            return false;
        }

        // Check if the variable found is at the right level. Deeper levels are always an error.
        if (isset($tokens[$hasVariable]['nested_parenthesis'])
            && \count($tokens[$hasVariable]['nested_parenthesis']) !== $targetNestingLevel
        ) {
                return false;
        }

        // Ok, so the first variable is at the right level, now are there any
        // blacklisted tokens within the empty() ?
        $hasBadToken = $phpcsFile->findNext($tokenBlackList, $start, $end);
        if ($hasBadToken === false) {
            return true;
        }

        // If there are also bracket tokens, the blacklisted token might be part of a variable
        // variable, but if there are no bracket tokens, we know we have an error.
        $hasBrackets = $phpcsFile->findNext($bracketTokens, $start, $end);
        if ($hasBrackets === false) {
            return false;
        }

        // Ok, we have both a blacklisted token as well as brackets, so we need to walk
        // the tokens of the variable variable.
        for ($i = $start; $i < $end; $i++) {
            // If this is a bracket token, skip to the end of the bracketed expression.
            if (isset($bracketTokens[$tokens[$i]['code']], $tokens[$i]['bracket_closer'])) {
                $i = $tokens[$i]['bracket_closer'];
                continue;
            }

            // If it's a blacklisted token, not within brackets, we have an error.
            if (isset($tokenBlackList[$tokens[$i]['code']])) {
                return false;
            }
        }

        return true;
    }
}
