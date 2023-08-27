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
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Detect missing required function parameters in calls to native PHP functions.
 *
 * Specifically when those function parameters are no longer required in more recent PHP versions.
 *
 * PHP version All
 *
 * @link https://www.php.net/manual/en/doc.changelog.php
 *
 * @since 7.0.3
 * @since 7.1.0  Now extends the `AbstractComplexVersionSniff` instead of the base `Sniff` class.
 * @since 9.0.0  Renamed from `RequiredOptionalFunctionParametersSniff` to `RequiredToOptionalFunctionParametersSniff`.
 * @since 10.0.0 Now extends the base `AbstractFunctionCallParameterSniff` class.
 *               Methods which were previously required due to the extending of the `AbstractComplexVersionSniff`
 *               have been removed.
 */
class RequiredToOptionalFunctionParametersSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * A list of function parameters, which were required in older versions and became optional later on.
     *
     * The array lists : version number with true (required) and false (optional).
     *
     * The index is the 1-based parameter position of the parameter in the parameter list.
     * If's sufficient to list the last version in which the parameter was still required.
     *
     * @since 7.0.3
     * @since 10.0.0 - Parameter renamed from `$functionParameters` to `$targetFunctions` for
     *                 compatibility with the `AbstractFunctionCallParameterSniff` class.
     *               - The parameter offsets were changed from 0-based to 1-based.
     *
     * @var array<string, array<int, array<string, bool|string>>>
     */
    protected $targetFunctions = [
        'array_diff_assoc' => [
            2 => [
                'name' => 'arrays',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'array_diff_key' => [
            2 => [
                'name' => 'arrays',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'array_diff_uassoc' => [
            /*
             * $rest is actually at position 2, but has another required parameter after it,
             * so we need to detect on the last parameter.
             */
            3 => [
                'name' => 'rest',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'array_diff_ukey' => [
            // Note from array_diff_uassoc applies here too.
            3 => [
                'name' => 'rest',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'array_diff' => [
            2 => [
                'name' => 'arrays',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'array_intersect_assoc' => [
            2 => [
                'name' => 'arrays',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'array_intersect_key' => [
            2 => [
                'name' => 'arrays',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'array_intersect_uassoc' => [
            // Note from array_diff_uassoc applies here too.
            3 => [
                'name' => 'rest',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'array_intersect_ukey' => [
            // Note from array_diff_uassoc applies here too.
            3 => [
                'name' => 'rest',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'array_intersect' => [
            2 => [
                'name' => 'arrays',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'array_merge' => [
            1 => [
                'name' => 'arrays',
                '7.3'  => true,
                '7.4'  => false,
            ],
        ],
        'array_merge_recursive' => [
            1 => [
                'name' => 'arrays',
                '7.3'  => true,
                '7.4'  => false,
            ],
        ],
        'array_push' => [
            2 => [
                'name' => 'values',
                '7.2'  => true,
                '7.3'  => false,
            ],
        ],
        'array_udiff_assoc' => [
            // Note from array_diff_uassoc applies here too.
            3 => [
                'name' => 'rest',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'array_udiff_uassoc' => [
            // Note from array_diff_uassoc applies here too.
            4 => [
                'name' => 'rest',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'array_udiff' => [
            // Note from array_diff_uassoc applies here too.
            3 => [
                'name' => 'rest',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'array_uintersect_assoc' => [
            // Note from array_diff_uassoc applies here too.
            3 => [
                'name' => 'rest',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'array_uintersect_uassoc' => [
            // Note from array_diff_uassoc applies here too.
            4 => [
                'name' => 'rest',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'array_uintersect' => [
            // Note from array_diff_uassoc applies here too.
            3 => [
                'name' => 'rest',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'array_unshift' => [
            2 => [
                'name' => 'values',
                '7.2'  => true,
                '7.3'  => false,
            ],
        ],
        'bcscale' => [
            1 => [
                'name' => 'scale',
                '7.2'  => true,
                '7.3'  => false,
            ],
        ],
        'fgetcsv' => [
            2 => [
                'name' => 'length',
                '5.0'  => true,
                '5.1'  => false,
            ],
        ],
        'ftp_fget' => [
            4 => [
                'name' => 'mode',
                '7.2'  => true,
                '7.3'  => false,
            ],
        ],
        'ftp_fput' => [
            4 => [
                'name' => 'mode',
                '7.2'  => true,
                '7.3'  => false,
            ],
        ],
        'ftp_get' => [
            4 => [
                'name' => 'mode',
                '7.2'  => true,
                '7.3'  => false,
            ],
        ],
        'ftp_nb_fget' => [
            4 => [
                'name' => 'mode',
                '7.2'  => true,
                '7.3'  => false,
            ],
        ],
        'ftp_nb_fput' => [
            4 => [
                'name' => 'mode',
                '7.2'  => true,
                '7.3'  => false,
            ],
        ],
        'ftp_nb_get' => [
            4 => [
                'name' => 'mode',
                '7.2'  => true,
                '7.3'  => false,
            ],
        ],
        'ftp_nb_put' => [
            4 => [
                'name' => 'mode',
                '7.2'  => true,
                '7.3'  => false,
            ],
        ],
        'ftp_put' => [
            4 => [
                'name' => 'mode',
                '7.2'  => true,
                '7.3'  => false,
            ],
        ],
        'getenv' => [
            1 => [
                'name' => 'name',
                '7.0'  => true,
                '7.1'  => false,
            ],
        ],
        'imagepolygon' => [
            /*
             * $points is actually at position 3, but has another required parameter after it,
             * so we need to detect on the last parameter.
             * Note: this function explicitly does NOT support named parameters for the function
             * signature without this parameter, but that's not the concern of this sniff.
             */
            4 => [
                'name' => 'num_points',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'imageopenpolygon' => [
            // Note from imagepolygon applies here too.
            4 => [
                'name' => 'num_points',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'imagefilledpolygon' => [
            // Note from imagepolygon applies here too.
            4 => [
                'name' => 'num_points',
                '7.4'  => true,
                '8.0'  => false,
            ],
        ],
        'preg_match_all' => [
            3 => [
                'name' => 'matches',
                '5.3'  => true,
                '5.4'  => false,
            ],
        ],
        'stream_socket_enable_crypto' => [
            3 => [
                'name' => 'crypto_method',
                '5.5'  => true,
                '5.6'  => false,
            ],
        ],
        'xmlwriter_write_element' => [
            3 => [
                'name'  => 'content',
                '5.2.2' => true,
                '5.2.3' => false,
            ],
        ],
        'xmlwriter_write_element_ns' => [
            5 => [
                'name'  => 'content',
                '5.2.2' => true,
                '5.2.3' => false,
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
     *               `process()` method (now removed).
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
     * @return void
     */
    public function processNoParameters(File $phpcsFile, $stackPtr, $functionName)
    {
        $this->processParameters($phpcsFile, $stackPtr, $functionName, []);
    }

    /**
     * Retrieve the relevant detail (version) information for use in an error message.
     *
     * @since 7.1.0
     * @since 10.0.0 - Method renamed from `getErrorInfo()` to `getVersionInfo().
     *               - Second function parameter `$itemInfo` removed.
     *               - Method visibility changed from `public` to `protected`.
     *
     * @param array $itemArray Version and other information about the item.
     *
     * @return array<string, string>
     */
    protected function getVersionInfo(array $itemArray)
    {
        $versionInfo = [
            'requiredVersion' => '',
        ];

        foreach ($itemArray as $version => $required) {
            if (\preg_match('`^\d\.\d(\.\d{1,2})?$`', $version) !== 1) {
                // Not a version key.
                continue;
            }

            if ($required === true && ScannedCode::shouldRunOnOrBelow($version) === true) {
                $versionInfo['requiredVersion'] = $version;
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

        if (empty($versionInfo['requiredVersion'])) {
            return;
        }

        $this->addError($phpcsFile, $stackPtr, $itemInfo, $itemArray, $versionInfo);
    }

    /**
     * Generates the error for this item.
     *
     * @since 7.1.0
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
        $error     = 'The "%s" parameter for function %s() is missing, but was required for PHP version %s and lower';
        $errorCode = MessageHelper::stringToErrorCode($itemInfo['name'] . '_' . $itemArray['name'], true) . 'Missing';
        $data      = [
            $itemArray['name'],
            $itemInfo['name'],
            $versionInfo['requiredVersion'],
        ];

        $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
    }
}
