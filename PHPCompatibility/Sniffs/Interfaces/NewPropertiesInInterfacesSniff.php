<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2022 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Interfaces;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\ObjectDeclarations;

/**
 * Detect declaration of properties in interfaces, which is available since PHP 8.4.0.
 *
 * PHP version 8.4
 *
 * @link https://wiki.php.net/rfc/property-hooks#interfaces
 *
 * @since 10.0.0
 */
final class NewPropertiesInInterfacesSniff extends Sniff
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
        return [\T_INTERFACE];
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
        if (ScannedCode::shouldRunOnOrBelow('8.3') === false) {
            return;
        }

        $properties = ObjectDeclarations::getDeclaredProperties($phpcsFile, $stackPtr);
        if (empty($properties)) {
            // There are no declared properties.
            return;
        }

        $skipTo = $stackPtr;
        foreach ($properties as $name => $ptr) {
            if ($skipTo > $ptr) {
                // Don't throw the same error multiple times for multi-property declarations.
                continue;
            }

            $phpcsFile->addError(
                'Declaring properties in interfaces is not supported in PHP 8.3 or earlier.',
                $ptr,
                'Found'
            );

            $endOfStatement = $phpcsFile->findNext([\T_SEMICOLON, \T_CLOSE_TAG, \T_OPEN_CURLY_BRACKET], ($ptr + 1));
            if ($endOfStatement !== false) {
                // Don't throw the same error multiple times for multi-property declarations.
                $skipTo = ($endOfStatement + 1);
            }
        }
    }
}
