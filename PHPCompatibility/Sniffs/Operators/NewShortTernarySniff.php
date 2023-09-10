<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Operators;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\Operators;

/**
 * Detect usage of the short ternary (elvis) operator as introduced in PHP 5.3.
 *
 * Performs checks on ternary operators, specifically that the middle expression
 * is not omitted for versions that don't support this.
 *
 * PHP version 5.3
 *
 * @link https://www.php.net/manual/en/migration53.new-features.php
 * @link https://www.php.net/manual/en/language.operators.comparison.php#language.operators.comparison.ternary
 *
 * @since 7.0.0
 * @since 7.0.8 This sniff now throws an error instead of a warning.
 * @since 9.0.0 Renamed from `TernaryOperatorsSniff` to `NewShortTernarySniff`.
 */
class NewShortTernarySniff extends Sniff
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
        return [\T_INLINE_THEN];
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
        if (ScannedCode::shouldRunOnOrBelow('5.2') === false) {
            return;
        }

        if (Operators::isShortTernary($phpcsFile, $stackPtr) === false) {
            return;
        }

        $phpcsFile->addError(
            'Middle may not be omitted from ternary operators in PHP < 5.3',
            $stackPtr,
            'MiddleMissing'
        );
    }
}
