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
use PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHPCSUtils\Utils\Variables;

/**
 * Detect and verify the use of typed class property declarations.
 *
 * Typed class property declarations are available since PHP 7.4.
 * - Since PHP 8.0, `mixed` is allowed to be used as a property type.
 *
 * PHP version 7.4+
 *
 * @link https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.core.typed-properties
 * @link https://wiki.php.net/rfc/typed_properties_v2
 * @link https://wiki.php.net/rfc/mixed_type_v2
 *
 * @since 9.2.0
 */
class NewTypedPropertiesSniff extends Sniff
{
    use ComplexVersionNewFeatureTrait;

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
    protected $newTypes = [
        'mixed' => [
            '7.4' => false,
            '8.0' => true,
        ],
        // Union type only.
        'false' => [
            '7.4' => false,
            '8.0' => true,
        ],
        // Union type only.
        'null' => [
            '7.4' => false,
            '8.0' => true,
        ],
    ];

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
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
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
        $type      = \ltrim($properties['type'], '?'); // Trim off potential nullability.
        $type      = \strtolower($type);
        $typeToken = $properties['type_token'];

        if ($this->supportsBelow('7.3') === true) {
            $phpcsFile->addError(
                'Typed properties are not supported in PHP 7.3 or earlier. Found: %s',
                $typeToken,
                'Found',
                [$type]
            );
        } else {
            $types = \explode('|', $type);
            foreach ($types as $type) {
                if (isset($this->newTypes[$type])) {
                    $itemInfo = [
                        'name' => $type,
                    ];
                    $this->handleFeature($phpcsFile, $typeToken, $itemInfo);

                    /*
                     * Nullable mixed type declarations are not allowed, but could have been used prior
                     * to PHP 8 if the type hint referred to a class named "Mixed".
                     * Only throw an error if PHP 8+ needs to be supported.
                     */
                    if (($type === 'mixed' && $properties['nullable_type'] === true)
                        && $this->supportsAbove('8.0') === true
                    ) {
                        $phpcsFile->addError(
                            'Mixed types cannot be nullable, null is already part of the mixed type',
                            $typeToken,
                            'NullableMixed'
                        );
                    }
                } elseif (isset($this->invalidTypes[$type])) {
                    $error = '%s is not supported as a type declaration for properties';
                    $data  = [$type];

                    if ($this->invalidTypes[$type] !== false) {
                        $error .= ' Did you mean %s ?';
                        $data[] = $this->invalidTypes[$type];
                    }

                    $phpcsFile->addError($error, $typeToken, 'InvalidType', $data);
                }
            }
        }

        $endOfStatement = $phpcsFile->findNext(\T_SEMICOLON, ($stackPtr + 1));
        if ($endOfStatement !== false) {
            // Don't throw the same error multiple times for multi-property declarations.
            return ($endOfStatement + 1);
        }
    }


   /**
     * Handle the retrieval of relevant information and - if necessary - throwing of an
     * error for a matched item.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the relevant token in
     *                                               the stack.
     * @param array                       $itemInfo  Base information about the item.
     *
     * @return void
     */
    protected function handleFeature(File $phpcsFile, $stackPtr, array $itemInfo)
    {
        $itemArray   = $this->newTypes[$itemInfo['name']];
        $versionInfo = $this->getVersionInfo($itemArray);

        if (empty($versionInfo['not_in_version'])
            || $this->supportsBelow($versionInfo['not_in_version']) === false
        ) {
            return;
        }

        $this->addError($phpcsFile, $stackPtr, $itemInfo, $versionInfo);
    }


    /**
     * Generates the error for this item.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $stackPtr    The position of the relevant token in
     *                                                 the stack.
     * @param array                       $itemInfo    Base information about the item.
     * @param string[]                    $versionInfo Array with detail (version) information
     *                                                 relevant to the item.
     *
     * @return void
     */
    protected function addError(File $phpcsFile, $stackPtr, array $itemInfo, array $versionInfo)
    {
        // Overrule the default message template.
        $this->msgTemplate = "The '%s' property type is not present in PHP version %s or earlier";

        $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['name'], $versionInfo);

        $phpcsFile->addError($msgInfo['message'], $stackPtr, $msgInfo['errorcode'], $msgInfo['data']);
    }
}
