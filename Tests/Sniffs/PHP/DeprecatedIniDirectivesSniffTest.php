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

        $this->assertWarning($file, 3, "INI directive 'define_syslog_variables' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 4, "INI directive 'define_syslog_variables' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test register_globals
     *
     * @return void
     */
    public function testRegisterGlobals()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 6, "INI directive 'register_globals' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 7, "INI directive 'register_globals' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test register_long_arrays
     *
     * @return void
     */
    public function testRegisterLongArrays()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 9, "INI directive 'register_long_arrays' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 10, "INI directive 'register_long_arrays' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test magic_quotes_gpc
     *
     * @return void
     */
    public function testMagicQuotesGpc()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 12, "INI directive 'magic_quotes_gpc' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 13, "INI directive 'magic_quotes_gpc' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test magic_quotes_runtime
     *
     * @return void
     */
    public function testMagicQuotesRuntime()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 15, "INI directive 'magic_quotes_runtime' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 16, "INI directive 'magic_quotes_runtime' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test magic_quotes_sybase
     *
     * @return void
     */
    public function testMagicQuotesSybase()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 18, "INI directive 'magic_quotes_sybase' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 19, "INI directive 'magic_quotes_sybase' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test allow_call_time_pass_reference
     *
     * @return void
     */
    public function testAllowCallTimePassReference()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 21, "INI directive 'allow_call_time_pass_reference' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 22, "INI directive 'allow_call_time_pass_reference' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test highlight.bg
     *
     * @return void
     */
    public function testHighlightBg()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 24, "INI directive 'highlight.bg' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 25, "INI directive 'highlight.bg' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test session.bug_compat_42
     *
     * @return void
     */
    public function testSessionBugCompat42()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 27, "INI directive 'session.bug_compat_42' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 28, "INI directive 'session.bug_compat_42' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test session.bug_compat_warn
     *
     * @return void
     */
    public function testSessionBugCompatWarn()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 30, "INI directive 'session.bug_compat_warn' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 31, "INI directive 'session.bug_compat_warn' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test y2k_compliance
     *
     * @return void
     */
    public function testY2kCompliance()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 33, "INI directive 'y2k_compliance' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 34, "INI directive 'y2k_compliance' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test zend.ze1_compatibility_mode
     *
     * @return void
     */
    public function testZendZe1CompatibilityMode()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 36, "INI directive 'zend.ze1_compatibility_mode' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 37, "INI directive 'zend.ze1_compatibility_mode' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test safe_mode
     *
     * @return void
     */
    public function testSafeMode()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 39, "INI directive 'safe_mode' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 40, "INI directive 'safe_mode' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test safe_mode_gid
     *
     * @return void
     */
    public function testSafeModeGid()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 42, "INI directive 'safe_mode_gid' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 43, "INI directive 'safe_mode_gid' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test safe_mode_include_dir
     *
     * @return void
     */
    public function testSafeModeIncludeDir()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 45, "INI directive 'safe_mode_include_dir' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 46, "INI directive 'safe_mode_include_dir' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test safe_mode_exec_dir
     *
     * @return void
     */
    public function testSafeModeExecDir()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 48, "INI directive 'safe_mode_exec_dir' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 49, "INI directive 'safe_mode_exec_dir' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test safe_mode_allowed_env_vars
     *
     * @return void
     */
    public function testSafeModeAllowedEnvVars()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 51, "INI directive 'safe_mode_allowed_env_vars' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 52, "INI directive 'safe_mode_allowed_env_vars' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
    }

    /**
     * Test safe_mode_protected_env_vars
     *
     * @return void
     */
    public function testSafeModeProtectedEnvVars()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php');

        $this->assertWarning($file, 54, "INI directive 'safe_mode_protected_env_vars' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
        $this->assertWarning($file, 55, "INI directive 'safe_mode_protected_env_vars' is deprecated in PHP 5.3 and forbidden in PHP 5.4");
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

    public function testSettingTestVersion()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_ini_directives.php', '5.3');

        $this->assertWarning($file, 54, "INI directive 'safe_mode_protected_env_vars' is deprecated in PHP 5.3");
    }
}
