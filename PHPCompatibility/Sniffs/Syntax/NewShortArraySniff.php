<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Syntax;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Arrays;

/**
 * Detect use of short array syntax which is available since PHP 5.4.
 *
 * PHP version 5.4
 *
 * @link https://wiki.php.net/rfc/shortsyntaxforarrays
 * @link https://www.php.net/manual/en/language.types.array.php#language.types.array.syntax
 *
 * @since 7.0.0
 * @since 9.0.0 Renamed from `ShortArraySniff` to `NewShortArraySniff`.
 */
class NewShortArraySniff extends Sniff
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
        return Collections::shortArrayListOpenTokensBC();
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('5.3') === false) {
            return;
        }

        if (Arrays::isShortArray($phpcsFile, $stackPtr) === false) {
            return;
        }

        $error = 'Short array syntax (%s) is not supported in PHP 5.3 or lower';
        $data  = ['open'];
        $phpcsFile->addError($error, $stackPtr, 'Found', $data);

        $tokens = $phpcsFile->getTokens();
        $data   = ['close'];
        $phpcsFile->addError($error, $tokens[$stackPtr]['bracket_closer'], 'Found', $data);
    }
}
