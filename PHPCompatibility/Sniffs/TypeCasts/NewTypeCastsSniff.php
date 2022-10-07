<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\TypeCasts;

use PHPCompatibility\AbstractNewFeatureSniff;
use PHP_CodeSniffer\Files\File;

/**
 * Detect use of newly introduced type casts.
 *
 * PHP version All
 *
 * @link https://www.php.net/manual/en/language.types.type-juggling.php#language.types.typecasting
 *
 * @since 8.0.1
 */
class NewTypeCastsSniff extends AbstractNewFeatureSniff
{

    /**
     * A list of new type casts, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the keyword appears.
     *
     * @since 8.0.1
     *
     * @var array(string => array(string => bool|string))
     */
    protected $newTypeCasts = [
        'T_UNSET_CAST' => [
            '4.4'         => false,
            '5.0'         => true,
            'description' => 'The unset cast',
        ],
        'T_BINARY_CAST' => [
            '5.2.0'       => false,
            '5.2.1'       => true,
            'description' => 'The binary cast',
        ],
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 8.0.1
     *
     * @return array
     */
    public function register()
    {
        $tokens = [];
        foreach ($this->newTypeCasts as $token => $versions) {
            if (\defined($token)) {
                $tokens[] = \constant($token);
            }
        }

        return $tokens;
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 8.0.1
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $tokenType = $tokens[$stackPtr]['type'];

        $itemInfo = [
            'name'    => $tokenType,
            'content' => $tokens[$stackPtr]['content'],
        ];
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
    }


    /**
     * Get the relevant sub-array for a specific item from a multi-dimensional array.
     *
     * @since 8.0.1
     *
     * @param array $itemInfo Base information about the item.
     *
     * @return array Version and other information about the item.
     */
    public function getItemArray(array $itemInfo)
    {
        return $this->newTypeCasts[$itemInfo['name']];
    }


    /**
     * Get an array of the non-PHP-version array keys used in a sub-array.
     *
     * @since 8.0.1
     *
     * @return array
     */
    protected function getNonVersionArrayKeys()
    {
        return ['description'];
    }


    /**
     * Retrieve the relevant detail (version) information for use in an error message.
     *
     * @since 8.0.1
     *
     * @param array $itemArray Version and other information about the item.
     * @param array $itemInfo  Base information about the item.
     *
     * @return array
     */
    public function getErrorInfo(array $itemArray, array $itemInfo)
    {
        $errorInfo                = parent::getErrorInfo($itemArray, $itemInfo);
        $errorInfo['description'] = $itemArray['description'];

        return $errorInfo;
    }


    /**
     * Filter the error message before it's passed to PHPCS.
     *
     * @since 8.0.1
     *
     * @param string $error     The error message which was created.
     * @param array  $itemInfo  Base information about the item this error message applies to.
     * @param array  $errorInfo Detail information about an item this error message applies to.
     *
     * @return string
     */
    protected function filterErrorMsg($error, array $itemInfo, array $errorInfo)
    {
        return $error . '. Found: %s';
    }


    /**
     * Filter the error data before it's passed to PHPCS.
     *
     * @since 8.0.1
     *
     * @param array $data      The error data array which was created.
     * @param array $itemInfo  Base information about the item this error message applies to.
     * @param array $errorInfo Detail information about an item this error message applies to.
     *
     * @return array
     */
    protected function filterErrorData(array $data, array $itemInfo, array $errorInfo)
    {
        $data[0] = $errorInfo['description'];
        $data[]  = $itemInfo['content'];
        return $data;
    }
}
