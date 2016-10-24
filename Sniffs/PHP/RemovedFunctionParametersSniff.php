<?php
/**
 * PHPCompatibility_Sniffs_PHP_RemovedFunctionParametersSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_RemovedFunctionParametersSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_RemovedFunctionParametersSniff extends PHPCompatibility_Sniff
{
    /**
     * A list of removed function parameters, which were present in older versions.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * The index is the location of the parameter in the parameter list, starting at 0 !
     * If's sufficient to list the first version where the function parameter was deprecated/removed.
     *
     * @var array
     */
    protected $removedFunctionParameters = array(
                                        'gmmktime' => array(
                                            6 => array(
                                                'name' => 'is_dst',
                                                '5.1' => false, // deprecated
                                                '7.0' => true,
                                            ),
                                        ),
                                        'ldap_first_attribute' => array(
                                            2 => array(
                                                'name' => 'ber_identifier',
                                                '5.2.4' => true,
                                            ),
                                        ),
                                        'ldap_next_attribute' => array(
                                            2 => array(
                                                'name' => 'ber_identifier',
                                                '5.2.4' => true,
                                            ),
                                        ),
                                        'mktime' => array(
                                            6 => array(
                                                'name' => 'is_dst',
                                                '5.1' => false, // deprecated
                                                '7.0' => true,
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
        $this->removedFunctionParameters = $this->arrayKeysToLowercase($this->removedFunctionParameters);

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

        if (isset($this->removedFunctionParameters[$functionLc]) === false) {
            return;
        }

        $parameterCount = $this->getFunctionCallParameterCount($phpcsFile, $stackPtr);
        if ($parameterCount === 0) {
            return;
        }

        // If the parameter count returned > 0, we know there will be valid open parenthesis.
        $openParenthesis = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        $parameterOffsetFound = $parameterCount - 1;

        foreach($this->removedFunctionParameters[$functionLc] as $offset => $parameterDetails) {
            if ($offset <= $parameterOffsetFound) {

                $errorInfo = $this->getErrorInfo($functionLc, $offset);

                if ($errorInfo['deprecated'] !== '' || $errorInfo['removed'] !== '') {
                    $this->addError($phpcsFile, $openParenthesis, $function, $errorInfo);
                }
            }
        }

    }//end process()


    /**
     * Retrieve the relevant (version) information for the error message.
     *
     * @param string $functionLc      The lowercase name of the function.
     * @param int    $parameterOffset The parameter offset within the function call.
     *
     * @return array
     */
    protected function getErrorInfo($functionLc, $parameterOffset)
    {
        $errorInfo  = array(
            'paramName'   => '',
            'deprecated'  => '',
            'removed'     => '',
            'error'       => false,
        );

        $errorInfo['paramName'] = $this->removedFunctionParameters[$functionLc][$parameterOffset]['name'];

        foreach ($this->removedFunctionParameters[$functionLc][$parameterOffset] as $version => $removed) {
            if ($version !== 'name' && $this->supportsAbove($version)) {
                if ($removed === true && $errorInfo['removed'] === '') {
                    $errorInfo['removed'] = $version;
                    $errorInfo['error']   = true;
                } elseif($errorInfo['deprecated'] === '') {
                    $errorInfo['deprecated'] = $version;
                }
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
     * @param array                $errorInfo Array with details about the versions
     *                                        in which the function was deprecated
     *                                        and/or removed.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $function, $errorInfo)
    {
        $error     = 'The "%s" parameter for function %s was ';
        $errorCode = $this->stringToErrorCode($function . '_' . $errorInfo['paramName']) . 'Found';
        $data      = array(
            $errorInfo['paramName'],
            $function,
        );

        if($errorInfo['deprecated'] !== '') {
            $error .= 'deprecated in PHP version %s and ';
            $data[] = $errorInfo['deprecated'];
        }
        if($errorInfo['removed'] !== '') {
            $error .= 'removed in PHP version %s and ';
            $data[] = $errorInfo['removed'];
        }

        // Remove the last 'and' from the message.
        $error = substr($error, 0, strlen($error) - 5);

        $this->addMessage($phpcsFile, $error, $stackPtr, $errorInfo['error'], $errorCode, $data);

    }//end addError()

}//end class
