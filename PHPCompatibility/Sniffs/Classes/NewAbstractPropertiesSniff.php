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
 * Using the "abstract" modifier for (hooked) properties is available since PHP 8.4.
 *
 * Notes:
 * - The "abstract" modifier is not supported for multi-property declarations,
 *   as a hook is required and hooks are not supported for multi-property.
 * - The "abstract" modifier is not supported in constructor property promotion as that wouldn't make sense.
 * - The "abstract" modifier is also not supported for interface properties as those are implicitly abstract
 *   (but that's not the concern of this sniff).
 *
 * PHP version 8.4
 *
 * @link https://wiki.php.net/rfc/property-hooks#abstract_properties
 *
 * @since 10.0.0
 */
final class NewAbstractPropertiesSniff extends Sniff
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
            if (Scopes::isOOProperty($phpcsFile, $ptr) === false) {
                // This must be a constructor promoted property. Abstract is not supported.
                continue;
            }

            $propertyInfo = Variables::getMemberProperties($phpcsFile, $ptr);
            if ($propertyInfo['is_abstract'] === false) {
                // Not a abstract property.
                continue;
            }

            $abstractPtr = $phpcsFile->findPrevious([\T_SEMICOLON, \T_ABSTRACT], ($ptr - 1));
            if ($abstractPtr === false || $tokens[$abstractPtr]['code'] !== \T_ABSTRACT) {
                // Shouldn't be possible, but just in case.
                $abstractPtr = $ptr; // @codeCoverageIgnore
            }

            $phpcsFile->addError(
                'The abstract modifier for OO properties is not supported in PHP 8.3 or earlier.',
                $abstractPtr,
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
