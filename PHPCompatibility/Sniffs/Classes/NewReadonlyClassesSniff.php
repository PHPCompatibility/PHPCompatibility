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
use PHPCSUtils\Utils\ObjectDeclarations;

/**
 * Declaring classes as readonly is supported since PHP 8.2.
 *
 * PHP version 8.2
 *
 * @link https://wiki.php.net/rfc/readonly_classes
 * @link https://www.php.net/manual/en/migration82.new-features.php#migration82.new-features.core.readonly-classes
 * @link https://www.php.net/manual/en/language.oop5.basic.php#language.oop5.basic.class.readonly
 *
 * @since 10.0.0
 */
final class NewReadonlyClassesSniff extends Sniff
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
        return [\T_CLASS];
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
        if (ScannedCode::shouldRunOnOrBelow('8.1') === false) {
            return;
        }

        $properties = ObjectDeclarations::getClassProperties($phpcsFile, $stackPtr);
        if ($properties['is_readonly'] === false) {
            return;
        }

        $phpcsFile->addError(
            'Readonly classes are not supported in PHP 8.1 or earlier.',
            $properties['readonly_token'],
            'Found'
        );
    }
}
