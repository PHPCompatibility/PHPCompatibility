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

        $this->assertWarning($file, 20, "INI directive 'max_input_nesting_level' is not available before version 5.2.3");
        $this->assertWarning($file, 21, "INI directive 'max_input_nesting_level' is not available before version 5.2.3");

        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.2');

        $this->assertWarning($file, 20, "INI directive 'max_input_nesting_level' is not available before version 5.2.3");
        $this->assertWarning($file, 21, "INI directive 'max_input_nesting_level' is not available before version 5.2.3");
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
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', '5.2');

        $this->assertWarning($file, 47, "INI directive 'max_input_vars' is not available before version 5.3.9");
        $this->assertWarning($file, 48, "INI directive 'max_input_vars' is not available before version 5.3.9");
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


    /**
     * testNewIniDirectives
     *
     * @dataProvider dataNewIniDirectives
     *
     * @return void
     */
    public function testNewIniDirectives($iniName, $fromVersion, $lines, $warningVersion, $okVersion)
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', $warningVersion);
        foreach($lines as $line) {
            $this->assertWarning($file, $line, "INI directive '{$iniName}' is not available before version $fromVersion");
		}

        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', $okVersion);
        foreach( $lines as $line ) {
            $this->assertNoViolation($file, $line);
		}
    }

    public function dataNewIniDirectives() {
        return array(
            array('auto_globals_jit', '5.0', array(83, 84), '4.4', '5.1'),
            array('com.code_page', '5.0', array(86, 87), '4.4', '5.1'),
            array('date.default_latitude', '5.0', array(89, 90), '4.4', '5.1'),
            array('date.default_longitude', '5.0', array(92, 93), '4.4', '5.1'),
            array('date.sunrise_zenith', '5.0', array(95, 96), '4.4', '5.1'),
            array('date.sunset_zenith', '5.0', array(98, 99), '4.4', '5.1'),
            array('ibase.default_charset', '5.0', array(101, 102), '4.4', '5.1'),
            array('ibase.default_db', '5.0', array(104, 105), '4.4', '5.1'),
            array('mail.force_extra_parameters', '5.0', array(107, 108), '4.4', '5.1'),
            array('mime_magic.debug', '5.0', array(110, 111), '4.4', '5.1'),
            array('mysqli.max_links', '5.0', array(113, 114), '4.4', '5.1'),
            array('mysqli.default_port', '5.0', array(116, 117), '4.4', '5.1'),
            array('mysqli.default_socket', '5.0', array(119, 120), '4.4', '5.1'),
            array('mysqli.default_host', '5.0', array(122, 123), '4.4', '5.1'),
            array('mysqli.default_user', '5.0', array(125, 126), '4.4', '5.1'),
            array('mysqli.default_pw', '5.0', array(128, 129), '4.4', '5.1'),
            array('report_zend_debug', '5.0', array(131, 132), '4.4', '5.1'),
            array('session.hash_bits_per_character', '5.0', array(134, 135), '4.4', '5.1'),
            array('session.hash_function', '5.0', array(137, 138), '4.4', '5.1'),
            array('soap.wsdl_cache_dir', '5.0', array(140, 141), '4.4', '5.1'),
            array('soap.wsdl_cache_enabled', '5.0', array(143, 144), '4.4', '5.1'),
            array('soap.wsdl_cache_ttl', '5.0', array(146, 147), '4.4', '5.1'),
            array('sqlite.assoc_case', '5.0', array(149, 150), '4.4', '5.1'),
            array('tidy.clean_output', '5.0', array(152, 153), '4.4', '5.1'),
            array('tidy.default_config', '5.0', array(155, 156), '4.4', '5.1'),
            array('zend.ze1_compatibility_mode', '5.0', array(158, 159), '4.4', '5.1'),

            array('date.timezone', '5.1', array(161, 162), '5.0', '5.2'),
            array('detect_unicode', '5.1', array(164, 165), '5.0', '5.2'),
            array('realpath_cache_size', '5.1', array(170, 171), '5.0', '5.2'),
            array('realpath_cache_ttl', '5.1', array(173, 174), '5.0', '5.2'),
            
            array('mbstring.strict_detection', '5.1.2', array(176, 177), '5.1', '5.2'),
            array('mssql.charset', '5.1.2', array(179, 180), '5.1', '5.2'),
            
            array('gd.jpeg_ignore_warning', '5.1.3', array(182, 183), '5.1', '5.2'),
            
            array('fbsql.show_timestamp_decimals', '5.1.5', array(185, 186), '5.1', '5.2'),
            array('soap.wsdl_cache', '5.1.5', array(188, 189), '5.1', '5.2'),
            array('soap.wsdl_cache_limit', '5.1.5', array(191, 192), '5.1', '5.2'),
            
            array('filter.default', '5.2', array(194, 195), '5.1', '5.3'),
            array('filter.default_flags', '5.2', array(197, 198), '5.1', '5.3'),
            
            array('cgi.check_shebang_line', '5.2.1', array(200, 201), '5.2', '5.3'),

            array('mysqli.allow_local_infile', '5.2.4', array(203, 204), '5.2', '5.3'),

            array('max_file_uploads', '5.2.12', array(206, 207), '5.2', '5.3'),
            
            array('cgi.discard_path', '5.3', array(209, 210), '5.2', '5.4'),
            array('intl.default_locale', '5.3', array(212, 213), '5.2', '5.4'),
            array('intl.error_level', '5.3', array(215, 216), '5.2', '5.4'),
            array('mail.add_x_header', '5.3', array(218, 219), '5.2', '5.4'),
            array('mail.log', '5.3', array(221, 222), '5.2', '5.4'),
            array('mysqli.allow_persistent', '5.3', array(224, 225), '5.2', '5.4'),
            array('mysqli.max_persistent', '5.3', array(227, 228), '5.2', '5.4'),
            array('mysqli.cache_size', '5.3', array(230, 231), '5.2', '5.4'),
            array('mysqlnd.collect_memory_statistics', '5.3', array(233, 234), '5.2', '5.4'),
            array('mysqlnd.collect_statistics', '5.3', array(236, 237), '5.2', '5.4'),
            array('mysqlnd.debug', '5.3', array(239, 240), '5.2', '5.4'),
            array('mysqlnd.net_read_buffer_size', '5.3', array(242, 243), '5.2', '5.4'),
            array('odbc.default_cursortype', '5.3', array(245, 246), '5.2', '5.4'),
            array('zend.enable_gc', '5.3', array(248, 249), '5.2', '5.4'),
            
            array('curl.cainfo', '5.3.7', array(251, 252), '5.3', '5.4'),

            array('sqlite3.extension_dir', '5.3.11', array(254, 255), '5.3', '5.4'),

            array('session.upload_progress.prefix', '5.4', array(257, 258), '5.3', '5.5'),
            array('mysqlnd.log_mask', '5.4', array(263, 264), '5.3', '5.5'),
            array('mysqlnd.mempool_default_size', '5.4', array(266, 267), '5.3', '5.5'),
            array('mysqlnd.net_cmd_buffer_size', '5.4', array(269, 270), '5.3', '5.5'),
            array('mysqlnd.net_read_timeout', '5.4', array(272, 273), '5.3', '5.5'),
            array('phar.cache_list', '5.4', array(275, 276), '5.3', '5.5'),

            array('mysqlnd.trace_alloc', '5.5', array(278, 279), '5.4', '5.6'),
            array('sys_temp_dir', '5.5', array(281, 282), '5.4', '5.6'),
            array('xsl.security_prefs', '5.5', array(284, 285), '5.4', '5.6'),
            
            array('session.use_strict_mode', '5.5.2', array(287, 288), '5.5', '5.6'),

            array('mysqli.rollback_on_cached_plink', '5.6', array(290, 291), '5.5', '7.0'),

            array('assert.exception', '7.0', array(293, 294), '5.6', '7.1'),
            array('pcre.jit', '7.0', array(296, 297), '5.6', '7.1'),
            array('session.lazy_write', '7.0', array(299, 300), '5.6', '7.1'),
            array('zend.assertions', '7.0', array(302, 303), '5.6', '7.1'),

        );
	}


    /**
     * testNewIniDirectivesWithAlternative
     *
     * @dataProvider dataNewIniDirectivesWithAlternative
     *
     * @return void
     */
    public function testNewIniDirectivesWithAlternative($iniName, $fromVersion, $alternative, $lines, $warningVersion, $okVersion)
    {
        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', $warningVersion);
        foreach($lines as $line) {
            $this->assertWarning($file, $line, "INI directive '{$iniName}' is not available before version $fromVersion");
		}

        $file = $this->sniffFile('sniff-examples/new_ini_directives.php', $okVersion);
        foreach($lines as $line) {
            $this->assertNoViolation($file, $line);
		}
    }

    public function dataNewIniDirectivesWithAlternative() {
        return array(
            array('fbsql.batchsize', '5.1', 'fbsql.batchSize', array(167, 168), '5.0', '5.2'),
            array('zend.detect_unicode', '5.4', 'detect_unicode', array(260, 261), '5.3', '5.5'),
        );
	}

}
