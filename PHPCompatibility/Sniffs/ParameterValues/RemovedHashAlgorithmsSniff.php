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
use PHPCompatibility\Helpers\ComplexVersionDeprecatedRemovedFeatureTrait;
use PHPCompatibility\Helpers\HashAlgorithmsTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\MessageHelper;

/**
 * Detect the use of deprecated and removed hash algorithms.
 *
 * PHP version 5.4
 *
 * @link https://www.php.net/manual/en/function.hash-algos.php#refsect1-function.hash-algos-changelog
 *
 * @since 5.5
 * @since 7.1.0  Now extends the `AbstractRemovedFeatureSniff` instead of the base `Sniff` class.
 * @since 10.0.0 Now extends the base `AbstractFunctionCallParameterSniff` class
 *               and uses the `ComplexVersionNewFeatureTrait` and the `HashAlgorithmsTrait`.
 */
class RemovedHashAlgorithmsSniff extends AbstractFunctionCallParameterSniff
{
    use ComplexVersionDeprecatedRemovedFeatureTrait;
    use HashAlgorithmsTrait;

    /**
     * A list of removed hash algorithms, which were present in older versions.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * If's sufficient to list the first version where the hash algorithm was deprecated/removed.
     *
     * @since 7.0.7
     *
     * @var array<string, array<string, bool>>
     */
    protected $removedAlgorithms = [
        'salsa10' => [
            '5.4' => true,
        ],
        'salsa20' => [
            '5.4' => true,
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
        if (isset($this->removedAlgorithms[$algo]) === false) {
            return;
        }

        $itemInfo = [
            'name' => $algo,
        ];
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
    }


    /**
     * Handle the retrieval of relevant information and - if necessary - throwing of an
     * error/warning for a matched item.
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
        $itemArray   = $this->removedAlgorithms[$itemInfo['name']];
        $versionInfo = $this->getVersionInfo($itemArray);
        $isError     = null;

        if (empty($versionInfo['removed']) === false
            && ScannedCode::shouldRunOnOrAbove($versionInfo['removed']) === true
        ) {
            $isError = true;
        } elseif (empty($versionInfo['deprecated']) === false
            && ScannedCode::shouldRunOnOrAbove($versionInfo['deprecated']) === true
        ) {
            $isError = false;

            // Reset the 'removed' info as it is not relevant for the current notice.
            $versionInfo['removed'] = '';
        }

        if (isset($isError) === false) {
            return;
        }

        $this->addMessage($phpcsFile, $stackPtr, $isError, $itemInfo, $versionInfo);
    }


    /**
     * Generates the error or warning for this item.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $stackPtr    The position of the relevant token in
     *                                                 the stack.
     * @param bool                        $isError     Whether this should be an error or a warning.
     * @param array                       $itemInfo    Base information about the item.
     * @param string[]                    $versionInfo Array with detail (version) information
     *                                                 relevant to the item.
     *
     * @return void
     */
    protected function addMessage(File $phpcsFile, $stackPtr, $isError, array $itemInfo, array $versionInfo)
    {
        // Overrule the default message template.
        $this->msgTemplate = 'The %s hash algorithm is ';

        $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['name'], $versionInfo);

        MessageHelper::addMessage(
            $phpcsFile,
            $msgInfo['message'],
            $stackPtr,
            $isError,
            $msgInfo['errorcode'],
            $msgInfo['data']
        );
    }
}
