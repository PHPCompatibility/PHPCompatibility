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

use PHPCompatibility\Sniff;
use PHPCompatibility\Helpers\ComplexVersionDeprecatedRemovedFeatureTrait;
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
 * @since 10.0.0 Now extends the base `Sniff` class and uses the `ComplexVersionDeprecatedRemovedFeatureTrait`.
 */
class RemovedFunctionParametersSniff extends Sniff
{
    use ComplexVersionDeprecatedRemovedFeatureTrait;

    /**
     * A list of removed function parameters, which were present in older versions.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * The index is the location of the parameter in the parameter list, starting at 0 !
     * If's sufficient to list the first version where the function parameter was deprecated/removed.
     *
     * The optional `callback` key can be used to pass a method name which should be called for an
     * additional check. The method will be passed the parameter info and should return true
     * if the notice should be thrown or false otherwise.
     *
     * @since 7.0.0
     * @since 7.0.2 Visibility changed from `public` to `protected`.
     * @since 9.3.0 Optional `callback` key.
     *
     * @var array
     */
    protected $removedFunctionParameters = [
        'curl_version' => [
            0 => [
                'name' => 'age',
                '7.4'  => false,
                '8.0'  => true,
            ],
        ],
        'define' => [
            2 => [
                'name' => 'case_insensitive',
                '7.3'  => false,
                '8.0'  => true,
            ],
        ],
        'gmmktime' => [
            6 => [
                'name' => 'is_dst',
                '5.1'  => false,
                '7.0'  => true,
            ],
        ],
        'imap_headerinfo' => [
            4 => [
                'name' => 'defaulthost',
                '8.0'  => true,
            ],
        ],
        /*
         * For the below three functions, it's actually the 3rd (pos: 2) parameter which has been deprecated.
         * However with positional arguments, this can only be detected by checking for the "old last" argument.
         */
        'imagepolygon' => [
            3 => [
                'name' => 'num_points',
                '8.1'  => false,
            ],
        ],
        'imageopenpolygon' => [
            3 => [
                'name' => 'num_points',
                '8.1'  => false,
            ],
        ],
        'imagefilledpolygon' => [
            3 => [
                'name' => 'num_points',
                '8.1'  => false,
            ],
        ],
        'ldap_first_attribute' => [
            2 => [
                'name'  => 'ber_identifier',
                '5.2.4' => true,
            ],
        ],
        'ldap_next_attribute' => [
            2 => [
                'name'  => 'ber_identifier',
                '5.2.4' => true,
            ],
        ],
        'mb_decode_numericentity' => [
            3 => [
                'name' => 'is_hex',
                '8.0'  => true,
            ],
        ],
        'mktime' => [
            6 => [
                'name' => 'is_dst',
                '5.1'  => false,
                '7.0'  => true,
            ],
        ],
        'mysqli_get_client_info' => [
            0 => [
                'name' => 'mysql',
                '8.1'  => false,
            ],
        ],
        'odbc_do' => [
            2 => [
                'name' => 'flags',
                '8.0'  => true,
            ],
        ],
        'odbc_exec' => [
            2 => [
                'name' => 'flags',
                '8.0'  => true,
            ],
        ],
        'pg_connect' => [
            // These were already deprecated before, but version in which deprecation took place is unclear.
            2 => [
                'name' => 'options',
                '8.0'  => true,
            ],
            3 => [
                'name' => 'tty',
                '8.0'  => true,
            ],
            4 => [
                'name' => 'dbname',
                '8.0'  => true,
            ],
        ],
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.0
     *
     * @return array
     */
    public function register()
    {
        // Handle case-insensitivity of function names.
        $this->removedFunctionParameters = \array_change_key_case($this->removedFunctionParameters, \CASE_LOWER);

        return [\T_STRING];
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
        $tokens = $phpcsFile->getTokens();

        $function   = $tokens[$stackPtr]['content'];
        $functionLc = \strtolower($function);

        if (isset($this->removedFunctionParameters[$functionLc]) === false) {
            return;
        }

        $nextToken = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($nextToken === false
            || $tokens[$nextToken]['code'] !== \T_OPEN_PARENTHESIS
            || isset($tokens[$nextToken]['parenthesis_owner']) === true
        ) {
            return;
        }

        $ignore = [
            \T_DOUBLE_COLON    => true,
            \T_OBJECT_OPERATOR => true,
            \T_NEW             => true,
        ];

        $prevToken = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if (isset($ignore[$tokens[$prevToken]['code']]) === true) {
            // Not a call to a PHP function.
            return;

        } elseif ($tokens[$prevToken]['code'] === \T_NS_SEPARATOR) {
            $prevPrevToken = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($prevToken - 1), null, true);
            if ($tokens[$prevPrevToken]['code'] === \T_STRING
                || $tokens[$prevPrevToken]['code'] === \T_NAMESPACE
            ) {
                // Namespaced function.
                return;
            }
        }

        $parameters     = PassedParameters::getParameters($phpcsFile, $stackPtr);
        $parameterCount = \count($parameters);
        if ($parameterCount === 0) {
            return;
        }

        // If the parameter count returned > 0, we know there will be valid open parenthesis.
        $openParenthesis      = $phpcsFile->findNext(Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        $parameterOffsetFound = $parameterCount - 1;

        foreach ($this->removedFunctionParameters[$functionLc] as $offset => $parameterDetails) {
            if ($offset <= $parameterOffsetFound) {
                if (isset($parameterDetails['callback']) && \method_exists($this, $parameterDetails['callback'])) {
                    if ($this->{$parameterDetails['callback']}($phpcsFile, $parameters[($offset + 1)]) === false) {
                        continue;
                    }
                }

                $itemInfo = [
                    'name'   => $function,
                    'nameLc' => $functionLc,
                    'offset' => $offset,
                ];
                $this->handleFeature($phpcsFile, $openParenthesis, $itemInfo);
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
        $itemArray   = $this->removedFunctionParameters[$itemInfo['nameLc']][$itemInfo['offset']];
        $versionInfo = $this->getVersionInfo($itemArray);
        $isError     = null;

        if (empty($versionInfo['removed']) === false
            && $this->supportsAbove($versionInfo['removed']) === true
        ) {
            $isError = true;
        } elseif (empty($versionInfo['deprecated']) === false
            && $this->supportsAbove($versionInfo['deprecated']) === true
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
