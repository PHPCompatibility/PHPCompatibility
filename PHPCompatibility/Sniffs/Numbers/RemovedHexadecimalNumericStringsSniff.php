<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Numbers;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\Numbers;
use PHPCSUtils\Utils\TextStrings;

/**
 * PHP 7.0 removed support for recognizing hexadecimal numeric strings as numeric.
 *
 * Type juggling and recognition was inconsistent prior to PHP 7.
 * As of PHP 7, hexadecimal numeric strings are no longer treated as numeric.
 *
 * PHP version 7.0
 *
 * @link https://wiki.php.net/rfc/remove_hex_support_in_numeric_strings
 *
 * @since 7.0.3
 * @since 10.0.0 The check in this sniff was previously contained in the ValidIntegers
 *               sniff and has now been split off to a separate sniff.
 */
class RemovedHexadecimalNumericStringsSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.3
     *
     * @return array
     */
    public function register()
    {
        return [
            \T_CONSTANT_ENCAPSED_STRING,
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.0.3
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (Numbers::isHexidecimalInt(TextStrings::stripQuotes($tokens[$stackPtr]['content'])) === false) {
            return;
        }

        $isError = $this->supportsAbove('7.0');

        $this->addMessage(
            $phpcsFile,
            'The behaviour of hexadecimal numeric strings was inconsistent prior to PHP 7 and support has been removed in PHP 7. Found: %s',
            $stackPtr,
            $isError,
            'Found',
            [$tokens[$stackPtr]['content']]
        );
    }
}
