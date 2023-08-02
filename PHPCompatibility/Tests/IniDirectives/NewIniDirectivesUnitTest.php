<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\IniDirectives;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewIniDirectives sniff.
 *
 * @group newIniDirectives
 * @group iniDirectives
 *
 * @covers \PHPCompatibility\Sniffs\IniDirectives\NewIniDirectivesSniff
 *
 * @since 5.5
 */
class NewIniDirectivesUnitTest extends BaseSniffTestCase
{

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
        $errorVersion = (isset($testVersion)) ? $testVersion : $lastVersionBefore;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "INI directive '{$iniName}' is not present in PHP version {$lastVersionBefore} or earlier";
        $this->assertError($file, $lines[0], $error);
        $this->assertWarning($file, $lines[1], $error);

        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
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
    public static function dataNewIniDirectives()
    {
        return [
            ['auto_globals_jit', '5.0', [83, 84], '4.4'],
            ['com.code_page', '5.0', [86, 87], '4.4'],
            ['date.default_latitude', '5.0', [89, 90], '4.4'],
            ['date.default_longitude', '5.0', [92, 93], '4.4'],
            ['date.sunrise_zenith', '5.0', [95, 96], '4.4'],
            ['date.sunset_zenith', '5.0', [98, 99], '4.4'],
            ['ibase.default_charset', '5.0', [101, 102], '4.4'],
            ['ibase.default_db', '5.0', [104, 105], '4.4'],
            ['mail.force_extra_parameters', '5.0', [107, 108], '4.4'],
            ['mime_magic.debug', '5.0', [110, 111], '4.4'],
            ['mysqli.max_links', '5.0', [113, 114], '4.4'],
            ['mysqli.default_port', '5.0', [116, 117], '4.4'],
            ['mysqli.default_socket', '5.0', [119, 120], '4.4'],
            ['mysqli.default_host', '5.0', [122, 123], '4.4'],
            ['mysqli.default_user', '5.0', [125, 126], '4.4'],
            ['mysqli.default_pw', '5.0', [128, 129], '4.4'],
            ['mysqli.reconnect', '5.0', [494, 495], '4.4'],
            ['report_zend_debug', '5.0', [131, 132], '4.4'],
            ['session.hash_bits_per_character', '5.0', [134, 135], '4.4'],
            ['session.hash_function', '5.0', [137, 138], '4.4'],
            ['soap.wsdl_cache_dir', '5.0', [140, 141], '4.4'],
            ['soap.wsdl_cache_enabled', '5.0', [143, 144], '4.4'],
            ['soap.wsdl_cache_ttl', '5.0', [146, 147], '4.4'],
            ['sqlite.assoc_case', '5.0', [149, 150], '4.4'],
            ['tidy.clean_output', '5.0', [152, 153], '4.4'],
            ['tidy.default_config', '5.0', [155, 156], '4.4'],
            ['zend.ze1_compatibility_mode', '5.0', [158, 159], '4.4'],

            ['date.timezone', '5.1', [161, 162], '5.0'],
            ['detect_unicode', '5.1', [164, 165], '5.0'],
            ['realpath_cache_size', '5.1', [170, 171], '5.0'],
            ['realpath_cache_ttl', '5.1', [173, 174], '5.0'],
            ['pdo_odbc.connection_pooling', '5.1', [488, 489], '5.0'],

            ['pdo_odbc.db2_instance_name', '5.2', [491, 492], '5.1.0', '5.1'],

            ['mbstring.strict_detection', '5.2', [176, 177], '5.1.1', '5.1'],
            ['mssql.charset', '5.2', [179, 180], '5.1.1', '5.1'],
            ['oci8.default_prefetch', '5.2', [503, 504], '5.1.1', '5.1'],
            ['oci8.max_persistent', '5.2', [509, 510], '5.1.1', '5.1'],
            ['oci8.old_oci_close_semantics', '5.2', [512, 513], '5.1.1', '5.1'],
            ['oci8.persistent_timeout', '5.2', [515, 516], '5.1.1', '5.1'],
            ['oci8.ping_interval', '5.2', [518, 519], '5.1.1', '5.1'],
            ['oci8.privileged_connect', '5.2', [521, 522], '5.1.1', '5.1'],
            ['oci8.statement_cache_size', '5.2', [524, 525], '5.1.1', '5.1'],

            ['gd.jpeg_ignore_warning', '5.2', [182, 183], '5.1.2', '5.1'],

            ['fbsql.show_timestamp_decimals', '5.2', [185, 186], '5.1.4', '5.1'],
            ['soap.wsdl_cache', '5.2', [188, 189], '5.1.4', '5.1'],
            ['soap.wsdl_cache_limit', '5.2', [191, 192], '5.1.4', '5.1'],

            ['allow_url_include', '5.2', [8, 9], '5.1'],
            ['pcre.backtrack_limit', '5.2', [11, 12], '5.1'],
            ['pcre.recursion_limit', '5.2', [14, 15], '5.1'],
            ['session.cookie_httponly', '5.2', [17, 18], '5.1'],
            ['filter.default', '5.2', [194, 195], '5.1'],
            ['filter.default_flags', '5.2', [197, 198], '5.1'],

            ['cgi.check_shebang_line', '5.3', [200, 201], '5.2.0', '5.2'],

            ['max_input_nesting_level', '5.3', [20, 21], '5.2.2', '5.2'],

            ['mysqli.allow_local_infile', '5.3', [203, 204], '5.2.3', '5.2'],

            ['max_file_uploads', '5.3', [206, 207], '5.2.11', '5.2'],

            ['user_ini.filename', '5.3', [23, 24], '5.2'],
            ['user_ini.cache_ttl', '5.3', [26, 27], '5.2'],
            ['exit_on_timeout', '5.3', [29, 30], '5.2'],
            ['mbstring.http_output_conv_mimetype', '5.3', [32, 33], '5.2'],
            ['request_order', '5.3', [35, 36], '5.2'],
            ['cgi.discard_path', '5.3', [209, 210], '5.2'],
            ['intl.default_locale', '5.3', [212, 213], '5.2'],
            ['intl.error_level', '5.3', [215, 216], '5.2'],
            ['mail.add_x_header', '5.3', [218, 219], '5.2'],
            ['mail.log', '5.3', [221, 222], '5.2'],
            ['mysqli.allow_persistent', '5.3', [224, 225], '5.2'],
            ['mysqli.max_persistent', '5.3', [227, 228], '5.2'],
            ['mysqlnd.collect_memory_statistics', '5.3', [233, 234], '5.2'],
            ['mysqlnd.collect_statistics', '5.3', [236, 237], '5.2'],
            ['mysqlnd.debug', '5.3', [239, 240], '5.2'],
            ['mysqlnd.net_read_buffer_size', '5.3', [242, 243], '5.2'],
            ['mysqlnd.log_mask', '5.3', [263, 264], '5.2'],
            ['mysqlnd.net_read_timeout', '5.3', [272, 273], '5.2'],
            ['mysqlnd.net_cmd_buffer_size', '5.3', [269, 270], '5.2'],
            ['odbc.default_cursortype', '5.3', [245, 246], '5.2'],
            ['phar.readonly', '5.3', [473, 474], '5.2'],
            ['phar.require_hash', '5.3', [476, 477], '5.2'],
            ['phar.extract_list', '5.3', [479, 480], '5.2'],
            ['zend.enable_gc', '5.3', [248, 249], '5.2'],
            ['oci8.connection_class', '5.3', [500, 501], '5.2'],
            ['oci8.events', '5.3', [506, 507], '5.2'],

            ['mysqlnd.mempool_default_size', '5.4', [266, 267], '5.3.2', '5.3'],

            ['curl.cainfo', '5.4', [251, 252], '5.3.6', '5.3'],

            ['max_input_vars', '5.4', [47, 48], '5.3.8', '5.3'],

            ['sqlite3.extension_dir', '5.4', [254, 255], '5.3.10', '5.3'],

            ['cli.pager', '5.4', [38, 39], '5.3'],
            ['cli.prompt', '5.4', [41, 42], '5.3'],
            ['cli_server.color', '5.4', [44, 45], '5.3'],
            ['zend.multibyte', '5.4', [50, 51], '5.3'],
            ['zend.script_encoding', '5.4', [53, 54], '5.3'],
            ['zend.signal_check', '5.4', [56, 57], '5.3'],
            ['session.upload_progress.enabled', '5.4', [59, 60], '5.3'],
            ['session.upload_progress.cleanup', '5.4', [62, 63], '5.3'],
            ['session.upload_progress.name', '5.4', [65, 66], '5.3'],
            ['session.upload_progress.freq', '5.4', [68, 69], '5.3'],
            ['enable_post_data_reading', '5.4', [71, 72], '5.3'],
            ['windows_show_crt_warning', '5.4', [74, 75], '5.3'],
            ['session.upload_progress.prefix', '5.4', [257, 258], '5.3'],
            ['phar.cache_list', '5.4', [275, 276], '5.3'],

            ['intl.use_exceptions', '5.5', [77, 78], '5.4'],
            ['mysqlnd.sha256_server_public_key', '5.5', [80, 81], '5.4'],
            ['mysqlnd.trace_alloc', '5.5', [278, 279], '5.4'],
            ['sys_temp_dir', '5.5', [281, 282], '5.4'],
            ['xsl.security_prefs', '5.5', [284, 285], '5.4'],
            ['opcache.enable', '5.5', [344, 345], '5.4'],
            ['opcache.enable_cli', '5.5', [347, 348], '5.4'],
            ['opcache.memory_consumption', '5.5', [350, 351], '5.4'],
            ['opcache.interned_strings_buffer', '5.5', [353, 354], '5.4'],
            ['opcache.max_accelerated_files', '5.5', [356, 357], '5.4'],
            ['opcache.max_wasted_percentage', '5.5', [359, 360], '5.4'],
            ['opcache.use_cwd', '5.5', [362, 363], '5.4'],
            ['opcache.validate_timestamps', '5.5', [365, 366], '5.4'],
            ['opcache.revalidate_freq', '5.5', [368, 369], '5.4'],
            ['opcache.revalidate_path', '5.5', [371, 372], '5.4'],
            ['opcache.save_comments', '5.5', [374, 375], '5.4'],
            ['opcache.load_comments', '5.5', [377, 378], '5.4'],
            ['opcache.fast_shutdown', '5.5', [380, 381], '5.4'],
            ['opcache.enable_file_override', '5.5', [383, 384], '5.4'],
            ['opcache.optimization_level', '5.5', [386, 387], '5.4'],
            ['opcache.inherited_hack', '5.5', [389, 390], '5.4'],
            ['opcache.dups_fix', '5.5', [392, 393], '5.4'],
            ['opcache.blacklist_filename', '5.5', [395, 396], '5.4'],
            ['opcache.max_file_size', '5.5', [398, 399], '5.4'],
            ['opcache.consistency_checks', '5.5', [401, 402], '5.4'],
            ['opcache.force_restart_timeout', '5.5', [404, 405], '5.4'],
            ['opcache.error_log', '5.5', [407, 408], '5.4'],
            ['opcache.log_verbosity_level', '5.5', [410, 411], '5.4'],
            ['opcache.preferred_memory_model', '5.5', [413, 414], '5.4'],
            ['opcache.protect_memory', '5.5', [416, 417], '5.4'],
            ['opcache.mmap_base', '5.5', [419, 420], '5.4'],
            ['opcache.restrict_api', '5.5', [422, 423], '5.4'],
            ['opcache.file_update_protection', '5.5', [425, 426], '5.4'],
            ['opcache.huge_code_pages', '5.5', [428, 429], '5.4'],
            ['opcache.lockfile_path', '5.5', [431, 432], '5.4'],

            ['session.use_strict_mode', '5.6', [287, 288], '5.5.1', '5.5'],

            ['mysqli.rollback_on_cached_plink', '5.6', [290, 291], '5.5'],
            ['openssl.cafile', '5.6', [482, 483], '5.5'],
            ['openssl.capath', '5.6', [485, 486], '5.5'],
            ['mysqlnd.fetch_data_copy', '5.6', [497, 498], '5.5'],

            ['phpdbg.path', '7.0', [467, 468], '5.6.2', '5.6'],

            ['assert.exception', '7.0', [293, 294], '5.6'],
            ['pcre.jit', '7.0', [296, 297], '5.6'],
            ['phpdbg.eol', '7.0', [470, 471], '5.6'],
            ['session.lazy_write', '7.0', [299, 300], '5.6'],
            ['zend.assertions', '7.0', [302, 303], '5.6'],
            ['opcache.file_cache', '7.0', [437, 438], '5.6'],
            ['opcache.file_cache_only', '7.0', [440, 441], '5.6'],
            ['opcache.file_cache_consistency_checks', '7.0', [443, 444], '5.6'],
            ['opcache.file_cache_fallback', '7.0', [446, 447], '5.6'],

            ['opcache.validate_permission', '7.1', [449, 450], '7.0.13', '7.0'],
            ['opcache.validate_root', '7.1', [452, 453], '7.0.13', '7.0'],

            ['hard_timeout', '7.1', [320, 321], '7.0'],
            ['opcache.opt_debug_level', '7.1', [434, 435], '7.0'],
            ['session.sid_length', '7.1', [305, 306], '7.0'],
            ['session.sid_bits_per_character', '7.1', [308, 309], '7.0'],
            ['session.trans_sid_hosts', '7.1', [323, 324], '7.0'],
            ['session.trans_sid_tags', '7.1', [326, 327], '7.0'],
            ['url_rewriter.hosts', '7.1', [329, 330], '7.0'],

            ['imap.enable_insecure_rsh', '7.2', [335, 336], '7.1.24', '7.1'],

            ['sqlite3.defensive', '7.3', [527, 528], '7.2.16', '7.2'],

            ['syslog.facility', '7.3', [311, 312], '7.2'],
            ['syslog.ident', '7.3', [314, 315], '7.2'],
            ['syslog.filter', '7.3', [317, 318], '7.2'],
            ['session.cookie_samesite', '7.3', [332, 333], '7.2'],

            ['ffi.enable', '7.4', [458, 459], '7.3'],
            ['ffi.preload', '7.4', [461, 462], '7.3'],
            ['opcache.cache_id', '7.4', [341, 342], '7.3'],
            ['opcache.preload', '7.4', [455, 456], '7.3'],
            ['opcache.preload_user', '7.4', [464, 465], '7.3'],
            ['zend.exception_ignore_args', '7.4', [338, 339], '7.3'],
            ['unserialize_max_depth', '7.4', [555, 556], '7.3'],
            ['mbstring.regex_retry_limit', '7.4', [558, 559], '7.3'],

            ['com.dotnet_version', '8.0', [536, 537], '7.4'],
            ['pm.status_listen', '8.0', [533, 534], '7.4'],
            ['zend.exception_string_param_max_len', '8.0', [530, 531], '7.4'],

            ['opcache.jit', '8.0', [561, 562], '7.4'],
            ['opcache.jit_buffer_size', '8.0', [564, 565], '7.4'],
            ['opcache.jit_debug', '8.0', [567, 568], '7.4'],
            ['opcache.jit_bisect_limit', '8.0', [570, 571], '7.4'],
            ['opcache.jit_prof_threshold', '8.0', [573, 574], '7.4'],
            ['opcache.jit_hot_loop', '8.0', [576, 577], '7.4'],
            ['opcache.jit_hot_func', '8.0', [579, 580], '7.4'],
            ['opcache.jit_hot_return', '8.0', [582, 583], '7.4'],
            ['opcache.jit_hot_side_exit', '8.0', [585, 586], '7.4'],
            ['opcache.jit_blacklist_root_trace', '8.0', [588, 589], '7.4'],
            ['opcache.jit_blacklist_side_trace', '8.0', [591, 592], '7.4'],
            ['opcache.jit_max_loop_unrolls', '8.0', [594, 595], '7.4'],
            ['opcache.jit_max_exit_counters', '8.0', [597, 598], '7.4'],
            ['opcache.jit_max_root_traces', '8.0', [600, 601], '7.4'],
            ['opcache.jit_max_side_traces', '8.0', [603, 604], '7.4'],
            ['opcache.jit_max_recursive_calls', '8.0', [606, 607], '7.4'],
            ['opcache.jit_max_recursive_returns', '8.0', [609, 610], '7.4'],
            ['opcache.jit_max_polymorphic_calls', '8.0', [612, 613], '7.4'],

            ['fiber.stack_size', '8.1', [539, 540], '8.0'],
            ['mysqli.local_infile_directory', '8.1', [542, 543], '8.0'],
            ['pm.max_spawn_rate', '8.1', [545, 546], '8.0'],
        ];
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
        $errorVersion = (isset($testVersion)) ? $testVersion : $lastVersionBefore;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "INI directive '{$iniName}' is not present in PHP version {$lastVersionBefore} or earlier. This directive was previously called '{$alternative}'.";
        $this->assertError($file, $lines[0], $error);
        $this->assertWarning($file, $lines[1], $error);

        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
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
    public static function dataNewIniDirectivesWithAlternative()
    {
        return [
            ['fbsql.batchsize', '5.1', 'fbsql.batchSize', [167, 168], '5.0'],
            ['zend.detect_unicode', '5.4', 'detect_unicode', [260, 261], '5.3'],
        ];
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
        $file = $this->sniffFile(__FILE__, '4.4'); // Low version below the first addition.
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public static function dataNoFalsePositives()
    {
        return [
            [2],
            [3],
            [4],
            [5],
            [6],
            [549],
            [552],
            [553],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '99.0'); // High version beyond newest addition.
        $this->assertNoViolation($file);
    }
}
