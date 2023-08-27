<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2021 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Variables;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\BackCompat\BCTokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Context;
use PHPCSUtils\Utils\Operators;
use PHPCSUtils\Utils\Parentheses;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\TextStrings;
use ReflectionFunction;

/**
 * Detect indirect modification of the `$GLOBALS` variable and related changes in PHP 8.1.
 *
 * As of PHP 8.1, the `$GLOBALS` array is effectively a read-only copy of the global symbol table.
 *
 * This has the following side-effects:
 * - The recursive `$GLOBALS['GLOBALS']` entry no longer exists.
 * - Appending unnamed entries to the `$GLOBALS` array is no longer supported.
 * - Assignments which overwrite the `$GLOBALS` array are no longer allowed.
 *   This includes unsetting the `$GLOBALS` variable and passing the `$GLOBALS` variable
 *   to functions which would modify the received parameter by reference.
 * - Creating a reference to the `$GLOBALS` array is no longer allowed.
 *
 * Additionally, there are a couple of situations in which the behaviour around the use of $GLOBALS
 * has changed.
 *
 * PHP version 8.1
 *
 * @link https://www.php.net/manual/en/migration81.incompatible.php#migration81.incompatible.core.globals-access
 * @link https://wiki.php.net/rfc/restrict_globals_usage
 *
 * @since 10.0.0
 */
final class RemovedIndirectModificationOfGlobalsSniff extends Sniff
{

    const WRITE_ERROR = 'Only individual keys in the $GLOBALS variable can be modified. The top-level $GLOBALS variable is read-only since PHP 8.1. Detected: %s';

    /**
     * List of PHP native functions.
     *
     * Will be set the first time the list is needed.
     *
     * @var array<string, int> Key is the function name in lowercase, value irrelevant.
     */
    private $phpNativeFunctions;

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [\T_VARIABLE];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('8.1') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr]['content'] !== '$GLOBALS') {
            return;
        }

        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($nextNonEmpty === false) {
            // Live coding or parse error.
            return;
        }

        if ($tokens[$nextNonEmpty]['code'] === \T_OPEN_SQUARE_BRACKET) {
            if (isset($tokens[$nextNonEmpty]['bracket_closer']) === false) {
                // Live coding or parse error.
                return;
            }

            $searchStart = ($nextNonEmpty + 1);
            $searchEnd   = $tokens[$nextNonEmpty]['bracket_closer'];

            $hasNonEmptyTokens = $phpcsFile->findNext(Tokens::$emptyTokens, $searchStart, $searchEnd, true);
            if ($hasNonEmptyTokens === false) {
                // Assigning to a new array key.
                $phpcsFile->addError(
                    'Appending a new non-named array entry to the $GLOBALS variable is not allowed since PHP 8.1.',
                    $stackPtr,
                    'Appending'
                );
                return;
            }

            $allowedNumeric             = Tokens::$emptyTokens;
            $allowedNumeric[\T_LNUMBER] = \T_LNUMBER;
            $allowedNumeric[\T_DNUMBER] = \T_DNUMBER;

            $hasNonNumeric = $phpcsFile->findNext($allowedNumeric, $searchStart, $searchEnd, true);
            if ($hasNonNumeric === false) {
                if (ScannedCode::shouldRunOnOrBelow('8.0') === true) {
                    // This warning will only be thrown when both PHP < 8.1 and PHP 8.1+ need to be supported.
                    $phpcsFile->addWarning(
                        'The way variables with an integer or floating point variable name are stored in the $GLOBALS variable has changed in PHP 8.1. Please review your code to evaluate the impact.',
                        $stackPtr,
                        'NumericKey'
                    );
                }
                return;
            }

            /*
             * Note: doesn't allow for heredoc/nowdoc string keys, but that would be excedingly rare anyway.
             * Also doesn't allow for T_DOUBLE_QUOTED_STRING as that means there is variable interpolation
             * and that would never result in a match anyway.
             */
            $allowedText = Tokens::$emptyTokens;
            $allowedText[\T_CONSTANT_ENCAPSED_STRING] = \T_CONSTANT_ENCAPSED_STRING;

            $hasNonText = $phpcsFile->findNext($allowedText, $searchStart, $searchEnd, true);
            if ($hasNonText === false) {
                $key = $phpcsFile->findNext([\T_CONSTANT_ENCAPSED_STRING], $searchStart, $searchEnd);
                if (TextStrings::stripQuotes($tokens[$key]['content']) === 'GLOBALS') {
                    $phpcsFile->addError(
                        'The recursive $GLOBALS[\'GLOBALS\'] key no longer exists since PHP 8.1.',
                        $key,
                        'RecursiveKeyAccess'
                    );
                    return;
                }
            }

            // In all other cases, this will be in access/use of $GLOBALS with a key without changed behaviour.
            return;
        }

        $lastOwner = Parentheses::getLastOwner($phpcsFile, $stackPtr);
        if ($tokens[$lastOwner]['code'] === \T_UNSET) {
            $phpcsFile->addError(
                'Unsetting the $GLOBALS variable is not allowed since PHP 8.1.',
                $stackPtr,
                'Unset'
            );
            return;
        }

        if ($tokens[$lastOwner]['code'] === \T_LIST) {
            $phpcsFile->addError(
                \sprintf(self::WRITE_ERROR, 'list assignment'),
                $stackPtr,
                'ListWrite'
            );
            return;
        }

        if ($tokens[$lastOwner]['code'] === \T_FOREACH) {
            $inForeach = Context::inForeachCondition($phpcsFile, $stackPtr);
            if ($inForeach === 'beforeAs') {
                // Read access
                return;
            }

            if ($inForeach === 'afterAs') {
                $phpcsFile->addError(
                    \sprintf(self::WRITE_ERROR, 'foreach assignment'),
                    $stackPtr,
                    'ForeachWrite'
                );
                return;
            }
        }

        if (isset(BCTokens::assignmentTokens()[$tokens[$nextNonEmpty]['code']]) === true) {
            $phpcsFile->addError(
                \sprintf(self::WRITE_ERROR, 'assignment to $GLOBALS'),
                $stackPtr,
                'Overwrite'
            );
            return;
        }

        $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if ($tokens[$prevNonEmpty]['code'] === \T_BITWISE_AND
            && Operators::isReference($phpcsFile, $prevNonEmpty) === true
        ) {
            $phpcsFile->addError(
                'Creating a reference to the $GLOBALS variable is no longer supported since PHP 8.1.',
                $stackPtr,
                'Reference'
            );
            return;
        }

        if (($tokens[$prevNonEmpty]['code'] === \T_EQUAL
            || $tokens[$prevNonEmpty]['code'] === \T_COALESCE_EQUAL)
                && ScannedCode::shouldRunOnOrBelow('8.0') === true
        ) {
            // This warning will only be thrown when both PHP < 8.1 and PHP 8.1+ need to be supported.
            $phpcsFile->addWarning(
                'Prior to PHP 8.1, assignment of $GLOBALS to another variable would effectively function like a reference. Since PHP 8.1, it creates a by-value copy.',
                $stackPtr,
                'AssignmentOfGlobals'
            );
            return;
        }

        if (isset(Collections::incrementDecrementOperators()[$tokens[$prevNonEmpty]['code']]) === true
            || isset(Collections::incrementDecrementOperators()[$tokens[$nextNonEmpty]['code']]) === true
        ) {
            $phpcsFile->addError(
                \sprintf(self::WRITE_ERROR, 'variable increment/decrement'),
                $stackPtr,
                'IncrementDecrement'
            );
            return;
        }

        $functionPtr = $this->isInNamedPHPNativeFunctionCall($phpcsFile, $stackPtr);
        if ($functionPtr !== false) {
            $this->checkFunctionCallForPassByReference($phpcsFile, $stackPtr, $functionPtr);
        }
    }

    /**
     * Check if `$GLOBALS` is (part of) a parameter passed to a global function call to a PHP native function.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token.
     *
     * @return int|false Integer stack pointer to the function name token
     *                   or false when not in a function call.
     */
    private function isInNamedPHPNativeFunctionCall(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $lastOpenParens = Parentheses::getLastOpener($phpcsFile, $stackPtr);
        if ($lastOpenParens === false
            || isset($tokens[$lastOpenParens]['parenthesis_owner']) === true
        ) {
            return false;
        }

        $maybeLabel = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($lastOpenParens - 1), null, true);
        if ($maybeLabel === false || $tokens[$maybeLabel]['code'] !== \T_STRING) {
            // Not a named function call.
            return false;
        }

        // Set the native function property only once.
        if (isset($this->phpNativeFunctions) === false) {
            $functions                = \get_defined_functions();
            $functions                = \array_flip($functions['internal']);
            $this->phpNativeFunctions = \array_change_key_case($functions, \CASE_LOWER);
        }

        if (isset($this->phpNativeFunctions[\strtolower($tokens[$maybeLabel]['content'])]) === false) {
            // Definitely not a PHP native function call.
            return false;
        }

        $beforeLabel = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($maybeLabel - 1), null, true);
        if ($beforeLabel !== false
            && (isset(Collections::objectOperators()[$tokens[$beforeLabel]['code']]) === true
            || $tokens[$beforeLabel]['code'] === \T_NEW)
        ) {
            // Method call or class instantiation, not global function call.
            return false;
        }

        if ($tokens[$beforeLabel]['code'] === \T_NS_SEPARATOR) {
            $prevPrevToken = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($beforeLabel - 1), null, true);
            if ($tokens[$prevPrevToken]['code'] === \T_STRING
                || $tokens[$prevPrevToken]['code'] === \T_NAMESPACE
            ) {
                // Namespaced function call.
                return false;
            }
        }

        return $maybeLabel;
    }

    /**
     * Check if `$GLOBALS` is in a parameter which is passed by reference.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $stackPtr    The position of the current token.
     * @param int                         $functionPtr The position of the function call name token.
     *
     * @return void
     */
    private function checkFunctionCallForPassByReference(File $phpcsFile, $stackPtr, $functionPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $reflectionFunction = new ReflectionFunction($tokens[$functionPtr]['content']);
        $declaredParams     = $reflectionFunction->getParameters();

        $referenceParams = [];
        foreach ($declaredParams as $param) {
            if ($param->isPassedByReference() === true) {
                // Note: position is 0-based, while PassedParameters uses 1-based.
                $referenceParams[$param->getPosition()] = $param->getName();
            }
        }

        if (empty($referenceParams)) {
            // Function doesn't change any parameters by reference.
            return;
        }

        $params = PassedParameters::getParameters($phpcsFile, $functionPtr);
        foreach ($referenceParams as $position => $name) {
            $param = PassedParameters::getParameterFromStack($params, ($position + 1), $name);
            if ($param !== false
                && ($stackPtr >= $param['start'] && $stackPtr <= $param['end'])
            ) {
                // $GLOBALS was passed to the function call by reference.
                $phpcsFile->addError(
                    \sprintf(self::WRITE_ERROR, 'pass by reference'),
                    $stackPtr,
                    'PassByReference'
                );
            }
        }
    }
}
