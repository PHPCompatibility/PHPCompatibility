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
        ];
        $tokensToIgnore += Tokens::$ooScopeTokens;
        $tokensToIgnore += Collections::objectOperators();
        $tokensToIgnore += Tokens::$scopeModifiers;

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
        static $tokenBlockList, $bracketTokens;

        // Create the token arrays only once.
        if (isset($tokenBlockList, $bracketTokens) === false) {
            $tokenBlockList  = [
                \T_OPEN_PARENTHESIS => \T_OPEN_PARENTHESIS,
                \T_STRING_CONCAT    => \T_STRING_CONCAT,
            ];
            $tokenBlockList += Tokens::$assignmentTokens;
            $tokenBlockList += Tokens::$equalityTokens;
            $tokenBlockList += Tokens::$comparisonTokens;
            $tokenBlockList += Tokens::$operators;
            $tokenBlockList += Tokens::$booleanOperators;
            $tokenBlockList += Tokens::$castTokens;

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
        // blocklisted tokens within the empty() ?
        $hasBadToken = $phpcsFile->findNext($tokenBlockList, $start, $end);
        if ($hasBadToken === false) {
            return true;
        }

        // If there are also bracket tokens, the blocklisted token might be part of a variable
        // variable, but if there are no bracket tokens, we know we have an error.
        $hasBrackets = $phpcsFile->findNext($bracketTokens, $start, $end);
        if ($hasBrackets === false) {
            return false;
        }

        // Ok, we have both a blocklisted token as well as brackets, so we need to walk
        // the tokens of the variable variable.
        for ($i = $start; $i < $end; $i++) {
            // If this is a bracket token, skip to the end of the bracketed expression.
            if (isset($bracketTokens[$tokens[$i]['code']], $tokens[$i]['bracket_closer'])) {
                $i = $tokens[$i]['bracket_closer'];
                continue;
            }

            // If it's a blocklisted token, not within brackets, we have an error.
            if (isset($tokenBlockList[$tokens[$i]['code']])) {
                return false;
            }
        }

        return true;
    }
}
