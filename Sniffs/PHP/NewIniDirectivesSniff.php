<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewIniDirectivesSniff.
 *
 * PHP version 5.5
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2013 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewIniDirectivesSniff.
 *
 * Discourages the use of new INI directives through ini_set() or ini_get().
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2013 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_NewIniDirectivesSniff extends PHPCompatibility_Sniff
{
    /**
     * A list of new INI directives
     *
     * @var array(string)
     */
    protected $newIniDirectives = array(
        'auto_globals_jit' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'com.code_page' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'date.default_latitude' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'date.default_longitude' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'date.sunrise_zenith' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'date.sunset_zenith' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'ibase.default_charset' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'ibase.default_db' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'mail.force_extra_parameters' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'mime_magic.debug' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'mysqli.max_links' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'mysqli.default_port' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'mysqli.default_socket' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'mysqli.default_host' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'mysqli.default_user' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'mysqli.default_pw' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'report_zend_debug' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'session.hash_bits_per_character' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'session.hash_function' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'soap.wsdl_cache_dir' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'soap.wsdl_cache_enabled' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'soap.wsdl_cache_ttl' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'sqlite.assoc_case' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'tidy.clean_output' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'tidy.default_config' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'zend.ze1_compatibility_mode' => array(
            '4.4' => false,
            '5.0' => true,
        ),

        'date.timezone' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'detect_unicode' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'fbsql.batchsize' => array(
            '5.0'         => false,
            '5.1'         => true,
            'alternative' => 'fbsql.batchSize',
        ),
        'realpath_cache_size' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'realpath_cache_ttl' => array(
            '5.0' => false,
            '5.1' => true,
        ),

        'mbstring.strict_detection' => array(
            '5.1.1' => false,
            '5.1.2' => true,
        ),
        'mssql.charset' => array(
            '5.1.1' => false,
            '5.1.2' => true,
        ),

        'gd.jpeg_ignore_warning' => array(
            '5.1.2' => false,
            '5.1.3' => true,
        ),

        'fbsql.show_timestamp_decimals' => array(
            '5.1.4' => false,
            '5.1.5' => true,
        ),
        'soap.wsdl_cache' => array(
            '5.1.4' => false,
            '5.1.5' => true,
        ),
        'soap.wsdl_cache_limit' => array(
            '5.1.4' => false,
            '5.1.5' => true,
        ),

        'allow_url_include' => array(
            '5.1' => false,
            '5.2' => true
        ),
        'filter.default' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'filter.default_flags' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'pcre.backtrack_limit' => array(
            '5.1' => false,
            '5.2' => true
        ),
        'pcre.recursion_limit' => array(
            '5.1' => false,
            '5.2' => true
        ),
        'session.cookie_httponly' => array(
            '5.1' => false,
            '5.2' => true
        ),

        'cgi.check_shebang_line' => array(
            '5.2.0' => false,
            '5.2.1' => true
        ),

        'max_input_nesting_level' => array(
            '5.2.2' => false,
            '5.2.3' => true
        ),

        'mysqli.allow_local_infile' => array(
            '5.2.3' => false,
            '5.2.4' => true,
        ),

        'max_file_uploads' => array(
            '5.2.11' => false,
            '5.2.12' => true,
        ),

        'cgi.discard_path' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'exit_on_timeout' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'intl.default_locale' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'intl.error_level' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'mail.add_x_header' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'mail.log' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'mbstring.http_output_conv_mimetype' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'mysqli.allow_persistent' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'mysqli.cache_size' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'mysqli.max_persistent' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'mysqlnd.collect_memory_statistics' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'mysqlnd.collect_statistics' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'mysqlnd.debug' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'mysqlnd.net_read_buffer_size' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'odbc.default_cursortype' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'request_order' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'user_ini.cache_ttl' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'user_ini.filename' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'zend.enable_gc' => array(
            '5.2' => false,
            '5.3' => true,
        ),

        'curl.cainfo' => array(
            '5.3.6' => false,
            '5.3.7' => true,
        ),

        'max_input_vars' => array(
            '5.3.8' => false,
            '5.3.9' => true,
        ),

        'sqlite3.extension_dir' => array(
            '5.3.10' => false,
            '5.3.11' => true,
        ),

        'cli.pager' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'cli.prompt' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'cli_server.color' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'enable_post_data_reading' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'mysqlnd.mempool_default_size' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'mysqlnd.net_cmd_buffer_size' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'mysqlnd.net_read_timeout' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'phar.cache_list' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'session.upload_progress.enabled' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'session.upload_progress.cleanup' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'session.upload_progress.name' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'session.upload_progress.freq' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'session.upload_progress.min_freq' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'session.upload_progress.prefix' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'windows_show_crt_warning' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'zend.detect_unicode' => array(
            '5.3'         => false,
            '5.4'         => true,
            'alternative' => 'detect_unicode',
        ),
        'zend.multibyte' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'zend.script_encoding' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'zend.signal_check' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'mysqlnd.log_mask' => array(
            '5.3' => false,
            '5.4' => true,
        ),

        'intl.use_exceptions' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'mysqlnd.sha256_server_public_key' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'mysqlnd.trace_alloc' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'sys_temp_dir' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'xsl.security_prefs' => array(
            '5.4' => false,
            '5.5' => true,
        ),

        'session.use_strict_mode' => array(
            '5.5.1' => false,
            '5.5.2' => true,
        ),

        'mysqli.rollback_on_cached_plink' => array(
            '5.5' => false,
            '5.6' => true,
        ),

        'assert.exception' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'pcre.jit' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'session.lazy_write' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'zend.assertions' => array(
            '5.6' => false,
            '7.0' => true,
        ),

        'session.sid_length' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'session.sid_bits_per_character' => array(
            '7.0' => false,
            '7.1' => true,
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
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
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
        if ($function != 'ini_get' && $function != 'ini_set') {
            return;
        }

        $iniToken = $this->getFunctionCallParameter($phpcsFile, $stackPtr, 1);
        if ($iniToken === false) {
            return;
        }

        $filteredToken = $this->stripQuotes($iniToken['raw']);
        if (isset($this->newIniDirectives[$filteredToken]) === false) {
            return;
        }

        $notInVersion = '';
        foreach ($this->newIniDirectives[$filteredToken] as $version => $present) {
            if ($version !== 'alternative' && $present === false && $this->supportsBelow($version)) {
                $notInVersion = $version;
            }
        }

        if ($notInVersion !== '') {
            $error   = "INI directive '%s' is not present in PHP version %s or earlier";
            $isError = ($function !== 'ini_get') ? true : false;
            $data    = array(
                $filteredToken,
                $notInVersion
            );
            if (isset($this->newIniDirectives[$filteredToken]['alternative'])) {
                $error .= ". This directive was previously called '%s'.";
                $data[] = $this->newIniDirectives[$filteredToken]['alternative'];
            }

            if ($isError === true) {
                $phpcsFile->addError($error, $iniToken['end'], 'Found', $data);
            } else {
                $phpcsFile->addWarning($error, $iniToken['end'], 'Found', $data);
            }
        }

    }//end process()


}//end class
