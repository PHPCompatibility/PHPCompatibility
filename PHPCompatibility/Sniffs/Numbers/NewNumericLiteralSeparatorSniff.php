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

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Utils\Numbers;

/**
 * Support for an underscore in numeric literals to visually separate groups of digits
 * is available since PHP 7.4.
 *
 * PHP version 7.4
 *
 * @link https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.core.numeric-literal-separator
 * @link https://wiki.php.net/rfc/numeric_literal_separator
 *
 * @since 10.0.0
 */
class NewNumericLiteralSeparatorSniff extends Sniff
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
        return [
            \T_LNUMBER,
            \T_DNUMBER,
        ];
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
        if (ScannedCode::shouldRunOnOrBelow('7.3') === false) {
            return;
        }

        try {
            $numberInfo = Numbers::getCompleteNumber($phpcsFile, $stackPtr);
            if ($numberInfo['orig_content'] === $numberInfo['content']) {
                // Content is the same, i.e. no underscores found, move on.
                return;
            }

            $phpcsFile->addError(
                'The use of underscore separators in numeric literals is not supported in PHP 7.3 or lower. Found: %s',
                $stackPtr,
                'Found',
                [$numberInfo['orig_content']]
            );

            // Skip past the parts we've already taken into account to prevent double reporting.
            return ($numberInfo['last_token'] + 1);
        } catch (RuntimeException $e) {
            // Running on an unsupported PHPCS version.
            return;
        }
    }
}
