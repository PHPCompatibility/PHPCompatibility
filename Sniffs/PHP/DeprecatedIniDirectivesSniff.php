<?php
/**
 * PHPCompatibility_Sniffs_PHP_DeprecatedIniDirectivesSniff.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_DeprecatedIniDirectivesSniff.
 *
 * Discourages the use of deprecated INI directives through ini_set() or ini_get().
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_DeprecatedIniDirectivesSniff extends PHPCompatibility_Sniff
{
    /**
     * A list of deprecated INI directives.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * If's sufficient to list the first version where the ini directive was deprecated/removed.
     *
     * @var array(string)
     */
    protected $deprecatedIniDirectives = array(
        'fbsql.batchSize' => array(
            '5.1' => true,
            'alternative' => 'fbsql.batchsize',
        ),

        'ifx.allow_persistent' => array(
            '5.2.1' => true
        ),
        'ifx.blobinfile' => array(
            '5.2.1' => true
        ),
        'ifx.byteasvarchar' => array(
            '5.2.1' => true
        ),
        'ifx.charasvarchar' => array(
            '5.2.1' => true
        ),
        'ifx.default_host' => array(
            '5.2.1' => true
        ),
        'ifx.default_password' => array(
            '5.2.1' => true
        ),
        'ifx.default_user' => array(
            '5.2.1' => true
        ),
        'ifx.max_links' => array(
            '5.2.1' => true
        ),
        'ifx.max_persistent' => array(
            '5.2.1' => true
        ),
        'ifx.nullformat' => array(
            '5.2.1' => true
        ),
        'ifx.textasvarchar' => array(
            '5.2.1' => true
        ),

        'zend.ze1_compatibility_mode' => array(
            '5.3' => true,
        ),

        'allow_call_time_pass_reference' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'define_syslog_variables' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'detect_unicode' => array(
            '5.4'         => true,
            'alternative' => 'zend.detect_unicode',
        ),
        'highlight.bg' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'magic_quotes_gpc' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'magic_quotes_runtime' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'magic_quotes_sybase' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'mbstring.script_encoding' => array(
            '5.4'         => true,
            'alternative' => 'zend.script_encoding',
        ),
        'register_globals' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'register_long_arrays' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'safe_mode' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'safe_mode_allowed_env_vars' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'safe_mode_exec_dir' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'safe_mode_gid' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'safe_mode_include_dir' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'safe_mode_protected_env_vars' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'session.bug_compat_42' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'session.bug_compat_warn' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'y2k_compliance' => array(
            '5.3' => false,
            '5.4' => true
        ),

        'always_populate_raw_post_data' => array(
            '5.6' => false,
            '7.0' => true
        ),
        'iconv.input_encoding' => array(
            '5.6' => false
        ),
        'iconv.output_encoding' => array(
            '5.6' => false
        ),
        'iconv.internal_encoding' => array(
            '5.6' => false
        ),
        'mbstring.http_input' => array(
            '5.6' => false
        ),
        'mbstring.http_output' => array(
            '5.6' => false
        ),
        'mbstring.internal_encoding' => array(
            '5.6' => false
        ),

        'asp_tags' => array(
            '7.0' => true
        ),
        'xsl.security_prefs' => array(
            '7.0' => true
        ),

        'mcrypt.algorithms_dir' => array(
            '7.1' => false
        ),
        'mcrypt.modes_dir' => array(
            '7.1' => false
        ),
        'session.entropy_file' => array(
            '7.1' => true
        ),
        'session.entropy_length' => array(
            '7.1' => true
        ),
        'session.hash_function' => array(
            '7.1' => true
        ),
        'session.hash_bits_per_character' => array(
            '7.1' => true
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

        $functionLc = strtolower($tokens[$stackPtr]['content']);
        if (isset($this->iniFunctions[$functionLc]) === false) {
            return;
        }

        $iniToken = $this->getFunctionCallParameter($phpcsFile, $stackPtr, $this->iniFunctions[$functionLc]);
        if ($iniToken === false) {
            return;
        }

        $filteredToken = $this->stripQuotes($iniToken['raw']);
        if (isset($this->deprecatedIniDirectives[$filteredToken]) === false) {
            return;
        }

        $errorInfo = $this->getErrorInfo($filteredToken, $functionLc);

        if ($errorInfo['deprecated'] !== '' || $errorInfo['removed'] !== '') {
            $this->addError($phpcsFile, $iniToken['end'], $filteredToken, $errorInfo);
        }

    }//end process()


    /**
     * Retrieve the relevant (version) information for the error message.
     *
     * @param string $iniDirective The name of the ini directive.
     * @param string $functionLc   The lowercase name of the function used with the ini directive.
     *
     * @return array
     */
    protected function getErrorInfo($iniDirective, $functionLc)
    {
        $errorInfo  = array(
            'deprecated'  => '',
            'removed'     => '',
            'alternative' => '',
            'error'       => false,
        );

        foreach ($this->deprecatedIniDirectives[$iniDirective] as $version => $removed) {
            if ($version !== 'alternative' && $this->supportsAbove($version)) {
                if ($removed === true && $errorInfo['removed'] === '') {
                    $errorInfo['removed'] = $version;
                    $errorInfo['error']   = ($functionLc !== 'ini_get') ? true : false;
                } elseif($errorInfo['deprecated'] === '') {
                    $errorInfo['deprecated'] = $version;
                }
            }
        }

        if (isset($this->deprecatedIniDirectives[$iniDirective]['alternative'])) {
            $errorInfo['alternative'] = $this->deprecatedIniDirectives[$iniDirective]['alternative'];
        }

        return $errorInfo;

    }//end getErrorInfo()


    /**
     * Generates the error or warning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile    The file being scanned.
     * @param int                  $stackPtr     The position of the directive
     *                                           in the token array.
     * @param string               $iniDirective The name of the ini directive.
     * @param array                $errorInfo    Array with details about the versions
     *                                           in which the ini directive was deprecated
     *                                           and/or removed.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $iniDirective, $errorInfo)
    {
        $error     = "INI directive '%s' is ";
        $errorCode = $this->stringToErrorCode($iniDirective) . 'Found';
        $data      = array($iniDirective);

        if($errorInfo['deprecated'] !== '') {
            $error .= 'deprecated since PHP %s and ';
            $data[] = $errorInfo['deprecated'];
        }
        if($errorInfo['removed'] !== '') {
            $error .= 'removed since PHP %s and ';
            $data[] = $errorInfo['removed'];
        }

        // Remove the last 'and' from the message.
        $error     = substr($error, 0, strlen($error) - 5) . '.';

        if ($errorInfo['alternative'] !== '') {
            $error .= " Use '%s' instead.";
            $data[] = $errorInfo['alternative'];
        }

        $this->addMessage($phpcsFile, $error, $stackPtr, $errorInfo['error'], $errorCode, $data);

    }//end addError()

}//end class
