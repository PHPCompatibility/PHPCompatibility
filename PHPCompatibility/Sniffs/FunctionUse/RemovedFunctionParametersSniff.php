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

use PHPCompatibility\AbstractRemovedFeatureSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer_Tokens as Tokens;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Detect use of deprecated/removed function parameters in calls to native PHP functions.
 *
 * PHP version All
 *
 * @link https://www.php.net/manual/en/doc.changelog.php
 *
 * @since 7.0.0
 * @since 7.1.0 Now extends the `AbstractRemovedFeatureSniff` instead of the base `Sniff` class.
 */
class RemovedFunctionParametersSniff extends AbstractRemovedFeatureSniff
{
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
    protected $removedFunctionParameters = array(
        'curl_version' => array(
            0 => array(
                'name' => 'age',
                '7.4'  => false,
                '8.0'  => true,
            ),
        ),
        'define' => array(
            2 => array(
                'name' => 'case_insensitive',
                '7.3'  => false,
                '8.0'  => true,
            ),
        ),
        'gmmktime' => array(
            6 => array(
                'name' => 'is_dst',
                '5.1'  => false,
                '7.0'  => true,
            ),
        ),
        'ldap_first_attribute' => array(
            2 => array(
                'name'  => 'ber_identifier',
                '5.2.4' => true,
            ),
        ),
        'ldap_next_attribute' => array(
            2 => array(
                'name'  => 'ber_identifier',
                '5.2.4' => true,
            ),
        ),
        'mb_decode_numericentity' => array(
            3 => array(
                'name' => 'is_hex',
                '8.0'  => true,
            ),
        ),
        'mktime' => array(
            6 => array(
                'name' => 'is_dst',
                '5.1'  => false,
                '7.0'  => true,
            ),
        ),
    );


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

        return array(\T_STRING);
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
        $functionLc = strtolower($function);

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

        $ignore = array(
            \T_DOUBLE_COLON    => true,
            \T_OBJECT_OPERATOR => true,
            \T_NEW             => true,
        );

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
                if (isset($parameterDetails['callback']) && method_exists($this, $parameterDetails['callback'])) {
                    if ($this->{$parameterDetails['callback']}($phpcsFile, $parameters[($offset + 1)]) === false) {
                        continue;
                    }
                }

                $itemInfo = array(
                    'name'   => $function,
                    'nameLc' => $functionLc,
                    'offset' => $offset,
                );
                $this->handleFeature($phpcsFile, $openParenthesis, $itemInfo);
            }
        }
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
        return $this->removedFunctionParameters[$itemInfo['nameLc']][$itemInfo['offset']];
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
        return array('name', 'callback');
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
        $errorInfo              = parent::getErrorInfo($itemArray, $itemInfo);
        $errorInfo['paramName'] = $itemArray['name'];

        return $errorInfo;
    }


    /**
     * Get the item name to be used for the creation of the error code.
     *
     * @since 7.1.0
     *
     * @param array $itemInfo  Base information about the item.
     * @param array $errorInfo Detail information about an item.
     *
     * @return string
     */
    protected function getItemName(array $itemInfo, array $errorInfo)
    {
        return $itemInfo['name'] . '_' . $errorInfo['paramName'];
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
        return 'The "%s" parameter for function %s() is ';
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
        array_shift($data);
        array_unshift($data, $errorInfo['paramName'], $itemInfo['name']);
        return $data;
    }
}
