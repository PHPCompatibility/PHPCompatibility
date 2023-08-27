<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2022 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Numbers;

use PHP_CodeSniffer\Files\File;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Utils\Numbers;

/**
 * Support for octal integers using an explicit "0o"/"0O" prefix in integer literals is available since PHP 8.1.
 *
 * PHP version 8.1
 *
 * @link https://www.php.net/manual/en/migration81.new-features.php#migration81.new-features.core.octal-literal-prefix
 * @link https://wiki.php.net/rfc/explicit_octal_notation
 *
 * @since 10.0.0
 */
class NewExplicitOctalNotationSniff extends Sniff
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
        return [\T_LNUMBER];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
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
        if (ScannedCode::shouldRunOnOrBelow('8.0') === false) {
            return;
        }

        $numberInfo = Numbers::getCompleteNumber($phpcsFile, $stackPtr);
        if (\stripos($numberInfo['content'], '0o') !== 0) {
            // Not using explicit octal notation.
            return;
        }

        $phpcsFile->addError(
            'The explicit integer octal literal prefix "0o" is not supported in PHP 8.0 or lower. Found: %s',
            $stackPtr,
            'Found',
            [$numberInfo['orig_content']]
        );

        // Skip past the parts we've already taken into account to prevent double reporting.
        return ($numberInfo['last_token'] + 1);
    }
}
