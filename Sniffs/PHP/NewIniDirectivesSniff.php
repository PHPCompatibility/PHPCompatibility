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
        'allow_url_include' => array(
            '5.1' => false,
            '5.2' => true
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
        'max_input_nesting_level' => array(
            '5.1' => false,
            '5.2' => false,
            '5.2.2' => true
        ),

        'user_ini.filename' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'user_ini.cache_ttl' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'exit_on_timeout' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'mbstring.http_output_conv_mimetype' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'request_order' => array(
            '5.2' => false,
            '5.3' => true,
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
        'max_input_vars' => array(
            '5.3' => false,
            '5.4' => true,
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
        'enable_post_data_reading' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'windows_show_crt_warning' => array(
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
        $iniToken = $phpcsFile->findNext(T_CONSTANT_ENCAPSED_STRING, $stackPtr, null);

        $filteredToken = str_replace(array('"', "'"), array("", ""), $tokens[$iniToken]['content']);
        if (in_array($filteredToken, array_keys($this->newIniDirectives)) === false) {
            return;
        }

        $error = '';

        foreach ($this->newIniDirectives[$filteredToken] as $version => $present) {
            if ($this->supportsBelow($version)) {
                if ($present === true) {
                    $error .= " not available before version " . $version;
                }
            }
        }

        if (strlen($error) > 0) {
            $error = "INI directive '" . $filteredToken . "' is" . $error;

            $phpcsFile->addWarning($error, $stackPtr);
        }

    }//end process()


}//end class