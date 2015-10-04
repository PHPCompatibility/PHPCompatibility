<?php
/**
 * PHPCompatibility_Sniffs_PHP_DeprecatedFunctionsSniff.
 *
 * PHP version 5.6
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2014 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_DeprecatedFunctionsSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @version   1.1.0
 * @copyright 2014 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_DeprecatedFunctionsSniff extends PHPCompatibility_Sniff
{

    /**
     * If true, forbidden functions will be considered regular expressions.
     *
     * @var bool
     */
    protected $patternMatch = false;
    
    /**
     * A list of forbidden functions with their alternatives.
     *
     * The array lists : version number with 0 (deprecated) or 1 (forbidden) and an alternative function.
     * If no alternative exists, it is NULL. IE, the function should just not be used.
     *
     * @var array(string => array(string => int|string|null))
     */
    public $forbiddenFunctions = array(
                                        'call_user_method' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'call_user_func'
                                        ),
                                        'call_user_method_array' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'call_user_func_array'
                                        ),
                                        'define_syslog_variables' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => null
                                        ),
                                        'dl' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => null
                                        ),
                                        'ereg' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'preg_match'
                                        ),
                                        'ereg_replace' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'preg_replace'
                                        ),
                                        'eregi' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'preg_match'
                                        ),
                                        'eregi_replace' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'preg_replace'
                                        ),
                                        'import_request_variables' => array(
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => false,
                                            'alternative' => null
                                        ),
                                        'mcrypt_generic_end' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'mcrypt_generic_deinit'
                                        ),
                                        'mysql_db_query' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'mysql_select_db and mysql_query'
                                        ),
                                        'mysql_escape_string' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'mysql_real_escape_string'
                                        ),
                                        'mysql_list_dbs' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => null
                                        ),
                                        'mysqli_bind_param' => array(
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => 'mysqli_stmt_bind_param'
                                        ),
                                        'mysqli_bind_result' => array(
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => 'mysqli_stmt_bind_result'
                                        ),
                                        'mysqli_client_encoding' => array(
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => 'mysqli_character_set_name'
                                        ),
                                        'mysqli_fetch' => array(
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => 'mysqli_stmt_fetch'
                                        ),
                                        'mysqli_param_count' => array(
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => 'mysqli_stmt_param_count'
                                        ),
                                        'mysqli_get_metadata' => array(
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => 'mysqli_stmt_result_metadata'
                                        ),
                                        'mysqli_send_long_data' => array(
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => 'mysqli_stmt_send_long_data'
                                        ),
                                        'magic_quotes_runtime' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => null
                                        ),
                                        'session_register' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => '$_SESSION'
                                        ),
                                        'session_unregister' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => '$_SESSION'
                                        ),
                                        'session_is_registered' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => '$_SESSION'
                                        ),
                                        'set_magic_quotes_runtime' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => null
                                        ),
                                        'set_socket_blocking' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'stream_set_blocking'
                                        ),
                                        'split' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'preg_split'
                                        ),
                                        'spliti' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'preg_split'
                                        ),
                                        'sql_regcase' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => null
                                        ),
                                        'php_logo_guid' => array(
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => null
                                        ),
                                        'php_egg_logo_guid' => array(
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => null
                                        ),
                                        'php_real_logo_guid' => array(
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => null
                                        ),
                                        'zend_logo_guid' => array(
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => null
                                        ),
                                        'datefmt_set_timezone_id' => array(
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'datefmt_set_timezone'
                                        ),
                                        'mcrypt_ecb' => array(
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => null
                                        ),
                                        'mcrypt_cbc' => array(
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => null
                                        ),
                                        'mcrypt_cfb' => array(
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => null
                                        ),
                                        'mcrypt_ofb' => array(
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => null
                                        ),
                                    );

    /**
     * If true, an error will be thrown; otherwise a warning.
     *
     * @var bool
     */
    public $error = false;

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // Everyone has had a chance to figure out what forbidden functions
        // they want to check for, so now we can cache out the list.
        $this->forbiddenFunctionNames = array_keys($this->forbiddenFunctions);
    
        if ($this->patternMatch === true) {
            foreach ($this->forbiddenFunctionNames as $i => $name) {
                $this->forbiddenFunctionNames[$i] = '/'.$name.'/i';
            }
        }
    
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
                T_USE,
                T_NS_SEPARATOR,
        );

        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        if (in_array($tokens[$prevToken]['code'], $ignore) === true) {
            // Not a call to a PHP function.
            return;
        }

        $function = strtolower($tokens[$stackPtr]['content']);
        $pattern  = null;

        if ($this->patternMatch === true) {
            $count   = 0;
            $pattern = preg_replace(
                    $this->forbiddenFunctionNames,
                    $this->forbiddenFunctionNames,
                    $function,
                    1,
                    $count
            );

            if ($count === 0) {
                return;
            }

            // Remove the pattern delimiters and modifier.
            $pattern = substr($pattern, 1, -2);
        } else {
            if (in_array($function, $this->forbiddenFunctionNames) === false) {
                return;
            }
        }

        $this->addError($phpcsFile, $stackPtr, $function, $pattern);

    }//end process()

    /**
     * Generates the error or wanrning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the forbidden function
     *                                        in the token array.
     * @param string               $function  The name of the forbidden function.
     * @param string               $pattern   The pattern used for the match.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $function, $pattern=null)
    {
        if ($pattern === null) {
            $pattern = $function;
        }

        $error = '';

        $this->error = false;
        $previousVersionStatus = null;
        foreach ($this->forbiddenFunctions[$pattern] as $version => $forbidden) {
            if ($this->supportsAbove($version)) {
                if ($version != 'alternative') {
                    if ($previousVersionStatus !== $forbidden) {
                        $previousVersionStatus = $forbidden;
                        if ($forbidden === true) {
                            $this->error = true;
                            $error .= 'forbidden';
                        } else {
                            $error .= 'discouraged';
                        }
                        $error .=  ' from PHP version ' . $version . ' and ';
                    }
                }
            }
        }
        if (strlen($error) > 0) {
            $error = 'The use of function ' . $function . ' is ' . $error;
            $error = substr($error, 0, strlen($error) - 5);

            if ($this->forbiddenFunctions[$pattern]['alternative'] !== null) {
                $error .= '; use ' . $this->forbiddenFunctions[$pattern]['alternative'] . ' instead';
            }

            if ($this->error === true) {
                $phpcsFile->addError($error, $stackPtr);
            } else {
                $phpcsFile->addWarning($error, $stackPtr);
            }
        }

    }//end addError()

}//end class
