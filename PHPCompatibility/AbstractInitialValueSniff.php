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

use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Constants;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHPCSUtils\Utils\Scopes;

/**
 * Abstract base sniff to examine all typical places which take an initial value.
 *
 * Initial (default) values have some restrictions in PHP and, over time,
 * some of those restrictions have been lifted.
 * This base sniff allows for examining these initial values.
 *
 * The restrictions apply to the following places:
 * - Declarations of constants using the `const` keyword.
 * - Declarations of default values for OO properties.
 * - Declarations of default values for function parameters.
 * - Declarations of initial values for static variables.
 *
 * @since 10.0.0
 */
abstract class AbstractInitialValueSniff extends Sniff
{

    /**
     * Partial error phrases to be used in combination with the error message constant.
     *
     * @since 10.0.0
     *
     * @var array<string, string> Type indicator => suggested partial error phrase.
     */
    protected $initialValueTypes = [
        'const'     => 'when defining constants using the const keyword',
        'property'  => 'in property declarations',
        'staticvar' => 'in static variable declarations',
        'default'   => 'in default function arguments',
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        $targets = [
            // Constant declarations.
            \T_CONST  => \T_CONST,

            // Static variable declarations.
            \T_STATIC => \T_STATIC,
        ];

        // Property declarations.
        $targets += Collections::ooPropertyScopes();

        // Function parameters.
        $targets += Collections::functionDeclarationTokens();

        return $targets;
    }

    /**
     * Allow for a sniff to short-circuit running the logic by bowing out early.
     *
     * This method must be implemented in child classes.
     * Return `false` to never bow out early.
     *
     * @since 10.0.0
     *
     * @return bool
     */
    abstract protected function bowOutEarly();

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void|int Null or integer stack pointer to skip forward.
     */
    final public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->bowOutEarly() === true) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        /*
         * Handle default values for function parameters.
         */
        if (isset(Collections::functionDeclarationTokens()[$tokens[$stackPtr]['code']])) {
            $parameters = FunctionDeclarations::getParameters($phpcsFile, $stackPtr);
            if (empty($parameters) || isset($tokens[$stackPtr]['parenthesis_closer']) === false) {
                // No parameters or parse error/live coding, nothing to do.
                return;
            }

            $parenthesisCloser = $tokens[$stackPtr]['parenthesis_closer'];
            $type              = 'default';

            foreach ($parameters as $param) {
                if (isset($param['default_token']) === false) {
                    continue;
                }

                // Ok, so this parameter has a default value. Let's examine it.
                $defaultEnd = $parenthesisCloser;
                if (\is_int($param['comma_token'])) {
                    $defaultEnd = $param['comma_token'];
                }

                $this->processInitialValue($phpcsFile, $param['token'], $param['default_token'], $defaultEnd, $type);
            }

            if (isset($tokens[$stackPtr]['scope_opener'])) {
                // Skip over the declaration, no need to trigger the sniff for a "static" return type.
                return $tokens[$stackPtr]['scope_opener'];
            }

            return;
        }

        /*
         * Handle default values for OO properties.
         */
        if (isset(Collections::ooPropertyScopes()[$tokens[$stackPtr]['code']])) {
            $ooProperties = ObjectDeclarations::getDeclaredProperties($phpcsFile, $stackPtr);
            if (empty($ooProperties)) {
                return;
            }

            foreach ($ooProperties as $variableToken) {
                if (Scopes::isOOProperty($phpcsFile, $variableToken) === false) {
                    // Skip constructor promoted properties. Those are handled via the function declaration token.
                    continue;
                }

                $end = $this->findEndOfCurrentDeclaration($phpcsFile, $variableToken, $phpcsFile->numTokens);
                $this->processSubStatement($phpcsFile, $variableToken, $end, 'property');
            }

            return;
        }

        /*
         * Handle default values for constants/static variables.
         */
        $start          = ($stackPtr + 1);
        $endOfStatement = $phpcsFile->findNext([\T_SEMICOLON, \T_CLOSE_TAG], $start);
        if ($endOfStatement === false) {
            // No semi-colon - live coding.
            return;
        }

        // If this is a potentially typed class constant, we need to skip over the type.
        if ($tokens[$stackPtr]['code'] === \T_CONST) {
            $type = 'const';

            if (Scopes::isOOConstant($phpcsFile, $stackPtr) === true) {
                $constProperties = Constants::getProperties($phpcsFile, $stackPtr);
                $start           = ($constProperties['name_token'] - 1);
            }
        }

        // Filter out late static binding, class properties, static closures and arrow function and static return types.
        if ($tokens[$stackPtr]['code'] === \T_STATIC) {
            $next = $phpcsFile->findNext(Tokens::$emptyTokens, $start, null, true);
            if ($next === false || $tokens[$next]['code'] !== \T_VARIABLE) {
                // Not a static variable declaration. Bow out.
                return;
            }

            if (Scopes::isOOProperty($phpcsFile, $next) === true) {
                // Class properties are examined based on the OO token.
                return;
            }
            unset($next);

            $type = 'staticvar';
        }

        // Examine each variable/constant in multi-declarations.
        do {
            $end   = $this->findEndOfCurrentDeclaration($phpcsFile, $start, $endOfStatement);
            $start = $phpcsFile->findNext(Tokens::$emptyTokens, $start, $end, true);
            if ($start === false
                || ($tokens[$stackPtr]['code'] === \T_CONST && $tokens[$start]['code'] !== \T_STRING)
                || ($tokens[$stackPtr]['code'] === \T_STATIC && $tokens[$start]['code'] !== \T_VARIABLE)
            ) {
                // Shouldn't be possible, skip over the problematic part of the statement.
                $start = ($end + 1);
                continue;
            }

            $this->processSubStatement($phpcsFile, $start, $end, $type);

            $start = ($end + 1);

        } while ($end < $endOfStatement);

        // Skip to the end of the statement to prevent duplicate messages for multi-declarations.
        return $endOfStatement;
    }

    /**
     * Find the end of the current constant/variable declaration in a potential
     * multi-declaration statement.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile      The file being scanned.
     * @param int                         $stackPtr       The position of the current token in the
     *                                                    stack passed in $tokens.
     * @param int                         $endOfStatement The ultimate end of the statement.
     *
     * @return int
     */
    private function findEndOfCurrentDeclaration(File $phpcsFile, $stackPtr, $endOfStatement)
    {
        $tokens    = $phpcsFile->getTokens();
        $endTokens = [
            \T_COMMA     => \T_COMMA,
            \T_SEMICOLON => \T_SEMICOLON,
            \T_CLOSE_TAG => \T_CLOSE_TAG,
        ];

        for ($i = $stackPtr; $i <= $endOfStatement; $i++) {
            if (isset($endTokens[$tokens[$i]['code']]) === true) {
                // Found the end of the statement.
                break;
            }

            // Skip nested statements.
            if (isset($tokens[$i]['scope_closer']) === true
                && $i === $tokens[$i]['scope_opener']
            ) {
                $i = $tokens[$i]['scope_closer'];
            } elseif (isset($tokens[$i]['bracket_closer']) === true
                && $i === $tokens[$i]['bracket_opener']
            ) {
                $i = $tokens[$i]['bracket_closer'];
            } elseif (isset($tokens[$i]['parenthesis_closer']) === true
                && $i === $tokens[$i]['parenthesis_opener']
            ) {
                $i = $tokens[$i]['parenthesis_closer'];
            }
        }

        return $i;
    }

    /**
     * Process a statement which _may_ or _may not_ have an initial value.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the variable/constant name token
     *                                               in the stack passed in $tokens.
     * @param int                         $end       The end of the (sub-)statement.
     *                                               This will normally be a comma or semi-colon.
     * @param string                      $type      The "type" of initial value declaration being examined.
     *                                               The type will match one of the keys in the
     *                                               `AbstractInitialValueSniff::$initialValueTypes` property.
     *
     * @return void
     */
    protected function processSubStatement(File $phpcsFile, $stackPtr, $end, $type)
    {
        $tokens = $phpcsFile->getTokens();
        $next   = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), $end, true);
        if ($next === false || $tokens[$next]['code'] !== \T_EQUAL) {
            // No value assigned.
            return;
        }

        $this->processInitialValue($phpcsFile, $stackPtr, ($next + 1), $end, $type);
    }

    /**
     * Process a token which has an initial value.
     *
     * This method must be implemented in child classes.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the variable/constant name token
     *                                               in the stack passed in $tokens.
     * @param int                         $start     The stackPtr to the start of the initial value.
     * @param int                         $end       The stackPtr to the end of the initial value.
     *                                               This will normally be a comma or semi-colon.
     * @param string                      $type      The "type" of initial value declaration being examined.
     *                                               The type will match one of the keys in the
     *                                               `AbstractInitialValueSniff::$initialValueTypes` property.
     *
     * @return void
     */
    abstract protected function processInitialValue(File $phpcsFile, $stackPtr, $start, $end, $type);
}
