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

use PHPCompatibility\Sniff;
use PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait;
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
     * @var array(string => array(string => bool))
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
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.0
     * @since 7.1.2  Now also checks based on the function and closure keywords.
     * @since 10.0.0 Now also checks PHP 7.4+ arrow functions.
     *
     * @return array
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

        $returnType      = \ltrim($properties['return_type'], '?'); // Trim off potential nullability.
        $returnType      = \strtolower($returnType);
        $returnTypeToken = $properties['return_type_token'];
        $types           = \explode('|', $returnType);

        foreach ($types as $type) {
            if (isset($this->newTypes[$type]) === true) {
                $itemInfo = [
                    'name' => $type,
                ];
                $this->handleFeature($phpcsFile, $returnTypeToken, $itemInfo);

                /*
                 * Nullable mixed type declarations are not allowed, but could have been used prior
                 * to PHP 8 if the type hint referred to a class named "Mixed".
                 * Only throw an error if PHP 8+ needs to be supported.
                 */
                if (($type === 'mixed' && $properties['nullable_return_type'] === true)
                    && $this->supportsAbove('8.0') === true
                ) {
                    $phpcsFile->addError(
                        'Mixed types cannot be nullable, null is already part of the mixed type',
                        $returnTypeToken,
                        'NullableMixed'
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
        $this->msgTemplate = "'%s' return type is not present in PHP version %s or earlier";

        $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['name'], $versionInfo);

        $phpcsFile->addError($msgInfo['message'], $stackPtr, $msgInfo['errorcode'], $msgInfo['data']);
    }
}
