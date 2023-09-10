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

use PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\Scopes;
use PHPCSUtils\Utils\Variables;

/**
 * Detect and verify the use of typed class property declarations.
 *
 * Typed class property declarations are available since PHP 7.4.
 * - Since PHP 8.0, `mixed` is allowed to be used as a property type.
 * - Since PHP 8.0, union types are supported and the union-only `false` and `null` types are available.
 * - Since PHP 8.1, intersection types are supported for class/interface names.
 * - Since PHP 8.2, `false` and `null` can be used as stand-alone types.
 * - Since PHP 8.2, the `true` sub-type is available.
 *
 * PHP version 7.4+
 *
 * @link https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.core.typed-properties
 * @link https://wiki.php.net/rfc/typed_properties_v2
 * @link https://wiki.php.net/rfc/mixed_type_v2
 * @link https://wiki.php.net/rfc/union_types_v2
 * @link https://wiki.php.net/rfc/pure-intersection-types
 * @link https://wiki.php.net/rfc/null-false-standalone-types
 * @link https://wiki.php.net/rfc/true-type
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
     * @var array<string, array<string, bool>>
     */
    protected $newTypes = [
        'mixed' => [
            '7.4' => false,
            '8.0' => true,
        ],
        // Union type only in PHP 8.0 and 8.1.
        'false' => [
            '7.4' => false,
            '8.0' => true,
        ],
        // Union type only in PHP 8.0 and 8.1.
        'null' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'true' => [
            '8.1' => false,
            '8.2' => true,
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
     * @var array<string, string|false>
     */
    protected $invalidTypes = [
        'boolean'  => 'bool',
        'integer'  => 'int',
        'callable' => false,
        'void'     => false,
        'never'    => false,
    ];

    /**
     * Types which are only allowed to occur in union types in PHP 8.0 and 8.1.
     *
     * @since 10.0.0
     *
     * @var array<string, true>
     */
    protected $unionOnlyTypes = [
        'false' => true,
        'null'  => true,
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 9.2.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [
            \T_VARIABLE,
            \T_FUNCTION,
        ];
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
        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr]['code'] === \T_VARIABLE) {
            if (Scopes::isOOProperty($phpcsFile, $stackPtr) === false) {
                // Not a class property.
                return;
            }

            $properties = Variables::getMemberProperties($phpcsFile, $stackPtr);
            if ($properties['type'] === '') {
                // Not a typed property.
                return;
            }

            $this->checkType($phpcsFile, $properties['type_token'], $properties);

            $endOfStatement = $phpcsFile->findNext(\T_SEMICOLON, ($stackPtr + 1));
            if ($endOfStatement !== false) {
                // Don't throw the same error multiple times for multi-property declarations.
                return ($endOfStatement + 1);
            }

            return;
        }

        /*
         * This must be a function declaration. Let's check for constructor property promotion.
         */
        if (Scopes::isOOMethod($phpcsFile, $stackPtr) === false) {
            // Global function.
            return;
        }

        $functionName = FunctionDeclarations::getName($phpcsFile, $stackPtr);
        if (\strtolower($functionName) !== '__construct') {
            // Not a class constructor.
            return;
        }

        $parameters = FunctionDeclarations::getParameters($phpcsFile, $stackPtr);
        foreach ($parameters as $param) {
            if (empty($param['property_visibility']) === true) {
                // Not property promotion.
                continue;
            }

            if ($param['type_hint'] === '') {
                // Not a typed property.
                return;
            }

            // Juggle some of the array entries to what it expected for properties.
            $paramInfo = [
                'type'          => $param['type_hint'],
                'nullable_type' => $param['nullable_type'],
                'param_name'    => $param['name'],
            ];

            $this->checkType($phpcsFile, $param['type_hint_token'], $paramInfo);
        }
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0 Split off from the process() method to allow for examining constructor
     *               property promotion parameters.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $typeToken The position of the current token in the
     *                                               stack passed in $tokens.
     * @param array                       $typeInfo  Information about the current property.
     *
     * @return void
     */
    protected function checkType(File $phpcsFile, $typeToken, $typeInfo)
    {
        $origType = $typeInfo['type'];
        $type     = \ltrim($origType, '?'); // Trim off potential nullability.
        $type     = \strtolower($type);

        $errorSuffix = '';
        if (isset($typeInfo['param_name']) === true) {
            $errorSuffix = ' (promoted property ' . $typeInfo['param_name'] . ')';
        }

        if (ScannedCode::shouldRunOnOrBelow('7.3') === true) {
            $phpcsFile->addError(
                'Typed properties are not supported in PHP 7.3 or earlier. Found: %s' . $errorSuffix,
                $typeToken,
                'Found',
                [$origType]
            );
        } else {
            $types              = \preg_split('`[|&]`', $type, -1, \PREG_SPLIT_NO_EMPTY);
            $isUnionType        = (\strpos($type, '|') !== false);
            $isIntersectionType = (\strpos($type, '&') !== false);

            if (ScannedCode::shouldRunOnOrBelow('7.4') === true && $isUnionType === true) {
                $phpcsFile->addError(
                    'Union types are not present in PHP version 7.4 or earlier. Found: %s' . $errorSuffix,
                    $typeToken,
                    'UnionTypeFound',
                    [$origType]
                );
            }

            if (ScannedCode::shouldRunOnOrBelow('8.0') === true && $isIntersectionType === true) {
                $phpcsFile->addError(
                    'Intersection types are not present in PHP version 8.0 or earlier. Found: %s',
                    $typeToken,
                    'IntersectionTypeFound',
                    [$origType]
                );
            }

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
                    if (($type === 'mixed' && $typeInfo['nullable_type'] === true)
                        && ScannedCode::shouldRunOnOrAbove('8.0') === true
                    ) {
                        $phpcsFile->addError(
                            'Mixed types cannot be nullable, null is already part of the mixed type' . $errorSuffix,
                            $typeToken,
                            'NullableMixed'
                        );
                    }

                    if (isset($this->unionOnlyTypes[$type]) === true
                        && $isUnionType === false
                        && ScannedCode::shouldRunOnOrBelow('8.1') === true
                    ) {
                        $phpcsFile->addError(
                            "The '%s' type can only be used as part of a union type in PHP 8.1 or earlier",
                            $typeToken,
                            'NonUnion' . \ucfirst($type),
                            [$type]
                        );
                    }
                } elseif (isset($this->invalidTypes[$type])) {
                    $error = '%s is not supported as a type declaration for properties' . $errorSuffix;
                    $data  = [$type];

                    if ($this->invalidTypes[$type] !== false) {
                        $error .= '. Did you mean %s ?';
                        $data[] = $this->invalidTypes[$type];
                    }

                    $phpcsFile->addError($error, $typeToken, 'InvalidType', $data);
                }
            }
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
            || ScannedCode::shouldRunOnOrBelow($versionInfo['not_in_version']) === false
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
