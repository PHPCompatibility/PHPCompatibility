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

use PHPCompatibility\AbstractNewFeatureSniff;
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
 * @since 10.0.0 Now extends the `AbstractNewFeatureSniff` instead of the base `Sniff` class.
 */
class NewTypedPropertiesSniff extends AbstractNewFeatureSniff
{

    /**
     * A list of new types.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the keyword appears.
     *
     * Note: this list should **not** include the initially supported types as that
     * is handled via the "Typed properties are not supported in PHP 7.3 or earlier"
     * error message.
     *
     * The types which were supported at the introduction of typed properties in PHP 7.4 were:
     * bool, int, float, string, array, object, iterable, self, parent,
     * any class or interface name, as well as nullability for all these.
     * {@link https://wiki.php.net/rfc/typed_properties_v2#supported_types}
     *
     * @since 10.0.0
     *
     * @var array(string => array(string => bool))
     */
    protected $newTypes = [];

    /**
     * Invalid types.
     *
     * The array lists : the invalid type => what was probably intended/alternative
     * or false if no alternative available.
     *
     * @since 10.0.0
     *
     * @var array(string => string|false)
     */
    protected $invalidTypes = [
        'boolean'  => 'bool',
        'integer'  => 'int',
        'callable' => false,
        'void'     => false,
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 9.2.0
     *
     * @return array
     */
    public function register()
    {
        return [\T_VARIABLE];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 9.2.0
     * @since 10.0.0 Refactored to work with the AbstractNewFeature sniff.
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
        $type      = ltrim($properties['type'], '?'); // Trim off potential nullability.
        $type      = strtolower($type);
        $typeToken = $properties['type_token'];

        if ($this->supportsBelow('7.3') === true) {
            $phpcsFile->addError(
                'Typed properties are not supported in PHP 7.3 or earlier. Found: %s',
                $typeToken,
                'Found',
                [$type]
            );
        } elseif (isset($this->newTypes[$type])) {
            $itemInfo = [
                'name' => $type,
            ];
            $this->handleFeature($phpcsFile, $typeToken, $itemInfo);
        } elseif (isset($this->invalidTypes[$type])) {
            $error = "%s is not supported as a type declaration for properties";
            $data  = [$type];

            if ($this->invalidTypes[$type] !== false) {
                $error .= " Did you mean %s ?";
                $data[] = $this->invalidTypes[$type];
            }

            $phpcsFile->addError($error, $typeToken, 'InvalidType', $data);
        }

        $endOfStatement = $phpcsFile->findNext(\T_SEMICOLON, ($stackPtr + 1));
        if ($endOfStatement !== false) {
            // Don't throw the same error multiple times for multi-property declarations.
            return ($endOfStatement + 1);
        }
    }


    /**
     * Get the relevant sub-array for a specific item from a multi-dimensional array.
     *
     * @since 10.0.0
     *
     * @param array $itemInfo Base information about the item.
     *
     * @return array Version and other information about the item.
     */
    public function getItemArray(array $itemInfo)
    {
        return $this->newTypes[$itemInfo['name']];
    }


    /**
     * Get the error message template for this sniff.
     *
     * @since 10.0.0
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return "The '%s' property type is not present in PHP version %s or earlier";
    }
}
