<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Operators;

use PHPCompatibility\AbstractNewFeatureSniff;
use PHP_CodeSniffer\Files\File;

/**
 * Detect use of new PHP operators.
 *
 * PHP version All
 *
 * @link https://wiki.php.net/rfc/pow-operator
 * @link https://wiki.php.net/rfc/combined-comparison-operator
 * @link https://wiki.php.net/rfc/isset_ternary
 * @link https://wiki.php.net/rfc/null_coalesce_equal_operator
 * @link https://wiki.php.net/rfc/nullsafe_operator
 *
 * @since 9.0.0 Detection of new operators was originally included in the
 *              `NewLanguageConstruct` sniff (since 5.6).
 */
class NewOperatorsSniff extends AbstractNewFeatureSniff
{

    /**
     * A list of new operators, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the operator appears.
     *
     * @since 5.6
     *
     * @var array(string => array(string => bool|string))
     */
    protected $newOperators = [
        'T_POW' => [
            '5.5'         => false,
            '5.6'         => true,
            'description' => 'The power operator (**)',
        ],
        'T_POW_EQUAL' => [
            '5.5'         => false,
            '5.6'         => true,
            'description' => 'The power assignment operator (**=)',
        ],
        'T_SPACESHIP' => [
            '5.6'         => false,
            '7.0'         => true,
            'description' => 'The spaceship operator (<=>)',
        ],
        'T_COALESCE' => [
            '5.6'         => false,
            '7.0'         => true,
            'description' => 'The null coalescing operator (??)',
        ],
        'T_COALESCE_EQUAL' => [
            '7.3'         => false,
            '7.4'         => true,
            'description' => 'The null coalesce equal operator (??=)',
        ],
        'T_NULLSAFE_OBJECT_OPERATOR' => [
            '7.4'         => false,
            '8.0'         => true,
            'description' => 'The nullsafe object operator (?->)',
        ],
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 5.6
     *
     * @return array
     */
    public function register()
    {
        $tokens = [];
        foreach ($this->newOperators as $token => $versions) {
            $tokens[] = \constant($token);
        }
        return $tokens;
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 5.6
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
            'name' => $tokenType,
        ];
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
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
        return $this->newOperators[$itemInfo['name']];
    }


    /**
     * Get an array of the non-PHP-version array keys used in a sub-array.
     *
     * @since 7.1.0
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
     * @since 7.1.0
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
     * Filter the error data before it's passed to PHPCS.
     *
     * @since 7.1.0
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
        return $data;
    }
}
