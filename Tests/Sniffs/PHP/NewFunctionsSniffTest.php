<?php
/**
 * New Functions Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New Functions Sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class NewFunctionsSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/new_functions.php';

    /**
     * Test functions that shouldn't be flagged by this sniff.
     *
     * These are either userland methods or namespaced functions.
     *
     * @requires PHP 5.3
     *
     * @group newFunctions
     *
     * @return void
     */
    public function testFunctionsThatShouldntBeFlagged()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');

        $this->assertNoViolation($file, 4);
        $this->assertNoViolation($file, 5);
        $this->assertNoViolation($file, 6);
        $this->assertNoViolation($file, 7);
    }


    /**
     * testNewFunction
     *
     * @group newFunctions
     *
     * @dataProvider dataNewFunction
     *
     * @param string $functionName      Name of the function.
     * @param string $lastVersionBefore The PHP version just *before* the function was introduced.
     * @param array  $lines             The line numbers in the test file which apply to this function.
     * @param string $okVersion         A PHP version in which the function was valid.
     * @param string $testVersion       Optional. A PHP version in which to test for the error if different
     *                                  from the $lastVersionBefore.
     *
     * @return void
     */
    public function testNewFunction($functionName, $lastVersionBefore, $lines, $okVersion, $testVersion = null)
    {
        if (isset($testVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $testVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $lastVersionBefore);
        }
        foreach($lines as $line) {
            $this->assertError($file, $line, "The function {$functionName} is not present in PHP version {$lastVersionBefore} or earlier");
        }

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach( $lines as $line ) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testNewFunction()
     *
     * @return array
     */
    public function dataNewFunction() {
        return array(
            array('array_fill_keys', '5.1', array(12, 13), '5.3'),
            array('error_get_last', '5.1', array(14), '5.3'),
            array('image_type_to_extension', '5.1', array(15), '5.2'),
            array('memory_get_peak_usage', '5.1', array(16), '5.2'),
            array('sys_get_temp_dir', '5.1', array(17), '5.2'),
            array('timezone_abbreviations_list', '5.1', array(18), '5.2'),
            array('timezone_identifiers_list', '5.1', array(19), '5.2'),
            array('timezone_name_from_abbr', '5.1', array(20), '5.2'),
            array('stream_socket_shutdown', '5.1', array(21), '5.2'),
            array('imagegrabscreen', '5.1', array(22), '5.2'),
            array('imagegrabwindow', '5.1', array(23), '5.2'),
            array('libxml_disable_entity_loader', '5.1', array(24), '5.2'),
            array('mb_stripos', '5.1', array(25), '5.2'),
            array('mb_stristr', '5.1', array(26), '5.2'),
            array('mb_strrchr', '5.1', array(27), '5.2'),
            array('mb_strrichr', '5.1', array(28), '5.2'),
            array('mb_strripos', '5.1', array(29), '5.2'),
            array('ming_setSWFCompression', '5.1', array(30), '5.2'),
            array('openssl_csr_get_public_key', '5.1', array(31), '5.2'),
            array('openssl_csr_get_subject', '5.1', array(32), '5.2'),
            array('openssl_pkey_get_details', '5.1', array(33), '5.2'),
            array('spl_object_hash', '5.1', array(34), '5.2'),
            array('iterator_apply', '5.1', array(35), '5.2'),
            array('preg_last_error', '5.1', array(36), '5.2'),
            array('pg_field_table', '5.1', array(37), '5.2'),
            array('posix_initgroups', '5.1', array(38), '5.2'),
            array('gmp_nextprime', '5.1', array(39), '5.2'),
            array('xmlwriter_full_end_element', '5.1', array(40), '5.2'),
            array('xmlwriter_write_raw', '5.1', array(41), '5.2'),
            array('xmlwriter_start_dtd_entity', '5.1', array(42), '5.2'),
            array('xmlwriter_end_dtd_entity', '5.1', array(43), '5.2'),
            array('xmlwriter_write_dtd_entity', '5.1', array(44), '5.2'),
            array('filter_has_var', '5.1', array(45), '5.2'),
            array('filter_id', '5.1', array(46), '5.2'),
            array('filter_input_array', '5.1', array(47), '5.2'),
            array('filter_input', '5.1', array(48), '5.2'),
            array('filter_list', '5.1', array(49), '5.2'),
            array('filter_var_array', '5.1', array(50), '5.2'),
            array('filter_var', '5.1', array(51), '5.2'),
            array('json_decode', '5.1', array(52), '5.2'),
            array('json_encode', '5.1', array(53), '5.2'),
            array('zip_close', '5.1', array(54), '5.2'),
            array('zip_entry_close', '5.1', array(55), '5.2'),
            array('zip_entry_compressedsize', '5.1', array(56), '5.2'),
            array('zip_entry_compressionmethod', '5.1', array(57), '5.2'),
            array('zip_entry_filesize', '5.1', array(58), '5.2'),
            array('zip_entry_name', '5.1', array(59), '5.2'),
            array('zip_entry_open', '5.1', array(60), '5.2'),
            array('zip_entry_read', '5.1', array(61), '5.2'),
            array('zip_open', '5.1', array(62), '5.2'),
            array('zip_read', '5.1', array(63), '5.2'),

            array('array_replace', '5.2', array(65), '5.3'),
            array('array_replace_recursive', '5.2', array(66), '5.3'),
            array('class_alias', '5.2', array(67), '5.3'),
            array('forward_static_call', '5.2', array(68), '5.3'),
            array('forward_static_call_array', '5.2', array(69), '5.3'),
            array('gc_collect_cycles', '5.2', array(70), '5.3'),
            array('gc_disable', '5.2', array(71), '5.3'),
            array('gc_enable', '5.2', array(72), '5.3'),
            array('gc_enabled', '5.2', array(73), '5.3'),
            array('get_called_class', '5.2', array(74), '5.3'),
            array('gethostname', '5.2', array(75), '5.3'),
            array('header_remove', '5.2', array(76), '5.3'),
            array('lcfirst', '5.2', array(77), '5.3'),
            array('parse_ini_string', '5.2', array(78), '5.3'),
            array('quoted_printable_encode', '5.2', array(79), '5.3'),
            array('str_getcsv', '5.2', array(80), '5.3'),
            array('stream_context_set_default', '5.2', array(81), '5.3'),
            array('stream_supports_lock', '5.2', array(82), '5.3'),
            array('stream_context_get_params', '5.2', array(83), '5.3'),
            array('date_add', '5.2', array(84), '5.3'),
            array('date_create_from_format', '5.2', array(85), '5.3'),
            array('date_diff', '5.2', array(86), '5.3'),
            array('date_get_last_errors', '5.2', array(87), '5.3'),
            array('date_parse_from_format', '5.2', array(88), '5.3'),
            array('date_sub', '5.2', array(89), '5.3'),
            array('timezone_version_get', '5.2', array(90), '5.3'),
            array('gmp_testbit', '5.2', array(91), '5.3'),
            array('hash_copy', '5.2', array(92), '5.3'),
            array('imap_gc', '5.2', array(93), '5.3'),
            array('imap_utf8_to_mutf7', '5.2', array(94), '5.3'),
            array('imap_mutf7_to_utf8', '5.2', array(95), '5.3'),
            array('json_last_error', '5.2', array(96), '5.3'),
            array('mysqli_fetch_all', '5.2', array(97), '5.3'),
            array('mysqli_get_connection_status', '5.2', array(98), '5.3'),
            array('mysqli_poll', '5.2', array(99), '5.3'),
            array('mysqli_read_async_query', '5.2', array(100), '5.3'),
            array('openssl_random_pseudo_bytes', '5.2', array(101), '5.3'),
            array('pcntl_signal_dispatch', '5.2', array(102), '5.3'),
            array('pcntl_sigprocmask', '5.2', array(103), '5.3'),
            array('pcntl_sigtimedwait', '5.2', array(104), '5.3'),
            array('pcntl_sigwaitinfo', '5.2', array(105), '5.3'),
            array('preg_filter', '5.2', array(106), '5.3'),
            array('msg_queue_exists', '5.2', array(107), '5.3'),
            array('shm_has_vars', '5.2', array(108), '5.3'),
            array('acosh', '5.2', array(109), '5.3'),
            array('asinh', '5.2', array(110), '5.3'),
            array('atanh', '5.2', array(111), '5.3'),
            array('expm1', '5.2', array(112), '5.3'),
            array('log1p', '5.2', array(113), '5.3'),
            array('enchant_broker_describe', '5.2', array(114), '5.3'),
            array('enchant_broker_dict_exists', '5.2', array(115), '5.3'),
            array('enchant_broker_free_dict', '5.2', array(116), '5.3'),
            array('enchant_broker_free', '5.2', array(117), '5.3'),
            array('enchant_broker_get_error', '5.2', array(118), '5.3'),
            array('enchant_broker_init', '5.2', array(119), '5.3'),
            array('enchant_broker_list_dicts', '5.2', array(120), '5.3'),
            array('enchant_broker_request_dict', '5.2', array(121), '5.3'),
            array('enchant_broker_request_pwl_dict', '5.2', array(122), '5.3'),
            array('enchant_broker_set_ordering', '5.2', array(123), '5.3'),
            array('enchant_dict_add_to_personal', '5.2', array(124), '5.3'),
            array('enchant_dict_add_to_session', '5.2', array(125), '5.3'),
            array('enchant_dict_check', '5.2', array(126), '5.3'),
            array('enchant_dict_describe', '5.2', array(127), '5.3'),
            array('enchant_dict_get_error', '5.2', array(128), '5.3'),
            array('enchant_dict_is_in_session', '5.2', array(129), '5.3'),
            array('enchant_dict_quick_check', '5.2', array(130), '5.3'),
            array('enchant_dict_store_replacement', '5.2', array(131), '5.3'),
            array('enchant_dict_suggest', '5.2', array(132), '5.3'),
            array('finfo_buffer', '5.2', array(133), '5.3'),
            array('finfo_close', '5.2', array(134), '5.3'),
            array('finfo_file', '5.2', array(135), '5.3'),
            array('finfo_open', '5.2', array(136), '5.3'),
            array('finfo_set_flags', '5.2', array(137), '5.3'),
            array('intl_error_name', '5.2', array(138), '5.3'),
            array('intl_get_error_code', '5.2', array(139), '5.3'),
            array('intl_get_error_message', '5.2', array(140), '5.3'),
            array('intl_is_failure', '5.2', array(141), '5.3'),
            array('mysqli_get_cache_stats', '5.2', array(142), '5.3'),

            array('hex2bin', '5.3', array(144), '5.4'),
            array('http_response_code', '5.3', array(145), '5.4'),
            array('get_declared_traits', '5.3', array(146), '5.4'),
            array('getimagesizefromstring', '5.3', array(147), '5.4'),
            array('stream_set_chunk_size', '5.3', array(148), '5.4'),
            array('socket_import_stream', '5.3', array(149), '5.4'),
            array('trait_exists', '5.3', array(150), '5.4'),
            array('header_register_callback', '5.3', array(151), '5.4'),
            array('class_uses', '5.3', array(152), '5.4'),
            array('session_status', '5.3', array(153), '5.4'),
            array('session_register_shutdown', '5.3', array(154), '5.4'),
            array('mysqli_error_list', '5.3', array(155), '5.4'),
            array('mysqli_stmt_error_list', '5.3', array(156), '5.4'),
            array('libxml_set_external_entity_loader', '5.3', array(157), '5.4'),
            array('ldap_control_paged_result', '5.3', array(158), '5.4'),
            array('ldap_control_paged_result_response', '5.3', array(159), '5.4'),
            array('transliteral_create', '5.3', array(160), '5.4'),
            array('transliteral_create_from_rules', '5.3', array(161), '5.4'),
            array('transliteral_create_inverse', '5.3', array(162), '5.4'),
            array('transliteral_get_error_code', '5.3', array(163), '5.4'),
            array('transliteral_get_error_message', '5.3', array(164), '5.4'),
            array('transliteral_list_ids', '5.3', array(165), '5.4'),
            array('transliteral_transliterate', '5.3', array(166), '5.4'),
            array('zlib_decode', '5.3', array(167), '5.4'),
            array('zlib_encode', '5.3', array(168), '5.4'),

            array('array_column', '5.4', array(170), '5.5'),
            array('boolval', '5.4', array(171), '5.5'),
            array('json_last_error_msg', '5.4', array(172), '5.5'),
            array('password_get_info', '5.4', array(173), '5.5'),
            array('password_hash', '5.4', array(174), '5.5'),
            array('password_needs_rehash', '5.4', array(175), '5.5'),
            array('password_verify', '5.4', array(176), '5.5'),
            array('hash_pbkdf2', '5.4', array(177), '5.5'),
            array('openssl_pbkdf2', '5.4', array(178), '5.5'),
            array('curl_escape', '5.4', array(179), '5.5'),
            array('curl_file_create', '5.4', array(180), '5.5'),
            array('curl_multi_setopt', '5.4', array(181), '5.5'),
            array('curl_multi_strerror', '5.4', array(182), '5.5'),
            array('curl_pause', '5.4', array(183), '5.5'),
            array('curl_reset', '5.4', array(184), '5.5'),
            array('curl_share_close', '5.4', array(185), '5.5'),
            array('curl_share_init', '5.4', array(186), '5.5'),
            array('curl_share_setopt', '5.4', array(187), '5.5'),
            array('curl_strerror', '5.4', array(188), '5.5'),
            array('curl_unescape', '5.4', array(189), '5.5'),
            array('imageaffinematrixconcat', '5.4', array(190), '5.5'),
            array('imageaffinematrixget', '5.4', array(191), '5.5'),
            array('imagecrop', '5.4', array(192), '5.5'),
            array('imagecropauto', '5.4', array(193), '5.5'),
            array('imageflip', '5.4', array(194), '5.5'),
            array('imagepalettetotruecolor', '5.4', array(195), '5.5'),
            array('imagescale', '5.4', array(196), '5.5'),
            array('mysqli_begin_transaction', '5.4', array(197), '5.5'),
            array('mysqli_release_savepoint', '5.4', array(198), '5.5'),
            array('mysqli_savepoint', '5.4', array(199), '5.5'),
            array('pg_escape_literal', '5.4', array(200), '5.5'),
            array('pg_escape_identifier', '5.4', array(201), '5.5'),
            array('socket_sendmsg', '5.4', array(202), '5.5'),
            array('socket_recvmsg', '5.4', array(203), '5.5'),
            array('socket_cmsg_space', '5.4', array(204), '5.5'),
            array('cli_get_process_title', '5.4', array(205), '5.5'),
            array('cli_set_process_title', '5.4', array(206), '5.5'),
            array('datefmt_format_object', '5.4', array(207), '5.5'),
            array('datefmt_get_calendar_object', '5.4', array(208), '5.5'),
            array('datefmt_get_timezone', '5.4', array(209), '5.5'),
            array('datefmt_set_timezone', '5.4', array(210), '5.5'),
            array('datefmt_get_calendar_object', '5.4', array(211), '5.5'),
            array('intlcal_create_instance', '5.4', array(212), '5.5'),
            array('intlcal_get_keyword_values_for_locale', '5.4', array(213), '5.5'),
            array('intlcal_get_now', '5.4', array(214), '5.5'),
            array('intlcal_get_available_locales', '5.4', array(215), '5.5'),
            array('intlcal_get', '5.4', array(216), '5.5'),
            array('intlcal_get_time', '5.4', array(217), '5.5'),
            array('intlcal_set_time', '5.4', array(218), '5.5'),
            array('intlcal_add', '5.4', array(219), '5.5'),
            array('intlcal_set_time_zone', '5.4', array(220), '5.5'),
            array('intlcal_after', '5.4', array(221), '5.5'),
            array('intlcal_before', '5.4', array(222), '5.5'),
            array('intlcal_set', '5.4', array(223), '5.5'),
            array('intlcal_roll', '5.4', array(224), '5.5'),
            array('intlcal_clear', '5.4', array(225), '5.5'),
            array('intlcal_field_difference', '5.4', array(226), '5.5'),
            array('intlcal_get_actual_maximum', '5.4', array(227), '5.5'),
            array('intlcal_get_actual_minumum', '5.4', array(228), '5.5'),
            array('intlcal_get_day_of_week_type', '5.4', array(229), '5.5'),
            array('intlcal_get_first_day_of_week', '5.4', array(230), '5.5'),
            array('intlcal_get_greatest_minimum', '5.4', array(231), '5.5'),
            array('intlcal_get_least_maximum', '5.4', array(232), '5.5'),
            array('intlcal_get_locale', '5.4', array(233), '5.5'),
            array('intlcal_get_maximum', '5.4', array(234), '5.5'),
            array('intlcal_get_minimal_days_in_first_week', '5.4', array(235), '5.5'),
            array('intlcal_get_minimum', '5.4', array(236), '5.5'),
            array('intlcal_get_time_zone', '5.4', array(237), '5.5'),
            array('intlcal_get_type', '5.4', array(238), '5.5'),
            array('intlcal_get_weekend_transition', '5.4', array(239), '5.5'),
            array('intlcal_in_daylight_time', '5.4', array(240), '5.5'),
            array('intlcal_is_equivalent_to', '5.4', array(241), '5.5'),
            array('intlcal_is_lenient', '5.4', array(242), '5.5'),
            array('intlcal_equals', '5.4', array(243), '5.5'),
            array('intlcal_get_repeated_wall_time_option', '5.4', array(244), '5.5'),
            array('intlcal_get_skipped_wall_time_option', '5.4', array(245), '5.5'),
            array('intlcal_set_repeated_wall_time_option', '5.4', array(246), '5.5'),
            array('intlcal_set_skipped_wall_time_option', '5.4', array(247), '5.5'),
            array('intlcal_from_date_time', '5.4', array(248), '5.5'),
            array('intlcal_to_date_time', '5.4', array(249), '5.5'),
            array('intlcal_get_error_code', '5.4', array(250), '5.5'),
            array('intlcal_get_error_message', '5.4', array(251), '5.5'),
            array('intlgregcal_create_instance', '5.4', array(252), '5.5'),
            array('intlgregcal_set_gregorian_change', '5.4', array(253), '5.5'),
            array('intlgregcal_get_gregorian_change', '5.4', array(254), '5.5'),
            array('intlgregcal_is_leap_year', '5.4', array(255), '5.5'),
            array('intlz_create_time_zone', '5.4', array(256), '5.5'),
            array('intlz_create_default', '5.4', array(257), '5.5'),
            array('intlz_get_id', '5.4', array(258), '5.5'),
            array('intlz_get_gmt', '5.4', array(259), '5.5'),
            array('intlz_get_unknown', '5.4', array(260), '5.5'),
            array('intlz_create_enumeration', '5.4', array(261), '5.5'),
            array('intlz_count_equivalent_ids', '5.4', array(262), '5.5'),
            array('intlz_create_time_zone_id_enumeration', '5.4', array(263), '5.5'),
            array('intlz_get_canonical_id', '5.4', array(264), '5.5'),
            array('intlz_get_region', '5.4', array(265), '5.5'),
            array('intlz_get_tz_data_version', '5.4', array(266), '5.5'),
            array('intlz_get_equivalent_id', '5.4', array(267), '5.5'),
            array('intlz_use_daylight_time', '5.4', array(268), '5.5'),
            array('intlz_get_offset', '5.4', array(269), '5.5'),
            array('intlz_get_raw_offset', '5.4', array(270), '5.5'),
            array('intlz_has_same_rules', '5.4', array(271), '5.5'),
            array('intlz_get_display_name', '5.4', array(272), '5.5'),
            array('intlz_get_dst_savings', '5.4', array(273), '5.5'),
            array('intlz_from_date_time_zone', '5.4', array(274), '5.5'),
            array('intlz_to_date_time_zone', '5.4', array(275), '5.5'),
            array('intlz_get_error_code', '5.4', array(276), '5.5'),
            array('intlz_get_error_message', '5.4', array(277), '5.5'),

            array('gmp_root', '5.5', array(279), '5.6'),
            array('gmp_rootrem', '5.5', array(280), '5.6'),
            array('hash_equals', '5.5', array(281), '5.6'),
            array('ldap_escape', '5.5', array(282), '5.6'),
            array('ldap_modify_batch', '5.4.25', array(283), '5.6', '5.4'),
            array('mysqli_get_links_stats', '5.5', array(284), '5.6'),
            array('openssl_get_cert_locations', '5.5', array(285), '5.6'),
            array('openssl_x509_fingerprint', '5.5', array(286), '5.6'),
            array('openssl_spki_new', '5.5', array(287), '5.6'),
            array('openssl_spki_verify', '5.5', array(288), '5.6'),
            array('openssl_spki_export_challenge', '5.5', array(289), '5.6'),
            array('openssl_spki_export', '5.5', array(290), '5.6'),
            array('pg_connect_poll', '5.5', array(291), '5.6'),
            array('pg_consume_input', '5.5', array(292), '5.6'),
            array('pg_flush', '5.5', array(293), '5.6'),
            array('pg_socket', '5.5', array(294), '5.6'),
            array('session_abort', '5.5', array(295), '5.6'),
            array('session_reset', '5.5', array(296), '5.6'),

            array('random_bytes', '5.6', array(298), '7.0'),
            array('random_int', '5.6', array(299), '7.0'),
            array('error_clear_last', '5.6', array(300), '7.0'),
            array('gmp_random_seed', '5.6', array(301), '7.0'),
            array('intdiv', '5.6', array(302), '7.0'),
            array('preg_replace_callback_array', '5.6', array(303), '7.0'),
            array('gc_mem_caches', '5.6', array(304), '7.0'),
            array('get_resources', '5.6', array(305), '7.0'),
            array('posix_setrlimit', '5.6', array(306), '7.0'),
            array('inflate_add', '5.6', array(307), '7.0'),
            array('deflate_add', '5.6', array(308), '7.0'),
            array('inflate_init', '5.6', array(309), '7.0'),
            array('deflate_init', '5.6', array(310), '7.0'),

            array('socket_export_stream', '7.0.6', array(312), '7.1', '7.0'),

            array('curl_multi_errno', '7.0', array(314), '7.1'),
            array('curl_share_errno', '7.0', array(315), '7.1'),
            array('curl_share_strerror', '7.0', array(316), '7.1'),
            array('is_iterable', '7.0', array(317), '7.1'),
            array('pcntl_async_signals', '7.0', array(318), '7.1'),
            array('session_create_id', '7.0', array(319), '7.1'),
            array('session_gc', '7.0', array(320), '7.1'),

        );
    }

}
