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

use PHPCompatibility\AbstractNewFeatureSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\FunctionDeclarations;

/**
 * Detect and verify the use of return type declarations in function declarations.
 *
 * Return type declarations are available since PHP 7.0.
 * - Since PHP 7.1, the `iterable` and `void` pseudo-types are available.
 * - Since PHP 7.2, the generic `object` type is available.
 * - Since PHP 8.0, `static` is allowed to be used as a return type.
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
 *
 * @since 7.0.0
 * @since 7.1.0 Now extends the `AbstractNewFeatureSniff` instead of the base `Sniff` class.
 * @since 7.1.2 Renamed from `NewScalarReturnTypeDeclarationsSniff` to `NewReturnTypeDeclarationsSniff`.
 */
class NewReturnTypeDeclarationsSniff extends AbstractNewFeatureSniff
{

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
    protected $newTypes = array(
        'int' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'float' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'bool' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'string' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'array' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'callable' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'parent' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'self' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'Class name' => array(
            '5.6' => false,
            '7.0' => true,
        ),

        'iterable' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'void' => array(
            '7.0' => false,
            '7.1' => true,
        ),

        'object' => array(
            '7.1' => false,
            '7.2' => true,
        ),

        'static' => array(
            '7.4' => false,
            '8.0' => true,
        ),
    );


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
        return Collections::functionDeclarationTokensBC();
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
        try {
            $properties = FunctionDeclarations::getProperties($phpcsFile, $stackPtr);
        } catch (RuntimeException $e) {
            // This must have been a T_STRING which wasn't an arrow function.
            return;
        }

        if ($properties['return_type'] === '') {
            // No return type found.
            return;
        }

        $returnType      = ltrim($properties['return_type'], '?'); // Trim off potential nullability.
        $returnType      = strtolower($returnType);
        $returnTypeToken = $properties['return_type_token'];

        if (isset($this->newTypes[$returnType]) === true) {
            $itemInfo = array(
                'name' => $returnType,
            );
            $this->handleFeature($phpcsFile, $returnTypeToken, $itemInfo);

            return;
        }

        // Handle class name based return types.
        $itemInfo = array(
            'name'   => 'Class name',
        );
        $this->handleFeature($phpcsFile, $returnTypeToken, $itemInfo);
    }


    /**
     * Get the relevant sub-array for a specific item from a multi-dimensional array.
     *
     * @since 7.1.0
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
     * @since 7.1.0
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return '%s return type is not present in PHP version %s or earlier';
    }
}
