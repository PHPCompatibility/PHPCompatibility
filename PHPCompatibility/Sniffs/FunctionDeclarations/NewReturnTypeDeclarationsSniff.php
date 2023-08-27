<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionDeclarations;

use PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\FunctionDeclarations;

/**
 * Detect and verify the use of return type declarations in function declarations.
 *
 * Return type declarations are available since PHP 7.0.
 * - Since PHP 7.1, the `iterable` and `void` pseudo-types are available.
 * - Since PHP 7.2, the generic `object` type is available.
 * - Since PHP 8.0, `static` is allowed to be used as a return type.
 * - Since PHP 8.0, `mixed` is allowed to be used as a return type.
 * - Since PHP 8.0, union types are supported and the union-only `false` and `null` types are available.
 * - Since PHP 8.1, the stand-alone `never` type is available.
 * - Since PHP 8.1, intersection types are supported for class/interface names.
 * - Since PHP 8.2, `false` and `null` can be used as stand-alone types.
 * - Since PHP 8.2, the `true` sub-type is available.
 *
 * PHP version 7.0+
 *
 * @link https://www.php.net/manual/en/migration70.new-features.php#migration70.new-features.return-type-declarations
 * @link https://www.php.net/manual/en/functions.returning-values.php#functions.returning-values.type-declaration
 * @link https://wiki.php.net/rfc/return_types
 * @link https://wiki.php.net/rfc/iterable
 * @link https://wiki.php.net/rfc/void_return_type
 * @link https://wiki.php.net/rfc/object-typehint
 * @link https://wiki.php.net/rfc/static_return_type
 * @link https://wiki.php.net/rfc/mixed_type_v2
 * @link https://wiki.php.net/rfc/union_types_v2
 * @link https://wiki.php.net/rfc/noreturn_type
 * @link https://wiki.php.net/rfc/pure-intersection-types
 * @link https://wiki.php.net/rfc/null-false-standalone-types
 * @link https://wiki.php.net/rfc/true-type
 *
 * @since 7.0.0
 * @since 7.1.0  Now extends the `AbstractNewFeatureSniff` instead of the base `Sniff` class.
 * @since 7.1.2  Renamed from `NewScalarReturnTypeDeclarationsSniff` to `NewReturnTypeDeclarationsSniff`.
 * @since 10.0.0 Now extends the base `Sniff` class and uses the `ComplexVersionNewFeatureTrait`.
 */
class NewReturnTypeDeclarationsSniff extends Sniff
{
    use ComplexVersionNewFeatureTrait;

    /**
     * A list of new types
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the keyword appears.
     *
     * @since 7.0.0
     *
     * @var array<string, array<string, bool>>
     */
    protected $newTypes = [
        'int' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'float' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'bool' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'string' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'array' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'callable' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'parent' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'self' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'Class name' => [
            '5.6' => false,
            '7.0' => true,
        ],

        'iterable' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'void' => [
            '7.0' => false,
            '7.1' => true,
        ],

        'object' => [
            '7.1' => false,
            '7.2' => true,
        ],

        'mixed' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'static' => [
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
        // Subtype of every other type.
        'never' => [
            '8.0' => false,
            '8.1' => true,
        ],
        'true' => [
            '8.1' => false,
            '8.2' => true,
        ],
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
     * Types which are only allowed to occur as stand-alone.
     *
     * @since 10.0.0
     *
     * @var array<string, string> Key: string type name, value: PHP version in which the type was introduced.
     */
    protected $standAloneTypes = [
        'void'  => '7.1',
        'mixed' => '8.0',
        'never' => '8.1',
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.0
     * @since 7.1.2  Now also checks based on the function and closure keywords.
     * @since 10.0.0 Now also checks PHP 7.4+ arrow functions.
     *
     * @return array<int|string>
     */
    public function register()
    {
        return Collections::functionDeclarationTokens();
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $properties = FunctionDeclarations::getProperties($phpcsFile, $stackPtr);
        if ($properties['return_type'] === '') {
            // No return type found.
            return;
        }

        $returnType         = \ltrim($properties['return_type'], '?'); // Trim off potential nullability.
        $returnType         = \strtolower($returnType);
        $returnTypeToken    = $properties['return_type_token'];
        $types              = \preg_split('`[|&]`', $returnType, -1, \PREG_SPLIT_NO_EMPTY);
        $isUnionType        = (\strpos($returnType, '|') !== false);
        $isIntersectionType = (\strpos($returnType, '&') !== false);

        if (ScannedCode::shouldRunOnOrBelow('7.4') === true && $isUnionType === true) {
            $phpcsFile->addError(
                'Union types are not present in PHP version 7.4 or earlier. Found: %s',
                $returnTypeToken,
                'UnionTypeFound',
                [$properties['return_type']]
            );
        }

        if (ScannedCode::shouldRunOnOrBelow('8.0') === true && $isIntersectionType === true) {
            $phpcsFile->addError(
                'Intersection types are not present in PHP version 8.0 or earlier. Found: %s',
                $returnTypeToken,
                'IntersectionTypeFound',
                [$properties['return_type']]
            );
        }

        foreach ($types as $type) {
            if (isset($this->newTypes[$type]) === true) {
                $itemInfo = [
                    'name' => $type,
                ];
                $this->handleFeature($phpcsFile, $returnTypeToken, $itemInfo);

                /*
                 * Stand-alone types are not allowed to be nullable, nor can they be included in a type union.
                 * As the names for these types _could_ have referred to a class prior to their introduction
                 * as a type, we should check for this, but only when the PHP version in which the type
                 * was introduced needs to be supported.
                 */
                if (isset($this->standAloneTypes[$type]) === true
                    && ($isUnionType === true
                    || $properties['nullable_return_type'] === true)
                    && ScannedCode::shouldRunOnOrAbove($this->standAloneTypes[$type]) === true
                ) {
                    $phpcsFile->addError(
                        "The '%s' type can only be used as a standalone type",
                        $returnTypeToken,
                        'NonStandalone' . \ucfirst($type),
                        [$type]
                    );
                }

                if (isset($this->unionOnlyTypes[$type]) === true
                    && $isUnionType === false
                    && ScannedCode::shouldRunOnOrBelow('8.1') === true
                ) {
                    $phpcsFile->addError(
                        "The '%s' type can only be used as part of a union type in PHP 8.1 or earlier",
                        $returnTypeToken,
                        'NonUnion' . \ucfirst($type),
                        [$type]
                    );
                }

                continue;
            }

            // Handle class name based return types.
            $itemInfo = [
                'name' => 'Class name',
            ];
            $this->handleFeature($phpcsFile, $returnTypeToken, $itemInfo);
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
        $this->msgTemplate = "'%s' return type is not present in PHP version %s or earlier";

        $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['name'], $versionInfo);

        $phpcsFile->addError($msgInfo['message'], $stackPtr, $msgInfo['errorcode'], $msgInfo['data']);
    }
}
