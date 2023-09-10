<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionUse;

use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\MessageHelper;

/**
 * Detect missing required function parameters in calls to native PHP functions.
 *
 * Specifically when those function parameters used to be optional in older PHP versions.
 *
 * PHP version All
 *
 * @link https://www.php.net/manual/en/doc.changelog.php
 *
 * @since 8.1.0
 * @since 9.0.0  Renamed from `OptionalRequiredFunctionParametersSniff` to `OptionalToRequiredFunctionParametersSniff`.
 * @since 10.0.0 Now extends the base `AbstractFunctionCallParameterSniff` class.
 *               Previously the sniff extended the sister-sniff `RequiredToOptionalFunctionParametersSniff`.
 *               Methods which were previously required due to the extending of the (grand-parent)
 *               `AbstractComplexVersionSniff` have been removed.
 */
class OptionalToRequiredFunctionParametersSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * A list of function parameters, which were optional in older versions and became required later on.
     *
     * The array lists : version number with true (required) and false (optional use deprecated).
     *
     * The index is the 1-based parameter position of the parameter in the parameter list.
     * If's sufficient to list the last version in which the parameter was not yet required.
     *
     * @since 8.1.0
     * @since 10.0.0 - Parameter renamed from `$functionParameters` to `$targetFunctions` for
     *                 compatibility with the `AbstractFunctionCallParameterSniff` class.
     *               - The parameter offsets were changed from 0-based to 1-based.
     *
     * @var array<string, array<int, array<string, bool|string>>>
     */
    protected $targetFunctions = [
        'crypt' => [
            2 => [
                'name' => 'salt',
                '5.6'  => false,
                '8.0'  => true,
            ],
        ],
        'gmmktime' => [
            1 => [
                'name' => 'hour',
                '8.0'  => true,
            ],
        ],
        'mb_parse_str' => [
            2 => [
                'name' => 'result',
                '8.0'  => true,
            ],
        ],
        'mktime' => [
            1 => [
                'name' => 'hour',
                '5.1'  => false,
                '8.0'  => true,
            ],
        ],
        'openssl_seal' => [
            5 => [
                'name' => 'cipher_algo',
                '8.0'  => true,
            ],
        ],
        'openssl_open' => [
            5 => [
                'name' => 'cipher_algo',
                '8.0'  => true,
            ],
        ],
        'parse_str' => [
            2 => [
                'name' => 'result',
                '7.2'  => false,
                '8.0'  => true,
            ],
        ],
    ];


    /**
     * Bowing out early is not applicable to this sniff.
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
     * @since 10.0.0 Part of the logic in this method was previously contained in the
     *               parent sniff `process()` method (now removed).
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token in the stack.
     * @param string                      $functionName The token content (function name) which was matched.
     * @param array                       $parameters   Array with information about the parameters.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $functionLc = \strtolower($functionName);

        foreach ($this->targetFunctions[$functionLc] as $offset => $parameterDetails) {
            $targetParam = PassedParameters::getParameterFromStack($parameters, $offset, $parameterDetails['name']);

            if ($targetParam === false) {
                $itemInfo = [
                    'name'   => $functionName,
                    'nameLc' => $functionLc,
                    'offset' => $offset,
                ];
                $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
            }
        }
    }

    /**
     * Process the function if no parameters were found.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token in the stack.
     * @param string                      $functionName The token content (function name) which was matched.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processNoParameters(File $phpcsFile, $stackPtr, $functionName)
    {
        $this->processParameters($phpcsFile, $stackPtr, $functionName, []);
    }

    /**
     * Retrieve the relevant detail (version) information for use in an error message.
     *
     * @since 8.1.0
     * @since 10.0.0 - Method renamed from `getErrorInfo()` to `getVersionInfo().
     *               - Second function parameter `$itemInfo` removed.
     *               - Method visibility changed from `public` to `protected`.
     *
     * @param array $itemArray Version and other information about the item.
     *
     * @return array
     */
    protected function getVersionInfo(array $itemArray)
    {
        $versionInfo = [
            'optionalDeprecated' => '',
            'optionalRemoved'    => '',
            'error'              => false,
        ];

        foreach ($itemArray as $version => $required) {
            if (\preg_match('`^\d\.\d(\.\d{1,2})?$`', $version) !== 1) {
                // Not a version key.
                continue;
            }

            if (ScannedCode::shouldRunOnOrAbove($version) === true) {
                if ($required === true && $versionInfo['optionalRemoved'] === '') {
                    $versionInfo['optionalRemoved'] = $version;
                    $versionInfo['error']           = true;
                } elseif ($versionInfo['optionalDeprecated'] === '') {
                    $versionInfo['optionalDeprecated'] = $version;
                }
            }
        }

        return $versionInfo;
    }

    /**
     * Handle the retrieval of relevant information and - if necessary - throwing of an
     * error for a matched item.
     *
     * @since 10.0.0 This was previously handled via a similar method in the `AbstractComplexVersionSniff`.
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
        $itemArray   = $this->targetFunctions[$itemInfo['nameLc']][$itemInfo['offset']];
        $versionInfo = $this->getVersionInfo($itemArray);

        if (empty($versionInfo['optionalDeprecated']) && empty($versionInfo['optionalRemoved'])) {
            return;
        }

        $this->addError($phpcsFile, $stackPtr, $itemInfo, $itemArray, $versionInfo);
    }

    /**
     * Generates the error or warning for this item.
     *
     * @since 8.1.0
     * @since 10.0.0 - Method visibility changed from `public` to `protected`.
     *               - Introduced $itemArray parameter.
     *               - Renamed the last parameter from `$errorInfo` to `$versionInfo`.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $stackPtr    The position of the relevant token in
     *                                                 the stack.
     * @param array                       $itemInfo    Base information about the item.
     * @param array                       $itemArray   The sub-array with all the details about
     *                                                 this item.
     * @param array                       $versionInfo Array with detail (version) information
     *                                                 relevant to the item.
     *
     * @return void
     */
    protected function addError(File $phpcsFile, $stackPtr, array $itemInfo, array $itemArray, array $versionInfo)
    {
        $error      = 'The "%s" parameter for function %s() is missing. Passing this parameter is no longer optional. The optional nature of the parameter is ';
        $errorCode  = MessageHelper::stringToErrorCode($itemInfo['name'] . '_' . $itemArray['name'], true);
        $codeSuffix = '';
        $data       = [
            $itemArray['name'],
            $itemInfo['name'],
        ];

        if ($versionInfo['optionalDeprecated'] !== '') {
            $error     .= 'deprecated since PHP %s and ';
            $codeSuffix = 'Soft';
            $data[]     = $versionInfo['optionalDeprecated'];
        }

        if ($versionInfo['optionalRemoved'] !== '') {
            $error     .= 'removed since PHP %s and ';
            $codeSuffix = 'Hard';
            $data[]     = $versionInfo['optionalRemoved'];
        }

        // Remove the last 'and' from the message.
        $error      = \substr($error, 0, (\strlen($error) - 5));
        $errorCode .= $codeSuffix . 'Required';

        MessageHelper::addMessage($phpcsFile, $error, $stackPtr, $versionInfo['error'], $errorCode, $data);
    }
}
