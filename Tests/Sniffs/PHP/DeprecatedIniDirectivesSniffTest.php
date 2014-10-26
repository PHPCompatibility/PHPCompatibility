<?php
/**
 * Deprecated ini directives sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Deprecated ini directives sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class DeprecatedIniDirectivesSniffTest extends BaseSniffTest
{
    /**
     * Test define_syslog_variables
     *
     * @return void
     */
    public function testDefineSyslogVariables()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 3, "INI directive 'define_syslog_variables' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 4, "INI directive 'define_syslog_variables' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test register_globals
     *
     * @return void
     */
    public function testRegisterGlobals()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 6, "INI directive 'register_globals' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 7, "INI directive 'register_globals' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test register_long_arrays
     *
     * @return void
     */
    public function testRegisterLongArrays()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 9, "INI directive 'register_long_arrays' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 10, "INI directive 'register_long_arrays' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test magic_quotes_gpc
     *
     * @return void
     */
    public function testMagicQuotesGpc()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 12, "INI directive 'magic_quotes_gpc' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 13, "INI directive 'magic_quotes_gpc' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test magic_quotes_runtime
     *
     * @return void
     */
    public function testMagicQuotesRuntime()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 15, "INI directive 'magic_quotes_runtime' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 16, "INI directive 'magic_quotes_runtime' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test magic_quotes_sybase
     *
     * @return void
     */
    public function testMagicQuotesSybase()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 18, "INI directive 'magic_quotes_sybase' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 19, "INI directive 'magic_quotes_sybase' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test allow_call_time_pass_reference
     *
     * @return void
     */
    public function testAllowCallTimePassReference()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 21, "INI directive 'allow_call_time_pass_reference' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 22, "INI directive 'allow_call_time_pass_reference' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test highlight.bg
     *
     * @return void
     */
    public function testHighlightBg()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 24, "INI directive 'highlight.bg' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 25, "INI directive 'highlight.bg' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test session.bug_compat_42
     *
     * @return void
     */
    public function testSessionBugCompat42()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 27, "INI directive 'session.bug_compat_42' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 28, "INI directive 'session.bug_compat_42' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test session.bug_compat_warn
     *
     * @return void
     */
    public function testSessionBugCompatWarn()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 30, "INI directive 'session.bug_compat_warn' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 31, "INI directive 'session.bug_compat_warn' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test y2k_compliance
     *
     * @return void
     */
    public function testY2kCompliance()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 33, "INI directive 'y2k_compliance' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 34, "INI directive 'y2k_compliance' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test zend.ze1_compatibility_mode
     *
     * @return void
     */
    public function testZendZe1CompatibilityMode()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 36, "INI directive 'zend.ze1_compatibility_mode' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 37, "INI directive 'zend.ze1_compatibility_mode' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test safe_mode
     *
     * @return void
     */
    public function testSafeMode()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 39, "INI directive 'safe_mode' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 40, "INI directive 'safe_mode' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test safe_mode_gid
     *
     * @return void
     */
    public function testSafeModeGid()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 42, "INI directive 'safe_mode_gid' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 43, "INI directive 'safe_mode_gid' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test safe_mode_include_dir
     *
     * @return void
     */
    public function testSafeModeIncludeDir()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 45, "INI directive 'safe_mode_include_dir' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 46, "INI directive 'safe_mode_include_dir' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test safe_mode_exec_dir
     *
     * @return void
     */
    public function testSafeModeExecDir()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 48, "INI directive 'safe_mode_exec_dir' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 49, "INI directive 'safe_mode_exec_dir' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test safe_mode_allowed_env_vars
     *
     * @return void
     */
    public function testSafeModeAllowedEnvVars()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 51, "INI directive 'safe_mode_allowed_env_vars' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 52, "INI directive 'safe_mode_allowed_env_vars' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test safe_mode_protected_env_vars
     *
     * @return void
     */
    public function testSafeModeProtectedEnvVars()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 54, "INI directive 'safe_mode_protected_env_vars' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
        $this->assertWarning($file, 55, "INI directive 'safe_mode_protected_env_vars' is deprecated from PHP 5.3 and forbidden from PHP 5.4");
    }

    /**
     * Test valid directive
     *
     * @return void
     */
    public function testValidDirective()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertNoViolation($file, 57);
        $this->assertNoViolation($file, 58);
    }

    /**
     * Test always_populate_raw_post_data
     *
     * @return void
     */
    public function testAlwaysPopulateRawPostData()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');
    
        $this->assertWarning($file, 60, "INI directive 'always_populate_raw_post_data' is deprecated from PHP 5.6");
    }

    /**
     * Test iconv.input_encoding setting
     *
     * @return void
     */
    public function testIconvInputEncoding()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');
    
        $this->assertWarning($file, 62, "INI directive 'iconv.input_encoding' is deprecated from PHP 5.6");
        $this->assertWarning($file, 63, "INI directive 'iconv.input_encoding' is deprecated from PHP 5.6");
    }

    /**
     * Test iconv.output_encoding setting
     *
     * @return void
     */
    public function testIconvOutputEncoding()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');
    
        $this->assertWarning($file, 65, "INI directive 'iconv.output_encoding' is deprecated from PHP 5.6");
        $this->assertWarning($file, 66, "INI directive 'iconv.output_encoding' is deprecated from PHP 5.6");
    }
    
    /**
     * Test iconv.internal_encoding setting
     *
     * @return void
     */
    public function testIconvInternalEncoding()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');
    
        $this->assertWarning($file, 68, "INI directive 'iconv.internal_encoding' is deprecated from PHP 5.6");
        $this->assertWarning($file, 69, "INI directive 'iconv.internal_encoding' is deprecated from PHP 5.6");
    }
    
    
    /**
     * Test mbstring.http_input setting
     *
     * @return void
     */
    public function testMbstringHttpInput()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');
    
        $this->assertWarning($file, 71, "INI directive 'mbstring.http_input' is deprecated from PHP 5.6");
        $this->assertWarning($file, 72, "INI directive 'mbstring.http_input' is deprecated from PHP 5.6");
    }
    
    /**
     * Test mbstring.http_output setting
     *
     * @return void
     */
    public function testMbstringHttpOutput()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');
    
        $this->assertWarning($file, 74, "INI directive 'mbstring.http_output' is deprecated from PHP 5.6");
        $this->assertWarning($file, 75, "INI directive 'mbstring.http_output' is deprecated from PHP 5.6");
    }
    
    /**
     * Test mbstring.internal_encoding setting
     *
     * @return void
     */
    public function testMbstringInternalEncoding()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');
    
        $this->assertWarning($file, 77, "INI directive 'mbstring.internal_encoding' is deprecated from PHP 5.6");
        $this->assertWarning($file, 78, "INI directive 'mbstring.internal_encoding' is deprecated from PHP 5.6");
    }
    
    
    
    public function testSettingTestVersion()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php', '5.3');

        $this->assertWarning($file, 54, "INI directive 'safe_mode_protected_env_vars' is deprecated from PHP 5.3");
    }
}
