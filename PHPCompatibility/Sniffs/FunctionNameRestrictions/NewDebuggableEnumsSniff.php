<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionNameRestrictions;

use PHP_CodeSniffer\Files\File;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Utils\ObjectDeclarations;

/**
 * The __debugInfo() magic method can be declared on enums since PHP 8.6.
 *
 * PHP version 8.6
 *
 * @link https://wiki.php.net/rfc/debugable-enums
 * @link https://www.php.net/language.oop5.magic#object.debuginfo
 *
 * @since 10.0.0
 */
final class NewDebuggableEnumsSniff extends Sniff
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
        return [\T_ENUM];
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
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('8.5') === false) {
            return;
        }

        $ooMethods = ObjectDeclarations::getDeclaredMethods($phpcsFile, $stackPtr);
        if (empty($ooMethods)) {
            // No methods declared in the enum. Bow out.
            return;
        }

        $ooMethods = \array_change_key_case($ooMethods, \CASE_LOWER);

        if (isset($ooMethods['__debuginfo']) === false) {
            // The enum doesn't declare the __debugInfo() method.
            return;
        }

        $phpcsFile->addError(
            'The magic method __debugInfo() could not be declared on an enum prior to PHP 8.6.',
            $ooMethods['__debuginfo'],
            'Found'
        );
    }
}
