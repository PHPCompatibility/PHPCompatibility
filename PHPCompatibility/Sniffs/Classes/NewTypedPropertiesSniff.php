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

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHPCSUtils\Utils\Variables;

/**
 * Typed class property declarations are available since PHP 7.4.
 *
 * PHP version 7.4
 *
 * @link https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.core.typed-properties
 * @link https://wiki.php.net/rfc/typed_properties_v2
 *
 * @since 9.2.0
 */
class NewTypedPropertiesSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 9.2.0
     *
     * @return array
     */
    public function register()
    {
        return array(\T_VARIABLE);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 9.2.0
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        try {
            $properties = Variables::getMemberProperties($phpcsFile, $stackPtr);
        } catch (RuntimeException $e) {
            // Not a class property.
            return;
        }

        if ($properties['type'] === '') {
            // Not a typed property.
            return;
        }

        // Still here ? In that case, this will be a typed property.
        if ($this->supportsBelow('7.3') === true) {
            $phpcsFile->addError(
                'Typed properties are not supported in PHP 7.3 or earlier. Found: %s',
                $properties['type_token'],
                'Found',
                array($properties['type'])
            );
        }

        if ($this->supportsAbove('7.4') === true) {
            $type = $properties['type'];
            if ($properties['nullable_type'] === true) {
                $type = ltrim($type, '?');
            }

            if ($type === 'void' || $type === 'callable') {
                $phpcsFile->addError(
                    '%s is not supported as a type declaration for properties',
                    $properties['type_token'],
                    'InvalidType',
                    array($type)
                );
            }
        }

        $endOfStatement = $phpcsFile->findNext(\T_SEMICOLON, ($stackPtr + 1));
        if ($endOfStatement !== false) {
            // Don't throw the same error multiple times for multi-property declarations.
            return ($endOfStatement + 1);
        }
    }
}
