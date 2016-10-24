<?php
/**
 * PHPCompatibility_Sniffs_PHP_RemovedGlobalVariablesSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_RemovedGlobalVariablesSniff.
 *
 * Discourages the use of removed global variables. Suggests alternative extensions if available
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_RemovedGlobalVariablesSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of removed global variables with their alternative, if any.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * If's sufficient to list the first version where the variable was deprecated/removed.
     *
     * @var array(string|null)
     */
    protected $removedGlobalVariables = array(
        'HTTP_RAW_POST_DATA' => array(
            '5.6' => false,
            '7.0' => true,
            'alternative' => 'php://input'
        ),
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_VARIABLE);

    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens  = $phpcsFile->getTokens();
        $varName = substr($tokens[$stackPtr]['content'], 1);

        if (isset($this->removedGlobalVariables[$varName]) === false) {
            return;
        }

        $errorInfo = $this->getErrorInfo($varName);

        if ($errorInfo['deprecated'] !== '' || $errorInfo['removed'] !== '') {
            $this->addError($phpcsFile, $stackPtr, $tokens[$stackPtr]['content'], $errorInfo);
        }

    }//end process()


    /**
     * Retrieve the relevant (version) information for the error message.
     *
     * @param string $varName The name of the variable.
     *
     * @return array
     */
    protected function getErrorInfo($varName)
    {
        $errorInfo  = array(
            'deprecated'  => '',
            'removed'     => '',
            'alternative' => '',
            'error'       => false,
        );

        foreach ($this->removedGlobalVariables[$varName] as $version => $removed) {
            if ($version !== 'alternative' && $this->supportsAbove($version)) {
                if ($removed === true && $errorInfo['removed'] === '') {
                    $errorInfo['removed'] = $version;
                    $errorInfo['error']   = true;
                } elseif($errorInfo['deprecated'] === '') {
                    $errorInfo['deprecated'] = $version;
                }
            }
        }

        if (isset($this->removedGlobalVariables[$varName]['alternative'])) {
            $errorInfo['alternative'] = $this->removedGlobalVariables[$varName]['alternative'];
        }

        return $errorInfo;

    }//end getErrorInfo()


    /**
     * Generates the error or warning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the variable
     *                                        in the token array.
     * @param string               $varName   The name of the variable.
     * @param array                $errorInfo Array with details about the versions
     *                                        in which the variable was deprecated
     *                                        and/or removed.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $varName, $errorInfo)
    {
        $error     = "Global variable '%s' is ";
        $errorCode = $this->stringToErrorCode(substr($varName, 1)) . 'Found';
        $data      = array($varName);

        if($errorInfo['deprecated'] !== '') {
            $error .= 'deprecated since PHP %s and ';
            $data[] = $errorInfo['deprecated'];
        }
        if($errorInfo['removed'] !== '') {
            $error .= 'removed since PHP %s and ';
            $data[] = $errorInfo['removed'];
        }

        // Remove the last 'and' from the message.
        $error = substr($error, 0, strlen($error) - 5);

        if ($errorInfo['alternative'] !== '') {
            $error .= ' - use %s instead.';
            $data[] = $errorInfo['alternative'];
        }

        $this->addMessage($phpcsFile, $error, $stackPtr, $errorInfo['error'], $errorCode, $data);

    }//end addError()

}//end class
