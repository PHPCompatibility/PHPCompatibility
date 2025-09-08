<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionUse;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Conditions;
use PHPCSUtils\Utils\Context;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\Parentheses;

/**
 * Detect usage of `func_get_args()`, `func_get_arg()` and `func_num_args()` in invalid context.
 *
 * Checks for:
 * - Prior to PHP 5.3, these functions could not be used as a function call parameter.
 * - Calling these functions from the outermost scope of a file which has been included by
 *   calling `include` or `require` from within a function in the calling file, worked
 *   prior to PHP 5.3. As of PHP 5.3, this will generate a warning and will always return false/-1.
 *   If the file was called directly or included in the global scope, calls to these
 *   functions would already generate a warning prior to PHP 5.3.
 *
 * PHP version 5.3
 *
 * @link https://www.php.net/manual/en/migration53.incompatible.php
 *
 * @since 8.2.0
 * @since 10.0.0 This class is now `final`.
 */
final class ArgumentFunctionsUsageSniff extends Sniff
{

    /**
     * The target functions for this sniff.
     *
     * @since 8.2.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'func_get_args' => true,
        'func_get_arg'  => true,
        'func_num_args' => true,
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 8.2.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [
            \T_STRING,
            \T_NAME_FULLY_QUALIFIED,
            // Only registering arrow functions to allow for skipping over them.
            \T_FN,
        ];
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr]['code'] === \T_FN
            && isset($tokens[$stackPtr]['scope_closer'])
        ) {
            // Skip over everything within an arrow function.
            return $tokens[$stackPtr]['scope_closer'];
        }

        $functionLc = \strtolower(\ltrim($tokens[$stackPtr]['content'], '\\'));
        if (isset($this->targetFunctions[$functionLc]) === false) {
            return;
        }

        // Next non-empty token should be the open parenthesis.
        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($nextNonEmpty === false
            || $tokens[$nextNonEmpty]['code'] !== \T_OPEN_PARENTHESIS
            || isset($tokens[$nextNonEmpty]['parenthesis_owner'])
        ) {
            return;
        }

        if (Context::inAttribute($phpcsFile, $stackPtr) === true) {
            // Class instantiation in attribute, not function call.
            return;
        }

        $ignore  = [
            \T_NEW => true,
        ];
        $ignore += Collections::objectOperators();

        $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if (isset($ignore[$tokens[$prevNonEmpty]['code']]) === true) {
            // Not a call to a PHP function.
            return;
        } elseif ($tokens[$prevNonEmpty]['code'] === \T_NS_SEPARATOR) {
            $prevPrevToken = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($prevNonEmpty - 1), null, true);
            if ($tokens[$prevPrevToken]['code'] === \T_STRING
                || $tokens[$prevPrevToken]['code'] === \T_NAMESPACE
            ) {
                // Namespaced function on PHPCS 3.x.
                return;
            }
        }

        $data = [$tokens[$stackPtr]['content']];

        /*
         * Check for use of the functions in the global scope.
         *
         * PHPCS can not determine whether a file is included from within a function in
         * another file, so always throw a warning/error.
         */
        if (Conditions::hasCondition($phpcsFile, $stackPtr, Collections::functionDeclarationTokens()) === false) {
            $isError = false;
            $message = 'Use of %s() outside of a user-defined function is only supported if the file is included from within a user-defined function in another file prior to PHP 5.3.';

            if (ScannedCode::shouldRunOnOrAbove('5.3') === true) {
                $isError  = true;
                $message .= ' As of PHP 5.3, it is no longer supported at all.';
            }

            MessageHelper::addMessage($phpcsFile, $message, $stackPtr, $isError, 'OutsideFunctionScope', $data);
        }

        /*
         * Check for use of the functions as a parameter in a function call.
         */
        if (ScannedCode::shouldRunOnOrBelow('5.2') === false) {
            return;
        }

        $opener = Parentheses::getLastOpener($phpcsFile, $stackPtr);
        if ($opener === false
            || isset($tokens[$opener]['parenthesis_owner']) === true
        ) {
            // Not nested in parentheses at all or nested in "owned" parentheses, which are never function calls.
            return;
        }

        $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($opener - 1), null, true);
        if (isset(Collections::nameTokens()[$tokens[$prevNonEmpty]['code']]) === false) {
            // Not nested in a function call.
            return;
        }

        $phpcsFile->addError(
            '%s() could not be used in parameter lists prior to PHP 5.3.',
            $stackPtr,
            'InParameterList',
            $data
        );
    }
}
