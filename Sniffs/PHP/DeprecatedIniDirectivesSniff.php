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
class PHPCompatibility_Sniffs_PHP_DeprecatedIniDirectivesSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * A list of deprecated INI directives
     *
     * @var array(string)
     */
    protected $deprecatedIniDirectives = array(
        'define_syslog_variables' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'register_globals' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'register_long_arrays' => array(
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
        'allow_call_time_pass_reference' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'highlight.bg' => array(
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
        'zend.ze1_compatibility_mode' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'safe_mode' => array(
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
        'safe_mode_exec_dir' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'safe_mode_allowed_env_vars' => array(
            '5.3' => false,
            '5.4' => true
        ),
        'safe_mode_protected_env_vars' => array(
            '5.3' => false,
            '5.4' => true
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
        if (in_array(str_replace("'", "", $tokens[$iniToken]['content']), array_keys($this->deprecatedIniDirectives)) === false) {
            return;
        }

        $error = '';

        foreach ($this->deprecatedIniDirectives[str_replace("'", "", $tokens[$iniToken]['content'])] as $version => $forbidden)
        {
            if (
                is_null(PHP_CodeSniffer::getConfigData('testVersion'))
                ||
                (
                    !is_null(PHP_CodeSniffer::getConfigData('testVersion'))
                    &&
                    version_compare(PHP_CodeSniffer::getConfigData('testVersion'), $version) >= 0
                )
            ) {
                if ($forbidden === true) {
                    $error .= " forbidden";
                } else {
                    $error .= " deprecated";
                }
                $error .= " in PHP " . $version . " and";
            }
        }

        if (strlen($error) > 0) {
            $error = "INI directive " . $tokens[$iniToken]['content'] . " is" . $error;
            $error = substr($error, 0, strlen($error) - 4) . ".";

            $phpcsFile->addWarning($error, $stackPtr);
        }

    }//end process()

}//end class
