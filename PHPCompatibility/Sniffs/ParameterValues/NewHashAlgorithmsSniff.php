<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\ParameterValues;

use PHPCompatibility\AbstractNewFeatureSniff;
use PHP_CodeSniffer_File as File;

/**
 * Detect the use of newly introduced hash algorithms.
 *
 * PHP version 5.2+
 *
 * @link https://www.php.net/manual/en/function.hash-algos.php#refsect1-function.hash-algos-changelog
 *
 * @since 7.0.7
 * @since 7.1.0 Now extends the `AbstractNewFeatureSniff` instead of the base `Sniff` class..
 */
class NewHashAlgorithmsSniff extends AbstractNewFeatureSniff
{
    /**
     * A list of new hash algorithms, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the hash algorithm appears.
     *
     * @since 7.0.7
     *
     * @var array(string => array(string => bool))
     */
    protected $newAlgorithms = [
        'md2' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'ripemd256' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'ripemd320' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'salsa10' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'salsa20' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'snefru256' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'sha224' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'joaat' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'fnv132' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'fnv164' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'gost-crypto' => [
            '5.5' => false,
            '5.6' => true,
        ],

        'sha512/224' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'sha512/256' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'sha3-224' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'sha3-256' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'sha3-384' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'sha3-512' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'crc32c' => [
            '7.3' => false,
            '7.4' => true,
        ],
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.7
     *
     * @return array
     */
    public function register()
    {
        return [\T_STRING];
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.0.7
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $algo = $this->getHashAlgorithmParameter($phpcsFile, $stackPtr);
        if (empty($algo) || \is_string($algo) === false) {
            return;
        }

        // Bow out if not one of the algorithms we're targetting.
        if (isset($this->newAlgorithms[$algo]) === false) {
            return;
        }

        // Check if the algorithm used is new.
        $itemInfo = [
            'name'   => $algo,
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
        return $this->newAlgorithms[$itemInfo['name']];
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
        return 'The %s hash algorithm is not present in PHP version %s or earlier';
    }
}
