<?php
/**
 * New ini directives sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New ini directives sniff tests
 *
 * @group newIniDirectives
 * @group iniDirectives
 *
 * @covers PHPCompatibility_Sniffs_PHP_NewIniDirectivesSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class NewIniDirectivesSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/new_ini_directives.php';

    /**
     * testNewIniDirectives
     *
     * @dataProvider dataNewIniDirectives
     *
     * @param string $iniName           Name of the ini directive.
     * @param string $okVersion         A PHP version in which the ini directive was present.
     * @param array  $lines             The line numbers in the test file which apply to this ini directive.
     * @param string $lastVersionBefore The last PHP version in which the ini directive was not present.
     * @param string $testVersion       Optional PHP version to test error/warning message with -
     *                                  if different from the $lastVersionBeforeversion.
     *
     * @return void
     */
    public function testNewIniDirectives($iniName, $okVersion, $lines, $lastVersionBefore, $testVersion = null)
    {
        if (isset($testVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $testVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $lastVersionBefore);
        }

        $error        = "INI directive '{$iniName}' is not present in PHP version {$lastVersionBefore} or earlier";
        $this->assertError($file, $lines[0], $error);
        $this->assertWarning($file, $lines[1], $error);

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach( $lines as $line ) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testNewIniDirectives()
     *
     * @return array
     */
    public function dataNewIniDirectives()
    {
        return array(
            array('auto_globals_jit', '5.0', array(83, 84), '4.4'),
            array('com.code_page', '5.0', array(86, 87), '4.4'),
            array('date.default_latitude', '5.0', array(89, 90), '4.4'),
            array('date.default_longitude', '5.0', array(92, 93), '4.4'),
            array('date.sunrise_zenith', '5.0', array(95, 96), '4.4'),
            array('date.sunset_zenith', '5.0', array(98, 99), '4.4'),
            array('ibase.default_charset', '5.0', array(101, 102), '4.4'),
            array('ibase.default_db', '5.0', array(104, 105), '4.4'),
            array('mail.force_extra_parameters', '5.0', array(107, 108), '4.4'),
            array('mime_magic.debug', '5.0', array(110, 111), '4.4'),
            array('mysqli.max_links', '5.0', array(113, 114), '4.4'),
            array('mysqli.default_port', '5.0', array(116, 117), '4.4'),
            array('mysqli.default_socket', '5.0', array(119, 120), '4.4'),
            array('mysqli.default_host', '5.0', array(122, 123), '4.4'),
            array('mysqli.default_user', '5.0', array(125, 126), '4.4'),
            array('mysqli.default_pw', '5.0', array(128, 129), '4.4'),
            array('report_zend_debug', '5.0', array(131, 132), '4.4'),
            array('session.hash_bits_per_character', '5.0', array(134, 135), '4.4'),
            array('session.hash_function', '5.0', array(137, 138), '4.4'),
            array('soap.wsdl_cache_dir', '5.0', array(140, 141), '4.4'),
            array('soap.wsdl_cache_enabled', '5.0', array(143, 144), '4.4'),
            array('soap.wsdl_cache_ttl', '5.0', array(146, 147), '4.4'),
            array('sqlite.assoc_case', '5.0', array(149, 150), '4.4'),
            array('tidy.clean_output', '5.0', array(152, 153), '4.4'),
            array('tidy.default_config', '5.0', array(155, 156), '4.4'),
            array('zend.ze1_compatibility_mode', '5.0', array(158, 159), '4.4'),

            array('date.timezone', '5.1', array(161, 162), '5.0'),
            array('detect_unicode', '5.1', array(164, 165), '5.0'),
            array('realpath_cache_size', '5.1', array(170, 171), '5.0'),
            array('realpath_cache_ttl', '5.1', array(173, 174), '5.0'),

            array('mbstring.strict_detection', '5.2', array(176, 177), '5.1.1', '5.1'),
            array('mssql.charset', '5.2', array(179, 180), '5.1.1', '5.1'),

            array('gd.jpeg_ignore_warning', '5.2', array(182, 183), '5.1.2', '5.1'),

            array('fbsql.show_timestamp_decimals', '5.2', array(185, 186), '5.1.4', '5.1'),
            array('soap.wsdl_cache', '5.2', array(188, 189), '5.1.4', '5.1'),
            array('soap.wsdl_cache_limit', '5.2', array(191, 192), '5.1.4', '5.1'),

            array('allow_url_include', '5.2', array(7, 8), '5.1'),
            array('pcre.backtrack_limit', '5.2', array(11, 12), '5.1'),
            array('pcre.recursion_limit', '5.2', array(14, 15), '5.1'),
            array('session.cookie_httponly', '5.2', array(17, 18), '5.1'),
            array('filter.default', '5.2', array(194, 195), '5.1'),
            array('filter.default_flags', '5.2', array(197, 198), '5.1'),

            array('cgi.check_shebang_line', '5.3', array(200, 201), '5.2.0', '5.2'),

            array('max_input_nesting_level', '5.3', array(20, 21), '5.2.2', '5.2'),

            array('mysqli.allow_local_infile', '5.3', array(203, 204), '5.2.3', '5.2'),

            array('max_file_uploads', '5.3', array(206, 207), '5.2.11', '5.2'),

            array('user_ini.filename', '5.3', array(23, 24), '5.2'),
            array('user_ini.cache_ttl', '5.3', array(26, 27), '5.2'),
            array('exit_on_timeout', '5.3', array(29, 30), '5.2'),
            array('mbstring.http_output_conv_mimetype', '5.3', array(32, 33), '5.2'),
            array('request_order', '5.3', array(35, 36), '5.2'),
            array('cgi.discard_path', '5.3', array(209, 210), '5.2'),
            array('intl.default_locale', '5.3', array(212, 213), '5.2'),
            array('intl.error_level', '5.3', array(215, 216), '5.2'),
            array('mail.add_x_header', '5.3', array(218, 219), '5.2'),
            array('mail.log', '5.3', array(221, 222), '5.2'),
            array('mysqli.allow_persistent', '5.3', array(224, 225), '5.2'),
            array('mysqli.max_persistent', '5.3', array(227, 228), '5.2'),
            array('mysqli.cache_size', '5.3', array(230, 231), '5.2'),
            array('mysqlnd.collect_memory_statistics', '5.3', array(233, 234), '5.2'),
            array('mysqlnd.collect_statistics', '5.3', array(236, 237), '5.2'),
            array('mysqlnd.debug', '5.3', array(239, 240), '5.2'),
            array('mysqlnd.net_read_buffer_size', '5.3', array(242, 243), '5.2'),
            array('odbc.default_cursortype', '5.3', array(245, 246), '5.2'),
            array('zend.enable_gc', '5.3', array(248, 249), '5.2'),

            array('curl.cainfo', '5.4', array(251, 252), '5.3.6', '5.3'),

            array('max_input_vars', '5.4', array(47, 48), '5.3.8', '5.3'),

            array('sqlite3.extension_dir', '5.4', array(254, 255), '5.3.10', '5.3'),

            array('cli.pager', '5.4', array(38, 39), '5.3'),
            array('cli.prompt', '5.4', array(41, 42), '5.3'),
            array('cli_server.color', '5.4', array(44, 45), '5.3'),
            array('zend.multibyte', '5.4', array(50, 51), '5.3'),
            array('zend.script_encoding', '5.4', array(53, 54), '5.3'),
            array('zend.signal_check', '5.4', array(56, 57), '5.3'),
            array('session.upload_progress.enabled', '5.4', array(59, 60), '5.3'),
            array('session.upload_progress.cleanup', '5.4', array(62, 63), '5.3'),
            array('session.upload_progress.name', '5.4', array(65, 66), '5.3'),
            array('session.upload_progress.freq', '5.4', array(68, 69), '5.3'),
            array('enable_post_data_reading', '5.4', array(71, 72), '5.3'),
            array('windows_show_crt_warning', '5.4', array(74, 75), '5.3'),
            array('session.upload_progress.prefix', '5.4', array(257, 258), '5.3'),
            array('mysqlnd.log_mask', '5.4', array(263, 264), '5.3'),
            array('mysqlnd.mempool_default_size', '5.4', array(266, 267), '5.3'),
            array('mysqlnd.net_cmd_buffer_size', '5.4', array(269, 270), '5.3'),
            array('mysqlnd.net_read_timeout', '5.4', array(272, 273), '5.3'),
            array('phar.cache_list', '5.4', array(275, 276), '5.3'),

            array('intl.use_exceptions', '5.5', array(77, 78), '5.4'),
            array('mysqlnd.sha256_server_public_key', '5.5', array(80, 81), '5.4'),
            array('mysqlnd.trace_alloc', '5.5', array(278, 279), '5.4'),
            array('sys_temp_dir', '5.5', array(281, 282), '5.4'),
            array('xsl.security_prefs', '5.5', array(284, 285), '5.4'),

            array('session.use_strict_mode', '5.6', array(287, 288), '5.5.1', '5.5'),

            array('mysqli.rollback_on_cached_plink', '5.6', array(290, 291), '5.5'),

            array('assert.exception', '7.0', array(293, 294), '5.6'),
            array('pcre.jit', '7.0', array(296, 297), '5.6'),
            array('session.lazy_write', '7.0', array(299, 300), '5.6'),
            array('zend.assertions', '7.0', array(302, 303), '5.6'),

            array('session.sid_length', '7.1', array(305, 306), '7.0'),
            array('session.sid_bits_per_character', '7.1', array(308, 309), '7.0'),
        );
    }


    /**
     * testNewIniDirectivesWithAlternative
     *
     * @dataProvider dataNewIniDirectivesWithAlternative
     *
     * @param string $iniName           Name of the ini directive.
     * @param string $okVersion         A PHP version in which the ini directive was present.
     * @param string $alternative       An alternative ini directive.
     * @param array  $lines             The line numbers in the test file which apply to this ini directive.
     * @param string $lastVersionBefore The last PHP version in which the ini directive was not present.
     * @param string $testVersion       Optional PHP version to test error/warning message with -
     *                                  if different from the $lastVersionBeforeversion.
     *
     * @return void
     */
    public function testNewIniDirectivesWithAlternative($iniName, $okVersion, $alternative, $lines, $lastVersionBefore, $testVersion = null)
    {
        if (isset($testVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $testVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $lastVersionBefore);
        }
        $error        = "INI directive '{$iniName}' is not present in PHP version {$lastVersionBefore} or earlier. This directive was previously called '{$alternative}'.";
        $this->assertError($file, $lines[0], $error);
        $this->assertWarning($file, $lines[1], $error);

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testNewIniDirectivesWithAlternative()
     *
     * @return array
     */
    public function dataNewIniDirectivesWithAlternative()
    {
        return array(
            array('fbsql.batchsize', '5.1', 'fbsql.batchSize', array(167, 168), '5.0'),
            array('zend.detect_unicode', '5.4', 'detect_unicode', array(260, 261), '5.3'),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '4.4'); // Low version below the first addition.
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return array(
            array(2),
            array(3),
            array(4),
            array(5),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '99.0'); // High version beyond newest addition.
        $this->assertNoViolation($file);
    }

}
