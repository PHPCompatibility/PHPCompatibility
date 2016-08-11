<?php
/**
 * New Functions Parameter Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New Functions Parameter Sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewFunctionParameterSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/new_function_parameter.php';

    /**
     * testInvalidParameter
     *
     * @dataProvider dataInvalidParameter
     *
     * @param string $functionName      Function name.
     * @param string $parameterName     Parameter name.
     * @param string $lastVersionBefore The PHP version just *before* the parameter was introduced.
     * @param array  $lines             The line numbers in the test file which apply to this class.
     * @param string $okVersion         A PHP version in which the parameter was ok to be used.
     * @param string $testVersion       Optional PHP version to use for testing the flagged case.
     *
     * @return void
     */
    public function testInvalidParameter($functionName, $parameterName, $lastVersionBefore, $lines, $okVersion, $testVersion = null)
    {
        if (isset($testVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $testVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $lastVersionBefore);
        }
        foreach ($lines as $line) {
            $this->assertError($file, $line, "The function {$functionName} does not have a parameter \"{$parameterName}\" in PHP version {$lastVersionBefore} or earlier");
        }

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testInvalidParameter()
     *
     * @return array
     */
    public function dataInvalidParameter()
    {
        return array(
            array('array_filter', 'flag', '5.5', array(11), '5.6'),
            array('array_slice', 'preserve_keys', '5.0.1', array(12), '5.1', '5.0'),
            array('array_unique', 'sort_flags', '5.2.8', array(13), '5.3', '5.2'),
            array('assert', 'description', '5.4.7', array(14), '5.5', '5.4'),
            array('base64_decode', 'strict', '5.1', array(15), '5.2'),
            array('class_implements', 'autoload', '5.0', array(16), '5.1'),
            array('class_parents', 'autoload', '5.0', array(17), '5.1'),
            array('clearstatcache', 'clear_realpath_cache', '5.2', array(18), '5.3'),
            array('clearstatcache', 'filename', '5.2', array(18), '5.3'),
            array('copy', 'context', '5.2', array(19), '5.3'),
            array('curl_multi_info_read', 'msgs_in_queue', '5.1', array(20), '5.2'),
            array('debug_backtrace', 'options', '5.2.4', array(21), '5.4', '5.2'),
            array('debug_backtrace', 'limit', '5.3', array(21), '5.4'),
            array('debug_print_backtrace', 'options', '5.3.5', array(22), '5.4', '5.3'),
            array('debug_print_backtrace', 'limit', '5.3', array(22), '5.4'),
            array('dirname', 'levels', '5.6', array(23), '7.0'),
            array('dns_get_record', 'raw', '5.3', array(24), '5.4'),
            array('fgetcsv', 'escape', '5.2', array(25), '5.3'),
            array('fputcsv', 'escape_char', '5.5.3', array(26), '5.6', '5.5'),
            array('file_get_contents', 'offset', '5.0', array(27), '5.1'),
            array('file_get_contents', 'maxlen', '5.0', array(27), '5.1'),
            array('filter_input_array', 'add_empty', '5.3', array(28), '5.4'),
            array('filter_var_array', 'add_empty', '5.3', array(29), '5.4'),
            array('gettimeofday', 'return_float', '5.0', array(30), '5.1'),
            array('get_html_translation_table', 'encoding', '5.3.3', array(31), '5.4', '5.3'),
            array('get_loaded_extensions', 'zend_extensions', '5.2.3', array(32), '5.3', '5.2'),
            array('gzcompress', 'encoding', '5.3', array(33), '5.4'),
            array('gzdeflate', 'encoding', '5.3', array(34), '5.4'),
            array('htmlentities', 'double_encode', '5.2.2', array(35), '5.3', '5.2'),
            array('htmlspecialchars', 'double_encode', '5.2.2', array(36), '5.3', '5.2'),
            array('http_build_query', 'arg_separator', '5.1.1', array(37), '5.4', '5.1'),
            array('http_build_query', 'enc_type', '5.3', array(37), '5.4'),
            array('idn_to_ascii', 'variant', '5.3', array(38), '5.4'),
            array('idn_to_ascii', 'idna_info', '5.3', array(38), '5.4'),
            array('idn_to_utf8', 'variant', '5.3', array(39), '5.4'),
            array('idn_to_utf8', 'idna_info', '5.3', array(39), '5.4'),
            array('imagecolorset', 'alpha', '5.3', array(40), '5.4'),
            array('imagepng', 'quality', '5.1.1', array(41), '5.2', '5.1'),
            array('imagepng', 'filters', '5.1.2', array(41), '5.2', '5.1'),
            array('imagerotate', 'ignore_transparent', '5.0', array(42), '5.1'),
            array('imap_open', 'n_retries', '5.1', array(43), '5.4'),
            array('imap_open', 'params', '5.3.1', array(43), '5.4', '5.3'),
            array('imap_reopen', 'n_retries', '5.1', array(44), '5.2'),
            array('ini_get_all', 'details', '5.2', array(45), '5.3'),
            array('is_a', 'allow_string', '5.3.8', array(46), '5.4', '5.3'),
            array('is_subclass_of', 'allow_string', '5.3.8', array(47), '5.4', '5.3'),
            array('iterator_to_array', 'use_keys', '5.2', array(48), '5.3', '5.2'),
            array('json_decode', 'depth', '5.2', array(49), '5.4'),
            array('json_decode', 'options', '5.3', array(49), '5.4'),
            array('json_encode', 'options', '5.2', array(50), '5.5'),
            array('json_encode', 'depth', '5.4', array(50), '5.5'),
            array('memory_get_peak_usage', 'real_usage', '5.1', array(51), '5.2'),
            array('memory_get_usage', 'real_usage', '5.1', array(52), '5.2'),
            array('mb_encode_numericentity', 'is_hex', '5.3', array(53), '5.4'),
            array('mb_strrpos', 'offset', '5.1', array(54), '5.2'),
            array('mssql_connect', 'new_link', '5.0', array(55), '5.1'),
            array('mysqli_commit', 'flags', '5.4', array(56), '5.5'),
            array('mysqli_commit', 'name', '5.4', array(56), '5.5'),
            array('mysqli_rollback', 'flags', '5.4', array(57), '5.5'),
            array('mysqli_rollback', 'name', '5.4', array(57), '5.5'),
            array('nl2br', 'is_xhtml', '5.2', array(58), '5.3'),
            array('openssl_decrypt', 'iv', '5.3.2', array(59), '5.4', '5.3'),
            array('openssl_encrypt', 'iv', '5.3.2', array(60), '5.4', '5.3'),
            array('openssl_pkcs7_verify', 'content', '5.0', array(61), '5.1'),
            array('openssl_seal', 'method', '5.2', array(62), '5.3'),
            array('openssl_verify', 'signature_alg', '5.1', array(63), '5.2'),
            array('parse_ini_file', 'scanner_mode', '5.2', array(64), '5.3'),
            array('parse_url', 'component', '5.1.1', array(65), '5.2', '5.1'),
            array('pg_lo_create', 'object_id', '5.2', array(66), '5.3'),
            array('pg_lo_import', 'object_id', '5.2', array(67), '5.3'),
            array('preg_replace', 'count', '5.0', array(68), '5.1'),
            array('preg_replace_callback', 'count', '5.0', array(69), '5.1'),
            array('round', 'mode', '5.2', array(70), '5.3'),
            array('sem_acquire', 'nowait', '5.6', array(71), '7.0'),
            array('session_regenerate_id', 'delete_old_session', '5.0', array(72), '5.1'),
            array('session_set_cookie_params', 'httponly', '5.1', array(73), '5.2'),
            array('session_set_save_handler', 'create_sid', '5.5', array(74), '5.6'),
            array('session_start', 'options', '5.6', array(75), '7.0'),
            array('setcookie', 'httponly', '5.1', array(76), '5.2'),
            array('setrawcookie', 'httponly', '5.1', array(77), '5.2'),
            array('simplexml_load_file', 'is_prefix', '5.1', array(78), '5.2'),
            array('simplexml_load_string', 'is_prefix', '5.1', array(79), '5.2'),
            array('spl_autoload_register', 'prepend', '5.2', array(80), '5.3'),
            array('stream_context_create', 'params', '5.2', array(81), '5.3'),
            array('stream_copy_to_stream', 'offset', '5.0', array(82), '5.1'),
            array('stream_get_contents', 'offset', '5.0', array(83), '5.1'),
            array('stream_wrapper_register', 'flags', '5.2.3', array(84), '5.3', '5.2'),
            array('stristr', 'before_needle', '5.2', array(85), '5.3'),
            array('strstr', 'before_needle', '5.2', array(86), '5.3'),
            array('str_word_count', 'charlist', '5.0', array(87), '5.1'),
            array('substr_count', 'offset', '5.0', array(88), '5.1'),
            array('substr_count', 'length', '5.0', array(88), '5.1'),
            array('sybase_connect', 'new', '5.2', array(89), '5.2.17'),
            array('timezone_transitions_get', 'timestamp_begin', '5.2', array(90), '5.3'),
            array('timezone_transitions_get', 'timestamp_end', '5.2', array(90), '5.3'),
            array('timezone_identifiers_list', 'what', '5.2', array(91), '5.3'),
            array('timezone_identifiers_list', 'country', '5.2', array(91), '5.3'),
            array('token_get_all', 'flags', '5.6', array(92), '7.0'),
            array('ucwords', 'delimiters', '5.4.31', array(93), '5.6', '5.4'),
            array('unserialize', 'options', '5.6', array(94), '7.0'),
        );
    }


    /**
     * testValidParameter
     *
     * @dataProvider dataValidParameter
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testValidParameter($line)
    {
        $file = $this->sniffFile(self::TEST_FILE);
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testValidParameter()
     *
     * @return array
     */
    public function dataValidParameter()
    {
        return array(
            array(4),
        );
    }

}
