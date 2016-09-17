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
     * version => false means the directive was deprecated in that version.
     * version => true means it was removed in that version.
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
        )
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

        $isError = false;

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
        if ($function !== 'ini_get' && $function !== 'ini_set') {
            return;
        }

        $iniToken = $this->getFunctionCallParameter($phpcsFile, $stackPtr, 1);
        if ($iniToken === false) {
            return;
        }

        $filteredToken = $this->stripQuotes($iniToken['raw']);
        if (isset($this->deprecatedIniDirectives[$filteredToken]) === false) {
            return;
        }

        $error = '';

        foreach ($this->deprecatedIniDirectives[$filteredToken] as $version => $forbidden)
        {
            if ($version !== 'alternative') {
                if ($this->supportsAbove($version)) {
                    if ($forbidden === true) {
                        $isError = ($function !== 'ini_get') ? true : false;
                        $error .= " forbidden";
                    } else {
                        $isError = false;
                        $error .= " deprecated";
                    }
                    $error .= " from PHP " . $version . " and";
                }
            }
        }

        if (strlen($error) > 0) {
            $error = "INI directive '" . $filteredToken . "' is" . $error;
            $error = substr($error, 0, strlen($error) - 4) . ".";
            if (isset($this->deprecatedIniDirectives[$filteredToken]['alternative'])) {
                $error .= " Use '" . $this->deprecatedIniDirectives[$filteredToken]['alternative'] . "' instead.";
            }

            if ($isError === true) {
                $phpcsFile->addError($error, $iniToken['end']);
            } else {
                $phpcsFile->addWarning($error, $iniToken['end']);
            }
        }

    }//end process()

}//end class
