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
use PHPCompatibility\Helpers\ComplexVersionDeprecatedRemovedFeatureTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Detect use of deprecated/removed function parameters in calls to native PHP functions.
 *
 * PHP version All
 *
 * @link https://www.php.net/manual/en/doc.changelog.php
 *
 * @since 7.0.0
 * @since 7.1.0  Now extends the `AbstractRemovedFeatureSniff` instead of the base `Sniff` class.
 * @since 10.0.0 Now extends the base `AbstractFunctionCallParameterSniff` class
 *               and uses the `ComplexVersionNewFeatureTrait`.
 */
class RemovedFunctionParametersSniff extends AbstractFunctionCallParameterSniff
{
    use ComplexVersionDeprecatedRemovedFeatureTrait;

    /**
     * A list of removed function parameters, which were present in older versions.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * The index is the 1-based parameter position of the parameter in the parameter list.
     * If's sufficient to list the first version where the function parameter was deprecated/removed.
     *
     * The optional `callback` key can be used to pass a method name which should be called for an
     * additional check. The method will be passed the parameter info and should return true
     * if the notice should be thrown or false otherwise.
     *
     * @since 7.0.0
     * @since 7.0.2  Visibility changed from `public` to `protected`.
     * @since 9.3.0  Optional `callback` key.
     * @since 10.0.0 - The parameter offsets were changed from 0-based to 1-based.
     *               - The property was renamed from `$removedFunctionParameters` to `$targetFunctions`.
     *
     * @var array<string, array<int, array<string, bool|string>>>
     */
    protected $targetFunctions = [
        'curl_version' => [
            1 => [
                'name' => 'age',
                '7.4'  => false,
                '8.0'  => true,
            ],
        ],
        'define' => [
            3 => [
                'name' => 'case_insensitive',
                '7.3'  => false,
                '8.0'  => true,
            ],
        ],
        'gmmktime' => [
            7 => [
                'name' => 'isDST',
                '5.1'  => false,
                '7.0'  => true,
            ],
        ],
        /*
         * For the below three functions, it's actually the 3rd parameter which has been deprecated.
         * However with positional arguments, this can only be detected by checking for the "old last" argument.
         * Note: this function explicitly does NOT support named parameters for the function
         * signature without this parameter, but that's not the concern of this sniff.
         */
        'imagefilledpolygon' => [
            4 => [
                'name' => 'num_points',
                '8.1'  => false,
            ],
        ],
        'imageopenpolygon' => [
            4 => [
                'name' => 'num_points',
                '8.1'  => false,
            ],
        ],
        'imagepolygon' => [
            4 => [
                'name' => 'num_points',
                '8.1'  => false,
            ],
        ],
        'imagerotate' => [
            4 => [
                'name' => 'ignore_transparent',
                '8.3'  => true,
            ],
        ],
        'imap_headerinfo' => [
            5 => [
                'name' => 'defaulthost',
                '8.0'  => true,
            ],
        ],
        'ldap_first_attribute' => [
            3 => [
                'name'  => 'ber_identifier',
                '5.2.4' => true,
            ],
        ],
        'ldap_next_attribute' => [
            3 => [
                'name'  => 'ber_identifier',
                '5.2.4' => true,
            ],
        ],
        'mb_decode_numericentity' => [
            4 => [
                'name' => 'is_hex',
                '8.0'  => true,
            ],
        ],
        'mktime' => [
            7 => [
                'name' => 'isDST',
                '5.1'  => false,
                '7.0'  => true,
            ],
        ],
        'mysqli_get_client_info' => [
            1 => [
                'name' => 'mysql',
                '8.1'  => false,
            ],
        ],
        'odbc_do' => [
            3 => [
                'name' => 'flags',
                '8.0'  => true,
            ],
        ],
        'odbc_exec' => [
            3 => [
                'name' => 'flags',
                '8.0'  => true,
            ],
        ],
        'pg_connect' => [
            // These were already deprecated before, but version in which deprecation took place is unclear.
            3 => [
                'name' => 'options',
                '8.0'  => true,
            ],
            4 => [
                'name' => 'tty',
                '8.0'  => true,
            ],
            5 => [
                'name' => 'dbname',
                '8.0'  => true,
            ],
        ],
    ];

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
        $functionLc = \strtolower($functionName);

        foreach ($this->targetFunctions[$functionLc] as $offset => $parameterDetails) {
            $targetParam = PassedParameters::getParameterFromStack($parameters, $offset, $parameterDetails['name']);

            if ($targetParam !== false && $targetParam['clean'] !== '') {
                if (isset($parameterDetails['callback']) && \method_exists($this, $parameterDetails['callback'])) {
                    if ($this->{$parameterDetails['callback']}($phpcsFile, $targetParam) === false) {
                        continue;
                    }
                }

                $firstNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, $targetParam['start'], ($targetParam['end'] + 1), true);

                $itemInfo = [
                    'name'   => $functionName,
                    'nameLc' => $functionLc,
                    'offset' => $offset,
                ];
                $this->handleFeature($phpcsFile, $firstNonEmpty, $itemInfo);
            }
        }
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
        $itemArray   = $this->targetFunctions[$itemInfo['nameLc']][$itemInfo['offset']];
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

        $this->addMessage($phpcsFile, $stackPtr, $isError, $itemInfo, $itemArray, $versionInfo);
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
     * @param array                       $itemArray   The sub-array with all the details about
     *                                                 this item.
     * @param string[]                    $versionInfo Array with detail (version) information
     *                                                 relevant to the item.
     *
     * @return void
     */
    protected function addMessage(File $phpcsFile, $stackPtr, $isError, array $itemInfo, array $itemArray, array $versionInfo)
    {
        // Overrule the default message template.
        $this->msgTemplate = 'The "%s" parameter for function %s() is ';

        $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['nameLc'] . '_' . $itemArray['name'], $versionInfo);

        $data = $msgInfo['data'];
        \array_unshift($data, $itemArray['name']);

        MessageHelper::addMessage($phpcsFile, $msgInfo['message'], $stackPtr, $isError, $msgInfo['errorcode'], $data);
    }
}
