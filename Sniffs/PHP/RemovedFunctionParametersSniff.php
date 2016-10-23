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
     * The array lists : version number with true (deprecated) and false (removed).
     * The index is the location of the parameter in the parameter list, starting at 0 !
     * If's sufficient to list the first version where the function was deprecated/removed.
     *
     * @var array
     */
    protected $removedFunctionParameters = array(
                                        'gmmktime' => array(
                                            6 => array(
                                                'name' => 'is_dst',
                                                '5.1' => true, // deprecated
                                                '7.0' => false,
                                            ),
                                        ),
                                        'ldap_first_attribute' => array(
                                            2 => array(
                                                'name' => 'ber_identifier',
                                                '5.2.4' => false,
                                            ),
                                        ),
                                        'ldap_next_attribute' => array(
                                            2 => array(
                                                'name' => 'ber_identifier',
                                                '5.2.4' => false,
                                            ),
                                        ),
                                        'mktime' => array(
                                            6 => array(
                                                'name' => 'is_dst',
                                                '5.1' => true, // deprecated
                                                '7.0' => false,
                                            ),
                                        ),
                                    );


    /**
     *
     * @var array
     */
    private $removedFunctionParametersNames;


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // Everyone has had a chance to figure out what removed function parameters
        // they want to check for, so now we can cache out the list.
        $this->removedFunctionParametersNames = array_keys($this->removedFunctionParameters);

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

        $function = strtolower($tokens[$stackPtr]['content']);

        if (in_array($function, $this->removedFunctionParametersNames) === false) {
            return;
        }

        $parameterCount = $this->getFunctionCallParameterCount($phpcsFile, $stackPtr);
        if ($parameterCount === 0) {
            return;
        }

        // If the parameter count returned > 0, we know there will be valid open parenthesis.
        $openParenthesis = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        $parameterOffsetFound = $parameterCount - 1;

        foreach($this->removedFunctionParameters[$function] as $offset => $parameterDetails) {
            if ($offset <= $parameterOffsetFound) {
                $this->addError($phpcsFile, $openParenthesis, $function, $offset);
            }
        }

    }//end process()


    /**
     * Generates the error or warning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile         The file being scanned.
     * @param int                  $stackPtr          The position of the function
     *                                                in the token array.
     * @param string               $function          The name of the function.
     * @param int                  $parameterLocation The parameter position within the function call.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $function, $parameterLocation)
    {
        $error = '';

        $isError        = false;
        $previousStatus = null;
        foreach ($this->removedFunctionParameters[$function][$parameterLocation] as $version => $present) {
            if ($version != 'name' && $this->supportsAbove($version)) {

                if ($previousStatus !== $present) {
                    $previousStatus = $present;
                    if ($present === false) {
                        $isError = true;
                        $error .= 'removed';
                    } else {
                        $error .= 'deprecated';
                    }
                    $error .=  ' in PHP version ' . $version . ' and ';
                }
            }
        }

        if (strlen($error) > 0) {
            $error     = 'The "%s" parameter for function %s was ' . $error;
            $error     = substr($error, 0, strlen($error) - 5);
            $errorCode = 'RemovedParameter';
            $data      = array(
                $this->removedFunctionParameters[$function][$parameterLocation]['name'],
                $function,
            );

            if ($isError === true) {
                $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
            } else {
                $phpcsFile->addWarning($error, $stackPtr, $errorCode, $data);
            }
        }

    }//end addError()

}//end class
