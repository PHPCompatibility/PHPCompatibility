<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Utils\ControlStructures;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Declaring encoding using block mode is no longer supported as of PHP 8.7.
 *
 * PHP version 8.7
 *
 * @link https://www.php.net/control-structures.declare.php#control-structures.declare.encoding
 * @link https://github.com/php/php-src/pull/9455
 *
 * @since 10.0.0
 */
final class RemovedDeclareEncodingBlockModeSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [\T_DECLARE];
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
        if (ScannedCode::shouldRunOnOrAbove('8.7') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        if (isset($tokens[$stackPtr]['parenthesis_opener'], $tokens[$stackPtr]['parenthesis_closer']) === false) {
            // Parse error. Bow out.
            return;
        }

        if (isset($tokens[$stackPtr]['scope_opener']) === false) {
            // Not using block mode.
            return;
        }

        // Call PHPCSUtils method to figure out if the declare statement has an encoding directive.
        // If not, bow out.

        // Check if the scope contains a namespace declaration.
        // If so, bow out, as this was never supported.

        // Otherwise throw error.
        $message   = 'An encoding declaration can not use block mode since PHP 8.7';
        $errorCode = 'Removed';

        $phpcsFile->addError($message, $stackPtr, $errorCode);
    }
}
