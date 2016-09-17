<?php
/**
 * PHPCompatibility_Sniffs_PHP_DeprecatedFunctionsSniff.
 *
 * PHP version 7.0
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_DeprecatedFunctionsSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_DeprecatedFunctionsSniff extends PHPCompatibility_Sniff
{
    /**
     * A list of forbidden functions with their alternatives.
     *
     * The array lists : version number with false (deprecated) or true (forbidden) and an alternative function.
     * If no alternative exists, it is NULL, i.e, the function should just not be used.
     *
     * @var array(string => array(string => bool|string|null))
     */
    protected $forbiddenFunctions = array(
                                        'php_check_syntax' => array(
                                            '5.0.5' => true,
                                            'alternative' => null
                                        ),
                                        'call_user_method' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
                                            'alternative' => 'call_user_func'
                                        ),
                                        'call_user_method_array' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
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
                                            '7.0' => true,
                                            'alternative' => 'preg_match'
                                        ),
                                        'ereg_replace' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
                                            'alternative' => 'preg_replace'
                                        ),
                                        'eregi' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
                                            'alternative' => 'preg_match'
                                        ),
                                        'eregi_replace' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
                                            'alternative' => 'preg_replace'
                                        ),
                                        'imagepsbbox' => array(
                                            '7.0' => true,
                                            'alternative' => null
                                        ),
                                        'imagepsencodefont' => array(
                                            '7.0' => true,
                                            'alternative' => null
                                        ),
                                        'imagepsextendfont' => array(
                                            '7.0' => true,
                                            'alternative' => null
                                        ),
                                        'imagepsfreefont' => array(
                                            '7.0' => true,
                                            'alternative' => null
                                        ),
                                        'imagepsloadfont' => array(
                                            '7.0' => true,
                                            'alternative' => null
                                        ),
                                        'imagepsslantfont' => array(
                                            '7.0' => true,
                                            'alternative' => null
                                        ),
                                        'imagepstext' => array(
                                            '7.0' => true,
                                            'alternative' => null
                                        ),
                                        'import_request_variables' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            'alternative' => null
                                        ),
                                        'ldap_sort' => array(
                                            '7.0' => false,
                                            'alternative' => null
                                        ),
                                        'mcrypt_generic_end' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
                                            'alternative' => 'mcrypt_generic_deinit'
                                        ),
                                        'mysql_db_query' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
                                            'alternative' => 'mysqli_select_db and mysqli_query'
                                        ),
                                        'mysql_escape_string' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
                                            'alternative' => 'mysqli_real_escape_string'
                                        ),
                                        'mysql_list_dbs' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
                                            'alternative' => null
                                        ),
                                        'mysqli_bind_param' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => 'mysqli_stmt_bind_param'
                                        ),
                                        'mysqli_bind_result' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => 'mysqli_stmt_bind_result'
                                        ),
                                        'mysqli_client_encoding' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => 'mysqli_character_set_name'
                                        ),
                                        'mysqli_fetch' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => 'mysqli_stmt_fetch'
                                        ),
                                        'mysqli_param_count' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => 'mysqli_stmt_param_count'
                                        ),
                                        'mysqli_get_metadata' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            '5.5' => true,
                                            '5.6' => true,
                                            'alternative' => 'mysqli_stmt_result_metadata'
                                        ),
                                        'mysqli_send_long_data' => array(
                                            '5.3' => false,
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
                                            '7.0' => true,
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
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
                                            'alternative' => null
                                        ),
                                        'set_socket_blocking' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
                                            'alternative' => 'stream_set_blocking'
                                        ),
                                        'split' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
                                            'alternative' => 'preg_split'
                                        ),
                                        'spliti' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
                                            'alternative' => 'preg_split'
                                        ),
                                        'sql_regcase' => array(
                                            '5.3' => false,
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
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
                                            '7.0' => true,
                                            'alternative' => 'datefmt_set_timezone'
                                        ),
                                        'mcrypt_ecb' => array(
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
                                            'alternative' => null
                                        ),
                                        'mcrypt_cbc' => array(
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
                                            'alternative' => null
                                        ),
                                        'mcrypt_cfb' => array(
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
                                            'alternative' => null
                                        ),
                                        'mcrypt_ofb' => array(
                                            '5.5' => false,
                                            '5.6' => false,
                                            '7.0' => true,
                                            'alternative' => null
                                        ),
                                        'ocibindbyname' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_bind_by_name'
                                        ),
                                        'ocicancel' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_cancel'
                                        ),
                                        'ocicloselob' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'OCI-Lob::close'
                                        ),
                                        'ocicollappend' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'OCI-Collection::append'
                                        ),
                                        'ocicollassign' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'OCI-Collection::assign'
                                        ),
                                        'ocicollassignelem' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'OCI-Collection::assignElem'
                                        ),
                                        'ocicollgetelem' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'OCI-Collection::getElem'
                                        ),
                                        'ocicollmax' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'OCI-Collection::max'
                                        ),
                                        'ocicollsize' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'OCI-Collection::size'
                                        ),
                                        'ocicolltrim' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'OCI-Collection::trim'
                                        ),
                                        'ocicolumnisnull' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_field_is_null'
                                        ),
                                        'ocicolumnname' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_field_name'
                                        ),
                                        'ocicolumnprecision' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_field_precision'
                                        ),
                                        'ocicolumnscale' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_field_scale'
                                        ),
                                        'ocicolumnsize' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_field_size'
                                        ),
                                        'ocicolumntype' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_field_type'
                                        ),
                                        'ocicolumntyperaw' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_field_type_raw'
                                        ),
                                        'ocicommit' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_commit'
                                        ),
                                        'ocidefinebyname' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_define_by_name'
                                        ),
                                        'ocierror' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_error'
                                        ),
                                        'ociexecute' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_execute'
                                        ),
                                        'ocifetch' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_fetch'
                                        ),
                                        'ocifetchinto' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => null
                                        ),
                                        'ocifetchstatement' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_fetch_all'
                                        ),
                                        'ocifreecollection' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'OCI-Collection::free'
                                        ),
                                        'ocifreecursor' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_free_statement'
                                        ),
                                        'ocifreedesc' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'OCI-Lob::free'
                                        ),
                                        'ocifreestatement' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_free_statement'
                                        ),
                                        'ociinternaldebug' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_internal_debug'
                                        ),
                                        'ociloadlob' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'OCI-Lob::load'
                                        ),
                                        'ocilogoff' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_close'
                                        ),
                                        'ocilogon' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_connect'
                                        ),
                                        'ocinewcollection' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_new_collection'
                                        ),
                                        'ocinewcursor' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_new_cursor'
                                        ),
                                        'ocinewdescriptor' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_new_descriptor'
                                        ),
                                        'ocinlogon' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_new_connect'
                                        ),
                                        'ocinumcols' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_num_fields'
                                        ),
                                        'ociparse' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_parse'
                                        ),
                                        'ociplogon' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_pconnect'
                                        ),
                                        'ociresult' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_result'
                                        ),
                                        'ocirollback' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_rollback'
                                        ),
                                        'ocirowcount' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_num_rows'
                                        ),
                                        'ocisavelob' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'OCI-Lob::save'
                                        ),
                                        'ocisavelobfile' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'OCI-Lob::import'
                                        ),
                                        'ociserverversion' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_server_version'
                                        ),
                                        'ocisetprefetch' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_set_prefetch'
                                        ),
                                        'ocistatementtype' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'oci_statement_type'
                                        ),
                                        'ociwritelobtofile' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'OCI-Lob::export'
                                        ),
                                        'ociwritetemporarylob' => array(
                                            '5.4' => false,
                                            '5.5' => false,
                                            '5.6' => false,
                                            'alternative' => 'OCI-Lob::writeTemporary'
                                        ),
                                        'mysqli_get_cache_stats' => array(
                                            '5.4' => true,
                                            'alternative' => null
                                        ),
                                    );

	/**
	 * List of just the function names.
	 *
	 * Will be set automatically in the register() method.
	 *
	 * @var array
	 */
    protected $forbiddenFunctionNames = array();

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

        if (in_array($function, $this->forbiddenFunctionNames) === false) {
            return;
        }

        $this->addError($phpcsFile, $stackPtr, $function);

    }//end process()

    /**
     * Generates the error or warning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the forbidden function
     *                                        in the token array.
     * @param string               $function  The name of the forbidden function.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $function)
    {
        $error = '';

        $isError = false;
        $previousVersionStatus = null;
        foreach ($this->forbiddenFunctions[$function] as $version => $forbidden) {
            if ($this->supportsAbove($version)) {
                if ($version != 'alternative') {
                    if ($previousVersionStatus !== $forbidden) {
                        $previousVersionStatus = $forbidden;
                        if ($forbidden === true) {
                            $isError = true;
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

            if ($this->forbiddenFunctions[$function]['alternative'] !== null) {
                $error .= '; use ' . $this->forbiddenFunctions[$function]['alternative'] . ' instead';
            }

            if ($isError === true) {
                $phpcsFile->addError($error, $stackPtr);
            } else {
                $phpcsFile->addWarning($error, $stackPtr);
            }
        }

    }//end addError()

}//end class
