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

use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait;
use PHPCompatibility\Helpers\HashAlgorithmsTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHP_CodeSniffer\Files\File;

/**
 * Detect the use of newly introduced hash algorithms.
 *
 * PHP version 5.2+
 *
 * @link https://www.php.net/manual/en/function.hash-algos.php#refsect1-function.hash-algos-changelog
 *
 * @since 7.0.7
 * @since 7.1.0  Now extends the `AbstractNewFeatureSniff` instead of the base `Sniff` class..
 * @since 10.0.0 Now extends the base `AbstractFunctionCallParameterSniff` class
 *               and uses the `ComplexVersionNewFeatureTrait` and the `HashAlgorithmsTrait`.
 */
class NewHashAlgorithmsSniff extends AbstractFunctionCallParameterSniff
{
    use ComplexVersionNewFeatureTrait;
    use HashAlgorithmsTrait;

    /**
     * A list of new hash algorithms, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the hash algorithm appears.
     *
     * @since 7.0.7
     *
     * @var array<string, array<string, bool>>
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

        'murmur3a' => [
            '8.0' => false,
            '8.1' => true,
        ],
        'murmur3c' => [
            '8.0' => false,
            '8.1' => true,
        ],
        'murmur3f' => [
            '8.0' => false,
            '8.1' => true,
        ],
        'xxh32' => [
            '8.0' => false,
            '8.1' => true,
        ],
        'xxh64' => [
            '8.0' => false,
            '8.1' => true,
        ],
        'xxh3' => [
            '8.0' => false,
            '8.1' => true,
        ],
        'xxh128' => [
            '8.0' => false,
            '8.1' => true,
        ],
    ];


    /**
     * Constructor.
     *
     * @since 10.0.0
     *
     * @return void
     */
    public function __construct()
    {
        $this->targetFunctions = $this->hashAlgoFunctions;
    }

    /**
     * Should the sniff bow out early for specific PHP versions ?
     *
     * @since 10.0.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return false;
    }

    /**
     * Process the parameters of a matched function.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token in the stack.
     * @param string                      $functionName The token content (function name) which was matched.
     * @param array                       $parameters   Array with information about the parameters.
     *
     * @return void
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $algo = $this->getHashAlgorithmParameter($functionName, $parameters);
        if (empty($algo) || \is_string($algo) === false) {
            return;
        }

        // Bow out if not one of the algorithms we're targetting.
        if (isset($this->newAlgorithms[$algo]) === false) {
            return;
        }

        // Check if the algorithm used is new.
        $itemInfo = [
            'name' => $algo,
        ];
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
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
        $itemArray   = $this->newAlgorithms[$itemInfo['name']];
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
        $this->msgTemplate = "The '%s' hash algorithm is not present in PHP version %s or earlier";

        $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['name'], $versionInfo);

        $phpcsFile->addError($msgInfo['message'], $stackPtr, $msgInfo['errorcode'], $msgInfo['data']);
    }
}
