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

        if (isset($this->functionParameters[$function]) === false) {
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

        foreach($this->functionParameters[$function] as $offset => $parameterDetails) {
            if ($offset > $parameterOffsetFound) {
                foreach ($parameterDetails as $version => $present) {
                    if ($version !== 'name' && $present === true && $this->supportsBelow($version)) {
						$requiredVersion = $version;
						$parameterName   = $parameterDetails['name'];
                    }
                }
            }
        }
        
        if (isset($requiredVersion, $parameterName)) {

            $error     = 'The "%s" parameter for function %s is missing, but was required for PHP version %s and lower';
            $errorCode = 'MissingRequiredParameter';
            $data      = array(
                          $parameterName,
                          $function,
                          $requiredVersion,
                         );
            $phpcsFile->addError($error, $openParenthesis, $errorCode, $data);
		}

    }//end process()

}//end class
