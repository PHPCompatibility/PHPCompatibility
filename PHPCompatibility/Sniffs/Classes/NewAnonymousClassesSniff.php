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

/**
 * Anonymous classes are supported since PHP 7.0.
 *
 * PHP version 7.0
 *
 * @link https://www.php.net/manual/en/language.oop5.anonymous.php
 * @link https://wiki.php.net/rfc/anonymous_classes
 *
 * @since 7.0.0
 */
class NewAnonymousClassesSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [\T_ANON_CLASS];
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('5.6') === false) {
            return;
        }

        $phpcsFile->addError(
            'Anonymous classes are not supported in PHP 5.6 or earlier',
            $stackPtr,
            'Found'
        );
    }
}
