<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionDeclarations;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\Scopes;

/**
 * As of PHP 8.0, when an object constructor exit()s, the destructor will no longer be called.
 *
 * Note: shutdown functions registered with `register_shutdown_function()` will still be called.
 *
 * PHP version 8.0
 *
 * @link https://github.com/php/php-src/blob/71bfa5344ab207072f4cd25745d7023096338385/UPGRADING#L200-L201
 *
 * @since 10.0.0
 */
class RemovedCallingDestructAfterConstructorExitSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array
     */
    public function register()
    {
        return [\T_FUNCTION];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('8.0') === false) {
            return;
        }

        if (Scopes::isOOMethod($phpcsFile, $stackPtr) === false) {
            // Function, not method.
            return;
        }

        $name = FunctionDeclarations::getName($phpcsFile, $stackPtr);
        if (empty($name) === true) {
            // Parse error or live coding.
            return;
        }

        if (\strtolower($name) !== '__construct') {
            // The rule only applies to constructors. Bow out.
            return;
        }

        $tokens = $phpcsFile->getTokens();
        if (isset($tokens[$stackPtr]['scope_opener'], $tokens[$stackPtr]['scope_closer']) === false) {
            // Parse error or live coding.
            return;
        }

        $current = $tokens[$stackPtr]['scope_opener'];
        $end     = $tokens[$stackPtr]['scope_closer'];
        do {
            $current = $phpcsFile->findNext(\T_EXIT, ($current + 1), $end);
            if ($current === false) {
                // No exit found.
                return;
            }

            $phpcsFile->addError(
                'When %s() is called within an object constructor, the object destructor will no longer be called since PHP 8.0',
                $current,
                'Found',
                [$tokens[$current]['content']]
            );
        } while (true);
    }
}
