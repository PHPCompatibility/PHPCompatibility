<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewFunctionParametersSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_newFunctionParametersSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_NewFunctionParametersSniff extends PHPCompatibility_Sniff
{
    /**
     * A list of new functions, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * The index is the location of the parameter in the parameter list, starting at 0 !
     * If's sufficient to list the first version where the function appears.
     *
     * @var array
     */
    protected $newFunctionParameters = array(
                                        'array_filter' => array(
                                            2 => array(
                                                'name' => 'flag',
                                                '5.5' => false,
                                                '5.6' => true
                                            ),
                                        ),
                                        'array_slice' => array(
                                            1 => array(
                                                'name' => 'preserve_keys',
                                                '5.0.1' => false,
                                                '5.0.2' => true
                                            ),
                                        ),
                                        'array_unique' => array(
                                            1 => array(
                                                'name' => 'sort_flags',
                                                '5.2.8' => false,
                                                '5.2.9' => true
                                            ),
                                        ),
                                        'assert' => array(
                                            1 => array(
                                                'name' => 'description',
                                                '5.4.7' => false,
                                                '5.4.8' => true
                                            ),
                                        ),
                                        'base64_decode' => array(
                                            1 => array(
                                                'name' => 'strict',
                                                '5.1' => false,
                                                '5.2' => true
                                            ),
                                        ),
                                        'class_implements' => array(
                                            1 => array(
                                                'name' => 'autoload',
                                                '5.0' => false,
                                                '5.1' => true
                                            ),
                                        ),
                                        'class_parents' => array(
                                            1 => array(
                                                'name' => 'autoload',
                                                '5.0' => false,
                                                '5.1' => true
                                            ),
                                        ),
                                        'clearstatcache' => array(
                                            0 => array(
                                                'name' => 'clear_realpath_cache',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                            1 => array(
                                                'name' => 'filename',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'copy' => array(
                                            2 => array(
                                                'name' => 'context',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'curl_multi_info_read' => array(
                                            1 => array(
                                                'name' => 'msgs_in_queue',
                                                '5.1' => false,
                                                '5.2' => true
                                            ),
                                        ),
                                        'debug_backtrace' => array(
                                            0 => array(
                                                'name' => 'options',
                                                '5.2.4' => false,
                                                '5.2.5' => true
                                            ),
                                            1 => array(
                                                'name' => 'limit',
                                                '5.3' => false,
                                                '5.4' => true
                                            ),
                                        ),
                                        'debug_print_backtrace' => array(
                                            0 => array(
                                                'name' => 'options',
                                                '5.3.5' => false,
                                                '5.3.6' => true
                                            ),
                                            1 => array(
                                                'name' => 'limit',
                                                '5.3' => false,
                                                '5.4' => true
                                            ),
                                        ),
                                        'dirname' => array(
                                            1 => array(
                                                'name' => 'levels',
                                                '5.6' => false,
                                                '7.0' => true
                                            ),
                                        ),
                                        'dns_get_record' => array(
                                            4 => array(
                                                'name' => 'raw',
                                                '5.3' => false,
                                                '5.4' => true
                                            ),
                                        ),
                                        'fgetcsv' => array(
                                            4 => array(
                                                'name' => 'escape',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'fputcsv' => array(
                                            4 => array(
                                                'name' => 'escape_char',
                                                '5.5.3' => false,
                                                '5.5.4' => true
                                            ),
                                        ),
                                        'file_get_contents' => array(
                                            3 => array(
                                                'name' => 'offset',
                                                '5.0' => false,
                                                '5.1' => true
                                            ),
                                            4 => array(
                                                'name' => 'maxlen',
                                                '5.0' => false,
                                                '5.1' => true
                                            ),
                                        ),
                                        'filter_input_array' => array(
                                            2 => array(
                                                'name' => 'add_empty',
                                                '5.3' => false,
                                                '5.4' => true
                                            ),
                                        ),
                                        'filter_var_array' => array(
                                            2 => array(
                                                'name' => 'add_empty',
                                                '5.3' => false,
                                                '5.4' => true
                                            ),
                                        ),
                                        'gettimeofday' => array(
                                            0 => array(
                                                'name' => 'return_float',
                                                '5.0' => false,
                                                '5.1' => true
                                            ),
                                        ),
                                        'get_html_translation_table' => array(
                                            2 => array(
                                                'name' => 'encoding',
                                                '5.3.3' => false,
                                                '5.3.4' => true
                                            ),
                                        ),
                                        'get_loaded_extensions' => array(
                                            0 => array(
                                                'name' => 'zend_extensions',
                                                '5.2.3' => false,
                                                '5.2.4' => true
                                            ),
                                        ),
                                        'gzcompress' => array(
                                            2 => array(
                                                'name' => 'encoding',
                                                '5.3' => false,
                                                '5.4' => true
                                            ),
                                        ),
                                        'gzdeflate' => array(
                                            2 => array(
                                                'name' => 'encoding',
                                                '5.3' => false,
                                                '5.4' => true
                                            ),
                                        ),
                                        'htmlentities' => array(
                                            3 => array(
                                                'name' => 'double_encode',
                                                '5.2.2' => false,
                                                '5.2.3' => true
                                            ),
                                        ),
                                        'htmlspecialchars' => array(
                                            3 => array(
                                                'name' => 'double_encode',
                                                '5.2.2' => false,
                                                '5.2.3' => true
                                            ),
                                        ),
                                        'http_build_query' => array(
                                            2 => array(
                                                'name' => 'arg_separator',
                                                '5.1.1' => false,
                                                '5.1.2' => true
                                            ),
                                            3 => array(
                                                'name' => 'enc_type',
                                                '5.3' => false,
                                                '5.4' => true
                                            ),
                                        ),
                                        'idn_to_ascii' => array(
                                            2 => array(
                                                'name' => 'variant',
                                                '5.3' => false,
                                                '5.4' => true
                                            ),
                                            3 => array(
                                                'name' => 'idna_info',
                                                '5.3' => false,
                                                '5.4' => true
                                            ),
                                        ),
                                        'idn_to_utf8' => array(
                                            2 => array(
                                                'name' => 'variant',
                                                '5.3' => false,
                                                '5.4' => true
                                            ),
                                            3 => array(
                                                'name' => 'idna_info',
                                                '5.3' => false,
                                                '5.4' => true
                                            ),
                                        ),
                                        'imagecolorset' => array(
                                            5 => array(
                                                'name' => 'alpha',
                                                '5.3' => false,
                                                '5.4' => true
                                            ),
                                        ),
                                        'imagepng' => array(
                                            2 => array(
                                                'name' => 'quality',
                                                '5.1.1' => false,
                                                '5.1.2' => true
                                            ),
                                            3 => array(
                                                'name' => 'filters',
                                                '5.1.2' => false,
                                                '5.1.3' => true
                                            ),
                                        ),
                                        'imagerotate' => array(
                                            3 => array(
                                                'name' => 'ignore_transparent',
                                                '5.0' => false,
                                                '5.1' => true
                                            ),
                                        ),
                                        'imap_open' => array(
                                            4 => array(
                                                'name' => 'n_retries',
                                                '5.1' => false,
                                                '5.2' => true
                                            ),
                                            5 => array(
                                                'name' => 'params',
                                                '5.3.1' => false,
                                                '5.3.2' => true
                                            ),
                                        ),
                                        'imap_reopen' => array(
                                            3 => array(
                                                'name' => 'n_retries',
                                                '5.1' => false,
                                                '5.2' => true
                                            ),
                                        ),
                                        'ini_get_all' => array(
                                            1 => array(
                                                'name' => 'details',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'is_a' => array(
                                            2 => array(
                                                'name' => 'allow_string',
                                                '5.3.8' => false,
                                                '5.3.9' => true
                                            ),
                                        ),
                                        'is_subclass_of' => array(
                                            2 => array(
                                                'name' => 'allow_string',
                                                '5.3.8' => false,
                                                '5.3.9' => true
                                            ),
                                        ),
                                        'iterator_to_array' => array(
                                            1 => array(
                                                'name' => 'use_keys',
                                                '5.2' => false,
                                                '5.2.1' => true
                                            ),
                                        ),
                                        'json_decode' => array(
                                            2 => array(
                                                'name' => 'depth',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                            3 => array(
                                                'name' => 'options',
                                                '5.3' => false,
                                                '5.4' => true
                                            ),
                                        ),
                                        'json_encode' => array(
                                            1 => array(
                                                'name' => 'options',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                            2 => array(
                                                'name' => 'depth',
                                                '5.4' => false,
                                                '5.5' => true
                                            ),
                                        ),
                                        'memory_get_peak_usage' => array(
                                            0 => array(
                                                'name' => 'real_usage',
                                                '5.1' => false,
                                                '5.2' => true
                                            ),
                                        ),
                                        'memory_get_usage' => array(
                                            0 => array(
                                                'name' => 'real_usage',
                                                '5.1' => false,
                                                '5.2' => true
                                            ),
                                        ),
                                        'mb_encode_numericentity' => array(
                                            3 => array(
                                                'name' => 'is_hex',
                                                '5.3' => false,
                                                '5.4' => true
                                            ),
                                        ),
                                        'mb_strrpos' => array(
                                            /*
                                             * Note: the actual position is 2, but the original 3rd
                                             * parameter 'encoding' was moved to the 4th position.
                                             * So the only way to detect if offset is used is when
                                             * both offset and encoding are set.
                                             */
                                            3 => array(
                                                'name' => 'offset',
                                                '5.1' => false,
                                                '5.2' => true
                                            ),
                                        ),
                                        'mssql_connect' => array(
                                            3 => array(
                                                'name' => 'new_link',
                                                '5.0' => false,
                                                '5.1' => true
                                            ),
                                        ),
                                        'mysqli_commit' => array(
                                            1 => array(
                                                'name' => 'flags',
                                                '5.4' => false,
                                                '5.5' => true
                                            ),
                                            2 => array(
                                                'name' => 'name',
                                                '5.4' => false,
                                                '5.5' => true
                                            ),
                                        ),
                                        'mysqli_rollback' => array(
                                            1 => array(
                                                'name' => 'flags',
                                                '5.4' => false,
                                                '5.5' => true
                                            ),
                                            2 => array(
                                                'name' => 'name',
                                                '5.4' => false,
                                                '5.5' => true
                                            ),
                                        ),
                                        'nl2br' => array(
                                            1 => array(
                                                'name' => 'is_xhtml',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'openssl_decrypt' => array(
                                            4 => array(
                                                'name' => 'iv',
                                                '5.3.2' => false,
                                                '5.3.3' => true
                                            ),
                                        ),
                                        'openssl_encrypt' => array(
                                            4 => array(
                                                'name' => 'iv',
                                                '5.3.2' => false,
                                                '5.3.3' => true
                                            ),
                                        ),
                                        'openssl_pkcs7_verify' => array(
                                            5 => array(
                                                'name' => 'content',
                                                '5.0' => false,
                                                '5.1' => true
                                            ),
                                        ),
                                        'openssl_seal' => array(
                                            4 => array(
                                                'name' => 'method',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'openssl_verify' => array(
                                            3 => array(
                                                'name' => 'signature_alg',
                                                '5.1' => false,
                                                '5.2' => true
                                            ),
                                        ),
                                        'parse_ini_file' => array(
                                            2 => array(
                                                'name' => 'scanner_mode',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'parse_url' => array(
                                            1 => array(
                                                'name' => 'component',
                                                '5.1.1' => false,
                                                '5.1.2' => true
                                            ),
                                        ),
                                        'pg_lo_create' => array(
                                            1 => array(
                                                'name' => 'object_id',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'pg_lo_import' => array(
                                            2 => array(
                                                'name' => 'object_id',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'preg_replace' => array(
                                            4 => array(
                                                'name' => 'count',
                                                '5.0' => false,
                                                '5.1' => true
                                            ),
                                        ),
                                        'preg_replace_callback' => array(
                                            4 => array(
                                                'name' => 'count',
                                                '5.0' => false,
                                                '5.1' => true
                                            ),
                                        ),
                                        'round' => array(
                                            2 => array(
                                                'name' => 'mode',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'sem_acquire' => array(
                                            1 => array(
                                                'name' => 'nowait',
                                                '5.6' => false,
                                                '5.6.1' => true
                                            ),
                                        ),
                                        'session_regenerate_id' => array(
                                            0 => array(
                                                'name' => 'delete_old_session',
                                                '5.0' => false,
                                                '5.1' => true
                                            ),
                                        ),
                                        'session_set_cookie_params' => array(
                                            4 => array(
                                                'name' => 'httponly',
                                                '5.1' => false,
                                                '5.2' => true
                                            ),
                                        ),
                                        'session_set_save_handler' => array(
                                            6 => array(
                                                'name' => 'create_sid',
                                                '5.5' => false,
                                                '5.5.1' => true
                                            ),
                                        ),
                                        'session_start' => array(
                                            0 => array(
                                                'name' => 'options',
                                                '5.6' => false,
                                                '7.0' => true
                                            ),
                                        ),
                                        'setcookie' => array(
                                            6 => array(
                                                'name' => 'httponly',
                                                '5.1' => false,
                                                '5.2' => true
                                            ),
                                        ),
                                        'setrawcookie' => array(
                                            6 => array(
                                                'name' => 'httponly',
                                                '5.1' => false,
                                                '5.2' => true
                                            ),
                                        ),
                                        'simplexml_load_file' => array(
                                            4 => array(
                                                'name' => 'is_prefix',
                                                '5.1' => false,
                                                '5.2' => true
                                            ),
                                        ),
                                        'simplexml_load_string' => array(
                                            4 => array(
                                                'name' => 'is_prefix',
                                                '5.1' => false,
                                                '5.2' => true
                                            ),
                                        ),
                                        'spl_autoload_register' => array(
                                            2 => array(
                                                'name' => 'prepend',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'stream_context_create' => array(
                                            1 => array(
                                                'name' => 'params',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'stream_copy_to_stream' => array(
                                            3 => array(
                                                'name' => 'offset',
                                                '5.0' => false,
                                                '5.1' => true
                                            ),
                                        ),
                                        'stream_get_contents' => array(
                                            2 => array(
                                                'name' => 'offset',
                                                '5.0' => false,
                                                '5.1' => true
                                            ),
                                        ),
                                        'stream_wrapper_register' => array(
                                            2 => array(
                                                'name' => 'flags',
                                                '5.2.3' => false,
                                                '5.2.4' => true
                                            ),
                                        ),
                                        'stristr' => array(
                                            2 => array(
                                                'name' => 'before_needle',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'strstr' => array(
                                            2 => array(
                                                'name' => 'before_needle',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'str_word_count' => array(
                                            2 => array(
                                                'name' => 'charlist',
                                                '5.0' => false,
                                                '5.1' => true
                                            ),
                                        ),
                                        'substr_count' => array(
                                            2 => array(
                                                'name' => 'offset',
                                                '5.0' => false,
                                                '5.1' => true
                                            ),
                                            3 => array(
                                                'name' => 'length',
                                                '5.0' => false,
                                                '5.1' => true
                                            ),
                                        ),
                                        'sybase_connect' => array(
                                            5 => array(
                                                'name' => 'new',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'timezone_transitions_get' => array(
                                            1 => array(
                                                'name' => 'timestamp_begin',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                            2 => array(
                                                'name' => 'timestamp_end',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'timezone_identifiers_list' => array(
                                            0 => array(
                                                'name' => 'what',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                            1 => array(
                                                'name' => 'country',
                                                '5.2' => false,
                                                '5.3' => true
                                            ),
                                        ),
                                        'token_get_all' => array(
                                            1 => array(
                                                'name' => 'flags',
                                                '5.6' => false,
                                                '7.0' => true
                                            ),
                                        ),
                                        'ucwords' => array(
                                            1 => array(
                                                'name' => 'delimiters',
                                                '5.4.31' => false,
                                                '5.5.15' => false,
                                                '5.4.32' => true,
                                                '5.5.16' => true
                                            ),
                                        ),
                                        'unserialize' => array(
                                            1 => array(
                                                'name' => 'options',
                                                '5.6' => false,
                                                '7.0' => true
                                            ),
                                        ),
                                    );


    /**
     *
     * @var array
     */
    private $newFunctionParametersNames;


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // Everyone has had a chance to figure out what forbidden functions
        // they want to check for, so now we can cache out the list.
        $this->newFunctionParametersNames = array_keys($this->newFunctionParameters);

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

        if (in_array($function, $this->newFunctionParametersNames) === false) {
            return;
        }

        $parameterCount = $this->getFunctionCallParameterCount($phpcsFile, $stackPtr);
        if ($parameterCount === 0) {
            return;
        }

        // If the parameter count returned > 0, we know there will be valid open parenthesis.
        $openParenthesis = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        $parameterOffsetFound = $parameterCount - 1;

        foreach($this->newFunctionParameters[$function] as $offset => $parameterDetails) {
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

        $isError = false;
        foreach ($this->newFunctionParameters[$function][$parameterLocation] as $version => $present) {
            if ($version != 'name' && $present === false && $this->supportsBelow($version)) {
                $isError = true;
                $error .= 'in PHP version ' . $version . ' or earlier';
                break;
            }
        }

        if (strlen($error) > 0) {
            $error = 'The function ' . $function . ' does not have a parameter "' . $this->newFunctionParameters[$function][$parameterLocation]['name'] . '" ' . $error;

            if ($isError === true) {
                $phpcsFile->addError($error, $stackPtr);
            } else {
                $phpcsFile->addWarning($error, $stackPtr);
            }
        }

    }//end addError()

}//end class
