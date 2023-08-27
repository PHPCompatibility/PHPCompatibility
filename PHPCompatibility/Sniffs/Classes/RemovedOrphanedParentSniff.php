<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Classes;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\Conditions;
use PHPCSUtils\Utils\MessageHelper;

/**
 * Using `parent` inside a class without parent is deprecated since PHP 7.4 and removed in PHP 8.0.
 *
 * This will throw a compile-time error in PHP 8.0. In PHP 7.4 an error will only
 * be generated if/when the parent is accessed at run-time.
 *
 * PHP version 7.4
 * PHP version 8.0
 *
 * @link https://www.php.net/manual/en/migration74.deprecated.php#migration74.deprecated.core.parent
 *
 * @since 9.2.0
 */
class RemovedOrphanedParentSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 9.2.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [\T_PARENT];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 9.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('7.4') === false) {
            return;
        }

        $tokens   = $phpcsFile->getTokens();
        $classPtr = Conditions::getLastCondition($phpcsFile, $stackPtr, Tokens::$ooScopeTokens);
        if ($classPtr === false || $tokens[$classPtr]['code'] === \T_TRAIT) {
            // Use outside of a class scope. Not our concern.
            return;
        }

        if (isset($tokens[$classPtr]['scope_opener']) === false) {
            // No scope opener known. Probably a parse error.
            return;
        }

        if ($tokens[$classPtr]['code'] !== \T_INTERFACE) {
            $extends = $phpcsFile->findNext(\T_EXTENDS, ($classPtr + 1), $tokens[$classPtr]['scope_opener']);
            if ($extends !== false) {
                // Class has a parent.
                return;
            }
        }

        /*
         * Work round a tokenizer issue in PHPCS < 3.8.0 (?) where a call to a global
         * `parent()` function would tokenize the `parent` function call label as `T_PARENT`.
         * @link https://github.com/squizlabs/PHP_CodeSniffer/pull/3797
         */
        $prev = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        $next = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($tokens[$prev]['code'] !== \T_NEW
            && $next !== false
            && $tokens[$next]['code'] === \T_OPEN_PARENTHESIS
        ) {
            return;
        }

        $error = 'Using "parent" inside %s is deprecated since PHP 7.4';
        $data  = ['a class without parent'];

        $code    = 'Deprecated';
        $isError = false;

        if (ScannedCode::shouldRunOnOrAbove('8.0') === true) {
            $error  .= ' and removed since PHP 8.0';
            $code    = 'Removed';
            $isError = true;
        }

        if ($tokens[$classPtr]['code'] === \T_INTERFACE) {
            $code .= 'InInterface';
            $data  = ['an interface'];
        }

        MessageHelper::addMessage($phpcsFile, $error, $stackPtr, $isError, $code, $data);
    }
}
