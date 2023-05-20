<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2022 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Classes;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Exceptions\ValueError;
use PHPCSUtils\Utils\Constants;

/**
 * Using the "final" modifier for class constants is available since PHP 8.1.
 *
 * PHP version 8.1
 *
 * @link https://wiki.php.net/rfc/final_class_const
 * @link https://www.php.net/manual/en/language.oop5.final.php#language.oop5.final.example.php81
 *
 * @since 10.0.0
 */
final class NewFinalConstantsSniff extends Sniff
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
        return [\T_CONST];
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
        if (ScannedCode::shouldRunOnOrBelow('8.0') === false) {
            return;
        }

        try {
            $properties = Constants::getProperties($phpcsFile, $stackPtr);
        } catch (ValueError $e) {
            // Not an OO constant or parse error.
            return;
        }

        if ($properties['final_token'] === false) {
            // Not a final constant.
            return;
        }

        $phpcsFile->addError(
            'The final modifier for OO constants is not supported in PHP 8.0 or earlier.',
            $properties['final_token'],
            'Found'
        );
    }
}
