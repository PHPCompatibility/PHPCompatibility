<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Helpers;

use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\Namespaces;

/**
 * Helper functions to figure out the fully qualified name/namespace of a token.
 *
 * ---------------------------------------------------------------------------------------------
 * This class is only intended for internal use by PHPCompatibility and is not part of the public API.
 * This also means that it has no promise of backward compatibility. Use at your own risk.
 * ---------------------------------------------------------------------------------------------
 *
 * {@internal The functionality in this class will likely be replaced at some point in
 * the future by functions from PHPCSUtils.}
 *
 * @since 10.0.0 These functions were moved from the generic `Sniff` class to this class.
 */
final class ResolveHelper
{

    /**
     * Get the Fully Qualified name for a class/function/constant etc.
     *
     * Checks if a class/function/constant name is already fully qualified and
     * if not, enriches it with the relevant namespace information.
     *
     * @since 7.0.3
     * @since 10.0.0 This method is now static.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the token.
     * @param string                      $name      The class / function / constant name.
     *
     * @return string
     */
    public static function getFQName(File $phpcsFile, $stackPtr, $name)
    {
        if (\strpos($name, '\\') === 0) {
            // Already fully qualified.
            return $name;
        }

        // Remove the namespace keyword if used.
        if (\strpos($name, 'namespace\\') === 0) {
            $name = \substr($name, 10);
        }

        $namespace = Namespaces::determineNamespace($phpcsFile, $stackPtr);

        if ($namespace === '') {
            return '\\' . $name;
        } else {
            return '\\' . $namespace . '\\' . $name;
        }
    }
}
