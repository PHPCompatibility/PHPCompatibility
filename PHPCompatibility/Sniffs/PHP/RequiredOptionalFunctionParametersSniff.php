<?php
/**
 * \PHPCompatibility\Sniffs\PHP\RequiredOptionalFunctionParametersSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\AbstractComplexVersionSniff;

/**
 * \PHPCompatibility\Sniffs\PHP\RequiredOptionalFunctionParametersSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class RequiredOptionalFunctionParametersSniff extends AbstractComplexVersionSniff
{

    /**
     * A list of function parameters, which were required in older versions and became optional later on.
     *
     * The array lists : version number with true (required) and false (optional).
     *
     * The index is the location of the parameter in the parameter list, starting at 0 !
     * If's sufficient to list the last version in which the parameter was still required.
     *
     * @var array
     */
    protected $functionParameters = array(
        'bcscale' => array(
            0 => array(
                'name' => 'scale',
                '7.2'  => true,
                '7.3'  => false,
            ),
        ),
        'getenv' => array(
            0 => array(
                'name' => 'varname',
                '7.0'  => true,
                '7.1'  => false,
            ),
        ),
        'preg_match_all' => array(
            2 => array(
                'name' => 'matches',
                '5.3'  => true,
                '5.4'  => false,
            ),
        ),
        'stream_socket_enable_crypto' => array(
            2 => array(
                'name' => 'crypto_type',
                '5.5'  => true,
                '5.6'  => false,
            ),
        ),
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // Handle case-insensitivity of function names.
        $this->functionParameters = $this->arrayKeysToLowercase($this->functionParameters);

        return array(T_STRING);
    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $ignore = array(
            T_DOUBLE_COLON,
            T_OBJECT_OPERATOR,
            T_FUNCTION,
            T_CONST,
        );

        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        if (in_array($tokens[$prevToken]['code'], $ignore, true) === true) {
            // Not a call to a PHP function.
            return;
        }

        $function   = $tokens[$stackPtr]['content'];
        $functionLc = strtolower($function);

        if (isset($this->functionParameters[$functionLc]) === false) {
            return;
        }

        $parameterCount  = $this->getFunctionCallParameterCount($phpcsFile, $stackPtr);
        $openParenthesis = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);

        // If the parameter count returned > 0, we know there will be valid open parenthesis.
        if ($parameterCount === 0 && $tokens[$openParenthesis]['code'] !== T_OPEN_PARENTHESIS) {
            return;
        }

        $parameterOffsetFound = $parameterCount - 1;

        foreach ($this->functionParameters[$functionLc] as $offset => $parameterDetails) {
            if ($offset > $parameterOffsetFound) {
                $itemInfo = array(
                    'name'   => $function,
                    'nameLc' => $functionLc,
                    'offset' => $offset,
                );
                $this->handleFeature($phpcsFile, $openParenthesis, $itemInfo);
            }
        }

    }//end process()


    /**
     * Determine whether an error/warning should be thrown for an item based on collected information.
     *
     * @param array $errorInfo Detail information about an item.
     *
     * @return bool
     */
    protected function shouldThrowError(array $errorInfo)
    {
        return ($errorInfo['requiredVersion'] !== '');
    }


    /**
     * Get the relevant sub-array for a specific item from a multi-dimensional array.
     *
     * @param array $itemInfo Base information about the item.
     *
     * @return array Version and other information about the item.
     */
    public function getItemArray(array $itemInfo)
    {
        return $this->functionParameters[$itemInfo['nameLc']][$itemInfo['offset']];
    }


    /**
     * Get an array of the non-PHP-version array keys used in a sub-array.
     *
     * @return array
     */
    protected function getNonVersionArrayKeys()
    {
        return array('name');
    }


    /**
     * Retrieve the relevant detail (version) information for use in an error message.
     *
     * @param array $itemArray Version and other information about the item.
     * @param array $itemInfo  Base information about the item.
     *
     * @return array
     */
    public function getErrorInfo(array $itemArray, array $itemInfo)
    {
        $errorInfo = array(
            'paramName'       => '',
            'requiredVersion' => '',
        );

        $versionArray = $this->getVersionArray($itemArray);

        if (empty($versionArray) === false) {
            foreach ($versionArray as $version => $required) {
                if ($required === true && $this->supportsBelow($version) === true) {
                    $errorInfo['requiredVersion'] = $version;
                }
            }
        }

        $errorInfo['paramName'] = $itemArray['name'];

        return $errorInfo;

    }//end getErrorInfo()


    /**
     * Get the error message template for this sniff.
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return 'The "%s" parameter for function %s() is missing, but was required for PHP version %s and lower';
    }


    /**
     * Generates the error or warning for this item.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the relevant token in
     *                                         the stack.
     * @param array                 $itemInfo  Base information about the item.
     * @param array                 $errorInfo Array with detail (version) information
     *                                         relevant to the item.
     *
     * @return void
     */
    public function addError(\PHP_CodeSniffer_File $phpcsFile, $stackPtr, array $itemInfo, array $errorInfo)
    {
        $error     = $this->getErrorMsgTemplate();
        $errorCode = $this->stringToErrorCode($itemInfo['name'] . '_' . $errorInfo['paramName']) . 'Missing';
        $data      = array(
            $errorInfo['paramName'],
            $itemInfo['name'],
            $errorInfo['requiredVersion'],
        );

        $phpcsFile->addError($error, $stackPtr, $errorCode, $data);

    }//end addError()


}//end class
