<?php
/**
 * New ini directives sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New ini directives sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class NewIniDirectivesSniffTest extends BaseSniffTest
{
    /**
     * Test functions that shouldnt be flagged by this sniff
     *
     * @return void
     */
    public function testFunctionThatShouldntBeFlagged()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.1');

        $this->assertNoViolation($file, 3);
        $this->assertNoViolation($file, 4);
        $this->assertNoViolation($file, 5);
    }

    /**
     * Test allow_url_include
     *
     * @return void
     */
    public function testAllowUrlInclude()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.1');

        $this->assertWarning($file, 7, "INI directive 'allow_url_include' is not available before version 5.2");
        $this->assertWarning($file, 8, "INI directive 'allow_url_include' is not available before version 5.2");

        // Line 9 tests using double quotes
        $this->assertWarning($file, 9, "INI directive 'allow_url_include' is not available before version 5.2");
    }

    /**
     * testPcreBacktracLimit
     *
     * @return void
     */
    public function testPcreBacktrackLimit()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.1');

        $this->assertWarning($file, 11, "INI directive 'pcre.backtrack_limit' is not available before version 5.2");
        $this->assertWarning($file, 12, "INI directive 'pcre.backtrack_limit' is not available before version 5.2");
    }

    /**
     * testPcreRecursionLimit
     *
     * @return void
     */
    public function testPcreRecursionLimit()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.1');

        $this->assertWarning($file, 14, "INI directive 'pcre.recursion_limit' is not available before version 5.2");
        $this->assertWarning($file, 15, "INI directive 'pcre.recursion_limit' is not available before version 5.2");
    }

    /**
     * testSessionCookieHttpOnly
     *
     * @return void
     */
    public function testSessionCookieHttpOnly()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.1');

        $this->assertWarning($file, 17, "INI directive 'session.cookie_httponly' is not available before version 5.2");
        $this->assertWarning($file, 18, "INI directive 'session.cookie_httponly' is not available before version 5.2");
    }

    /**
     * testMaxInputNestingLevel
     *
     * @return void
     */
    public function testMaxInputNestingLevel()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.1');

        $this->assertWarning($file, 20, "INI directive 'max_input_nesting_level' is not available before version 5.2.2");
        $this->assertWarning($file, 21, "INI directive 'max_input_nesting_level' is not available before version 5.2.2");

        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.2');

        $this->assertWarning($file, 20, "INI directive 'max_input_nesting_level' is not available before version 5.2.2");
        $this->assertWarning($file, 21, "INI directive 'max_input_nesting_level' is not available before version 5.2.2");
    }

    /**
     * testUserIniFilename
     *
     * @return void
     */
    public function testUserIniFilename()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.2');

        $this->assertWarning($file, 23, "INI directive 'user_ini.filename' is not available before version 5.3");
        $this->assertWarning($file, 24, "INI directive 'user_ini.filename' is not available before version 5.3");
    }

    /**
     * testUserInitCacheTtl
     *
     * @return void
     */
    public function testUserInitCacheTtl()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.2');

        $this->assertWarning($file, 26, "INI directive 'user_ini.cache_ttl' is not available before version 5.3");
        $this->assertWarning($file, 27, "INI directive 'user_ini.cache_ttl' is not available before version 5.3");
    }

    /**
     * testExitOnTimeout
     *
     * @return void
     */
    public function testExitOnTimeout()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.2');

        $this->assertWarning($file, 29, "INI directive 'exit_on_timeout' is not available before version 5.3");
        $this->assertWarning($file, 30, "INI directive 'exit_on_timeout' is not available before version 5.3");
    }

    /**
     * testMbstringHttpOutputConvMimetype
     *
     * @return void
     */
    public function testMbstringHttpOutputConvMimetype()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.2');

        $this->assertWarning($file, 32, "INI directive 'mbstring.http_output_conv_mimetype' is not available before version 5.3");
        $this->assertWarning($file, 33, "INI directive 'mbstring.http_output_conv_mimetype' is not available before version 5.3");
    }

    /**
     * testRequestOrder
     *
     * @return void
     */
    public function testRequestOrder()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.2');

        $this->assertWarning($file, 35, "INI directive 'request_order' is not available before version 5.3");
        $this->assertWarning($file, 36, "INI directive 'request_order' is not available before version 5.3");
    }

    /**
     * testCliPager
     *
     * @return void
     */
    public function testCliPager()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.3');

        $this->assertWarning($file, 38, "INI directive 'cli.pager' is not available before version 5.4");
        $this->assertWarning($file, 39, "INI directive 'cli.pager' is not available before version 5.4");
    }

    /**
     * testCliPrompt
     *
     * @return void
     */
    public function testCliPrompt()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.3');

        $this->assertWarning($file, 41, "INI directive 'cli.prompt' is not available before version 5.4");
        $this->assertWarning($file, 42, "INI directive 'cli.prompt' is not available before version 5.4");
    }

    /**
     * testCliServerColor
     *
     * @return void
     */
    public function testCliServerColor()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.3');

        $this->assertWarning($file, 44, "INI directive 'cli_server.color' is not available before version 5.4");
        $this->assertWarning($file, 45, "INI directive 'cli_server.color' is not available before version 5.4");
    }

    /**
     * testMaxInputVars
     *
     * @return void
     */
    public function testMaxInputVars()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.3');

        $this->assertWarning($file, 47, "INI directive 'max_input_vars' is not available before version 5.4");
        $this->assertWarning($file, 48, "INI directive 'max_input_vars' is not available before version 5.4");
    }

    /**
     * testZendMultibyte
     *
     * @return void
     */
    public function testZendMultibyte()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.3');

        $this->assertWarning($file, 50, "INI directive 'zend.multibyte' is not available before version 5.4");
        $this->assertWarning($file, 51, "INI directive 'zend.multibyte' is not available before version 5.4");
    }

    /**
     * testZendScriptEncoding
     *
     * @return void
     */
    public function testZendScriptEncoding()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.3');

        $this->assertWarning($file, 53, "INI directive 'zend.script_encoding' is not available before version 5.4");
        $this->assertWarning($file, 54, "INI directive 'zend.script_encoding' is not available before version 5.4");
    }

    /**
     * testZendSignalCheck
     *
     * @return void
     */
    public function testZendSignalCheck()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.3');

        $this->assertWarning($file, 56, "INI directive 'zend.signal_check' is not available before version 5.4");
        $this->assertWarning($file, 57, "INI directive 'zend.signal_check' is not available before version 5.4");
    }

    /**
     * testSessionUploadProgressEnabled
     *
     * @return void
     */
    public function testSessionUploadProgressEnabled()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.3');

        $this->assertWarning($file, 59, "INI directive 'session.upload_progress.enabled' is not available before version 5.4");
        $this->assertWarning($file, 60, "INI directive 'session.upload_progress.enabled' is not available before version 5.4");
    }

    /**
     * testSessionUploadProgressCleanup
     *
     * @return void
     */
    public function testSessionUploadProgressCleanup()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.3');

        $this->assertWarning($file, 62, "INI directive 'session.upload_progress.cleanup' is not available before version 5.4");
        $this->assertWarning($file, 63, "INI directive 'session.upload_progress.cleanup' is not available before version 5.4");
    }

    /**
     * testSessionUploadProgressName
     *
     * @return void
     */
    public function testSessionUploadProgressName()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.3');

        $this->assertWarning($file, 65, "INI directive 'session.upload_progress.name' is not available before version 5.4");
        $this->assertWarning($file, 66, "INI directive 'session.upload_progress.name' is not available before version 5.4");
    }

    /**
     * testSessionUploadProgressFreq
     *
     * @return void
     */
    public function testSessionUploadProgressFreq()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.3');

        $this->assertWarning($file, 68, "INI directive 'session.upload_progress.freq' is not available before version 5.4");
        $this->assertWarning($file, 69, "INI directive 'session.upload_progress.freq' is not available before version 5.4");
    }

    /**
     * testEnablePostDataReading
     *
     * @return void
     */
    public function testEnablePostDataReading()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.3');

        $this->assertWarning($file, 71, "INI directive 'enable_post_data_reading' is not available before version 5.4");
        $this->assertWarning($file, 72, "INI directive 'enable_post_data_reading' is not available before version 5.4");
    }

    /**
     * testWindowsShowCrtWarning
     *
     * @return void
     */
    public function testWindowsShowCrtWarning()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.3');

        $this->assertWarning($file, 74, "INI directive 'windows_show_crt_warning' is not available before version 5.4");
        $this->assertWarning($file, 75, "INI directive 'windows_show_crt_warning' is not available before version 5.4");
    }

    /**
     * testIntlUseExceptions
     *
     * @return void
     */
    public function testIntlUseExceptions()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.4');

        $this->assertWarning($file, 77, "INI directive 'intl.use_exceptions' is not available before version 5.5");
        $this->assertWarning($file, 78, "INI directive 'intl.use_exceptions' is not available before version 5.5");
    }

    /**
     * testMysqlndSha256ServerPublicKey
     *
     * @return void
     */
    public function testMysqlndSha256ServerPublicKey()
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.4');

        $this->assertWarning($file, 80, "INI directive 'mysqlnd.sha256_server_public_key' is not available before version 5.5");
        $this->assertWarning($file, 81, "INI directive 'mysqlnd.sha256_server_public_key' is not available before version 5.5");
    }
}
