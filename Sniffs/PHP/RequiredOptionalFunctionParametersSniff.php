<?php
/**
 * PHPCompatibility_Sniffs_PHP_RequiredOptionalFunctionParametersSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_RequiredOptionalFunctionParametersSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_RequiredOptionalFunctionParametersSniff extends PHPCompatibility_Sniff
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
                                     'preg_match_all' => array(
                                         2 => array(
                                             'name' => 'matches',
                                             '5.3' => true,
                                             '5.4' => false,
                                         ),
                                     ),
                                     'stream_socket_enable_crypto' => array(
                                         2 => array(
                                             'name' => 'crypto_type',
                                             '5.5' => true,
                                             '5.6' => false,
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
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $ignore = array(
                T_DOUBLE_COLON,
                T_OBJECT_OPERATOR,
                T_FUNCTION,
                T_CONST,
        );

        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        if (in_array($tokens[$prevToken]['code'], $ignore) === true) {
            // Not a call to a PHP function.
            return;
        }

        $function   = $tokens[$stackPtr]['content'];
        $functionLc = strtolower($function);

        if (isset($this->functionParameters[$functionLc]) === false) {
            return;
        }

        $parameterCount = $this->getFunctionCallParameterCount($phpcsFile, $stackPtr);
        if ($parameterCount === 0) {
            return;
        }

        // If the parameter count returned > 0, we know there will be valid open parenthesis.
        $openParenthesis      = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        $parameterOffsetFound = $parameterCount - 1;
        $requiredVersion      = null;
        $parameterName        = null;

        foreach($this->functionParameters[$functionLc] as $offset => $parameterDetails) {
            if ($offset > $parameterOffsetFound) {

                $errorInfo = $this->getErrorInfo($functionLc, $offset);

                if ($errorInfo['requiredVersion'] !== '') {
                    $this->addError($phpcsFile, $openParenthesis, $function, $errorInfo);
                }
            }
        }

    }//end process()


    /**
     * Retrieve the relevant (version) information for the error message.
     *
     * @param string $functionLc      The name of the function.
     * @param int    $parameterOffset The parameter offset within the function call.
     *
     * @return array
     */
    protected function getErrorInfo($functionLc, $parameterOffset)
    {
        $errorInfo  = array(
            'paramName'       => '',
            'requiredVersion' => '',
        );

        $errorInfo['paramName'] = $this->functionParameters[$functionLc][$parameterOffset]['name'];

        foreach ($this->functionParameters[$functionLc][$parameterOffset] as $version => $required) {
            if ($version !== 'name' && $required === true && $this->supportsBelow($version)) {
                $errorInfo['requiredVersion'] = $version;
            }
        }

        return $errorInfo;


    }//end getErrorInfo()


    /**
     * Generates the error or warning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the function
     *                                        in the token array.
     * @param string               $function  The name of the function.
     * @param array                $errorInfo Array with details about the version
     *                                        in which the parameter was still required.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $function, $errorInfo)
    {
        $error     = 'The "%s" parameter for function %s is missing, but was required for PHP version %s and lower';
        $errorCode = $this->stringToErrorCode($function . '_' . $errorInfo['paramName']) . 'Missing';
        $data      = array(
            $errorInfo['paramName'],
            $function,
            $errorInfo['requiredVersion'],
        );

        $phpcsFile->addError($error, $stackPtr, $errorCode, $data);

    }//end addError()

}//end class
