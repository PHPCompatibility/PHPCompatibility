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
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHPCSUtils\Utils\Scopes;
use PHPCSUtils\Utils\Variables;

/**
 * Using the "final" modifier for properties is available since PHP 8.4.
 *
 * Note: the "final" modifier is not supported (yet) in constructor property promotion.
 *
 * PHP version 8.4
 *
 * @link https://wiki.php.net/rfc/property-hooks#final_hooks
 *
 * @since 10.0.0
 */
final class NewFinalPropertiesSniff extends Sniff
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
        return Collections::ooPropertyScopes();
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
        if (ScannedCode::shouldRunOnOrBelow('8.3') === false) {
            return;
        }

        $properties = ObjectDeclarations::getDeclaredProperties($phpcsFile, $stackPtr);
        if (empty($properties)) {
            // There are no declared properties.
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $skipTo = $stackPtr;
        foreach ($properties as $name => $ptr) {
            if ($skipTo > $ptr) {
                // Don't throw the same error multiple times for multi-property declarations.
                continue;
            }

            if (Scopes::isOOProperty($phpcsFile, $ptr) === false) {
                // This must be a constructor promoted property. Final is not (yet) supported.
                continue;
            }

            $propertyInfo = Variables::getMemberProperties($phpcsFile, $ptr);
            if ($propertyInfo['is_final'] === false) {
                // Not a final property.
                continue;
            }

            $finalPtr = $phpcsFile->findPrevious([\T_SEMICOLON, \T_FINAL], ($ptr - 1));
            if ($finalPtr === false || $tokens[$finalPtr]['code'] !== \T_FINAL) {
                // Shouldn't be possible, but just in case.
                $finalPtr = $ptr; // @codeCoverageIgnore
            }

            $phpcsFile->addError(
                'The final modifier for OO properties is not supported in PHP 8.3 or earlier.',
                $finalPtr,
                'Found'
            );

            $endOfStatement = $phpcsFile->findNext([\T_SEMICOLON, \T_CLOSE_TAG], ($ptr + 1));
            if ($endOfStatement !== false) {
                // Don't throw the same error multiple times for multi-property declarations.
                $skipTo = ($endOfStatement + 1);
            }
        }
    }
}
