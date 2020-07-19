<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionUse;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewFunctionParameters sniff.
 *
 * @group newFunctionParameters
 * @group functionUse
 *
 * @covers \PHPCompatibility\Sniffs\FunctionUse\NewFunctionParametersSniff
 *
 * @since 7.0.0
 */
class NewFunctionParametersUnitTest extends BaseSniffTest
{

    /**
     * testInvalidParameter
     *
     * @dataProvider dataInvalidParameter
     *
     * @param string $functionName      Function name.
     * @param string $parameterName     Parameter name.
     * @param string $lastVersionBefore The PHP version just *before* the parameter was introduced.
     * @param array  $lines             The line numbers in the test file which apply to this function parameter.
     * @param string $okVersion         A PHP version in which the parameter was ok to be used.
     * @param string $testVersion       Optional PHP version to use for testing the flagged case.
     *
     * @return void
     */
    public function testInvalidParameter($functionName, $parameterName, $lastVersionBefore, $lines, $okVersion, $testVersion = null)
    {
        $errorVersion = (isset($testVersion)) ? $testVersion : $lastVersionBefore;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The function {$functionName}() does not have a parameter \"{$parameterName}\" in PHP version {$lastVersionBefore} or earlier";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }

        $file = $this->sniffFile(__FILE__, $okVersion);
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
        return [
            ['array_filter', 'mode', '5.5', [11], '5.6'],
            ['array_slice', 'preserve_keys', '5.0.1', [12], '5.1', '5.0'],
            ['array_unique', 'flags', '5.2.8', [13], '5.3', '5.2'],
            ['assert', 'description', '5.4.7', [14], '5.5', '5.4'],
            ['base64_decode', 'strict', '5.1', [15], '5.2'],
            ['bcmod', 'scale', '7.1', [96], '7.2'],
            ['class_implements', 'autoload', '5.0', [16], '5.1'],
            ['class_parents', 'autoload', '5.0', [17], '5.1'],
            ['clearstatcache', 'clear_realpath_cache', '5.2', [18], '5.3'],
            ['clearstatcache', 'filename', '5.2', [18], '5.3'],
            ['copy', 'context', '5.2', [19], '5.3'],
            ['curl_multi_info_read', 'queued_messages', '5.1', [20], '5.2'],
            ['date_time_set', 'microsecond', '7.0', [119], '7.1'],
            ['debug_backtrace', 'options', '5.2.4', [21], '5.4', '5.2'],
            ['debug_backtrace', 'limit', '5.3', [21], '5.4'],
            ['debug_print_backtrace', 'options', '5.3.5', [22], '5.4', '5.3'],
            ['debug_print_backtrace', 'limit', '5.3', [22], '5.4'],
            ['dirname', 'levels', '5.6', [23], '7.0'],
            ['dns_get_record', 'raw', '5.3', [24], '5.4'],
            ['fgetcsv', 'escape', '5.2', [25], '5.3'],
            ['file_get_contents', 'offset', '5.0', [27], '5.1'],
            ['file_get_contents', 'length', '5.0', [27], '5.1'],
            ['filter_input_array', 'add_empty', '5.3', [28], '5.4'],
            ['filter_var_array', 'add_empty', '5.3', [29], '5.4'],
            ['fputcsv', 'escape', '5.5.3', [26, 129], '8.1', '5.5'], // OK version > version in which last parameter was added to the function.
            ['fputcsv', 'eol', '8.0', [129], '8.1'],
            ['getenv', 'local_only', '5.5.37', [105], '5.6', '5.5'],
            ['getopt', 'rest_index', '7.0', [98], '7.1'],
            ['gettimeofday', 'as_float', '5.0', [30], '5.1'],
            ['get_defined_functions', 'exclude_disabled', '7.0.14', [95], '7.1', '7.0'],
            ['get_headers', 'context', '7.0', [97], '7.1'],
            ['get_html_translation_table', 'encoding', '5.3.3', [31], '5.4', '5.3'],
            ['get_loaded_extensions', 'zend_extensions', '5.2.3', [32], '5.3', '5.2'],
            ['gzcompress', 'encoding', '5.3', [33], '5.4'],
            ['gzdeflate', 'encoding', '5.3', [34], '5.4'],
            ['hash', 'options', '8.0', [126], '8.1'],
            ['hash_file', 'options', '8.0', [127], '8.1'],
            ['hash_init', 'options', '8.0', [128], '8.1'],
            ['htmlentities', 'double_encode', '5.2.2', [35], '5.3', '5.2'],
            ['htmlspecialchars', 'double_encode', '5.2.2', [36], '5.3', '5.2'],
            ['http_build_query', 'arg_separator', '5.1.1', [37], '5.4', '5.1'],
            ['http_build_query', 'encoding_type', '5.3', [37], '5.4'],
            ['idn_to_ascii', 'variant', '5.3', [38], '5.4'],
            ['idn_to_ascii', 'idna_info', '5.3', [38], '5.4'],
            ['idn_to_utf8', 'variant', '5.3', [39], '5.4'],
            ['idn_to_utf8', 'idna_info', '5.3', [39], '5.4'],
            ['imagecolorset', 'alpha', '5.3', [40], '5.4'],
            ['imagepng', 'quality', '5.1.1', [41], '5.2', '5.1'],
            ['imagepng', 'filters', '5.1.2', [41], '5.2', '5.1'],
            ['imagerotate', 'ignore_transparent', '5.0', [42], '5.1'],
            ['imap_open', 'retries', '5.1', [43], '5.4'],
            ['imap_open', 'options', '5.3.1', [43], '5.4', '5.3'],
            ['imap_reopen', 'retries', '5.1', [44], '5.2'],
            ['ini_get_all', 'details', '5.2', [45], '5.3'],
            ['is_a', 'allow_string', '5.3.8', [46], '5.4', '5.3'],
            ['is_subclass_of', 'allow_string', '5.3.8', [47], '5.4', '5.3'],
            ['iterator_to_array', 'preserve_keys', '5.2.0', [48], '5.3', '5.2'], // Function introduced in 5.2.1.
            ['json_decode', 'depth', '5.2', [49], '5.4'], // OK version > version in which last parameter was added to the function.
            ['json_decode', 'flags', '5.3', [49], '5.4'],
            ['json_encode', 'flags', '5.2', [50], '5.5'], // OK version > version in which last parameter was added to the function.
            ['json_encode', 'depth', '5.4', [50], '5.5'],
            ['ldap_add', 'controls', '7.2', [106], '7.3'],
            ['ldap_compare', 'controls', '7.2', [107], '7.3'],
            ['ldap_delete', 'controls', '7.2', [108], '7.3'],
            ['ldap_exop', 'controls', '7.2', [121], '7.3'],
            ['ldap_exop_passwd', 'controls', '7.2', [122], '7.3'],
            ['ldap_list', 'controls', '7.2', [109], '7.3'],
            ['ldap_mod_add', 'controls', '7.2', [110], '7.3'],
            ['ldap_mod_del', 'controls', '7.2', [111], '7.3'],
            ['ldap_mod_replace', 'controls', '7.2', [112], '7.3'],
            ['ldap_modify_batch', 'controls', '7.2', [113], '7.3'],
            ['ldap_parse_result', 'controls', '7.2', [114], '7.3'],
            ['ldap_read', 'controls', '7.2', [115], '7.3'],
            ['ldap_rename', 'controls', '7.2', [116], '7.3'],
            ['ldap_search', 'controls', '7.2', [117], '7.3'],
            ['memory_get_peak_usage', 'real_usage', '5.1', [51], '5.2'],
            ['memory_get_usage', 'real_usage', '5.1', [52], '5.2'],
            ['mb_decode_numericentity', 'is_hex', '5.3', [120], '5.4'],
            ['mb_encode_numericentity', 'hex', '5.3', [53], '5.4'],
            ['mb_strrpos', 'offset', '5.1', [54, 136], '5.2'],
            ['mssql_connect', 'new_link', '5.0', [55], '5.1'],
            ['mysqli_commit', 'flags', '5.4', [56], '5.5'],
            ['mysqli_commit', 'name', '5.4', [56], '5.5'],
            ['mysqli_rollback', 'flags', '5.4', [57], '5.5'],
            ['mysqli_rollback', 'name', '5.4', [57], '5.5'],
            ['nl2br', 'use_xhtml', '5.2', [58], '5.3'],
            ['openssl_decrypt', 'iv', '5.3.2', [59], '7.1', '5.3'], // OK version > version in which last parameter was added to the function.
            ['openssl_decrypt', 'tag', '7.0', [59], '7.1'],
            ['openssl_decrypt', 'aad', '7.0', [59], '7.1'],
            ['openssl_encrypt', 'iv', '5.3.2', [60], '7.1', '5.3'], // OK version > version in which last parameter was added to the function.
            ['openssl_encrypt', 'tag', '7.0', [60], '7.1'],
            ['openssl_encrypt', 'aad', '7.0', [60], '7.1'],
            ['openssl_encrypt', 'tag_length', '7.0', [60], '7.1'],
            ['openssl_open', 'cipher_algo', '5.2', [103], '7.0'], // OK version > version in which last parameter was added to the function.
            ['openssl_open', 'iv', '5.6', [103], '7.0'],
            ['openssl_pkcs7_verify', 'content', '5.0', [61], '7.2'], // OK version > version in which last parameter was added to the function.
            ['openssl_pkcs7_verify', 'output_filename', '7.1', [61], '7.2'],
            ['openssl_seal', 'cipher_algo', '5.2', [62], '7.0'], // OK version > version in which last parameter was added to the function.
            ['openssl_seal', 'iv', '5.6', [62], '7.0'],
            ['openssl_verify', 'algorithm', '5.1', [63], '5.2'],
            ['parse_ini_file', 'scanner_mode', '5.2', [64], '5.3'],
            ['parse_url', 'component', '5.1.1', [65], '5.2', '5.1'],
            ['pg_escape_bytea', 'connection', '5.1', [123], '5.2'],
            ['pg_escape_string', 'connection', '5.1', [124], '5.2'],
            ['pg_fetch_all', 'mode', '7.0', [99], '7.1'],
            ['pg_last_notice', 'mode', '7.0', [100], '7.1'],
            ['pg_lo_create', 'oid', '5.2', [66], '5.3'],
            ['pg_lo_import', 'oid', '5.2', [67], '5.3'],
            ['pg_meta_data', 'extended', '5.5', [125], '5.6'],
            ['pg_select', 'mode', '7.0', [101], '7.1'],
            ['php_uname', 'mode', '4.2', [104], '4.3'],
            ['preg_replace', 'count', '5.0', [68], '5.1'],
            ['preg_replace_callback', 'count', '5.0', [69], '7.4'], // OK version > version in which last parameter was added to the function.
            ['preg_replace_callback', 'flags', '7.3', [69], '7.4'],
            ['preg_replace_callback_array', 'flags', '7.3', [118], '7.4'],
            ['round', 'mode', '5.2', [70], '5.3'],
            ['sem_acquire', 'non_blocking', '5.6.0', [71], '7.0', '5.6'],
            ['session_regenerate_id', 'delete_old_session', '5.0', [72], '5.1'],
            ['session_set_cookie_params', 'httponly', '5.1', [73], '5.2'],
            ['session_set_save_handler', 'create_sid', '5.5.0', [74], '7.0', '5.5'], // OK version > version in which last parameter was added to the function.
            ['session_set_save_handler', 'validate_sid', '5.6', [74], '7.0'],
            ['session_set_save_handler', 'update_timestamp', '5.6', [74], '7.0'],
            ['session_start', 'options', '5.6', [75], '7.0'],
            ['setcookie', 'httponly', '5.1', [76], '5.2'],
            ['setrawcookie', 'httponly', '5.1', [77], '5.2'],
            ['simplexml_load_file', 'is_prefix', '5.1', [78], '5.2'],
            ['simplexml_load_string', 'is_prefix', '5.1', [79], '5.2'],
            ['spl_autoload_register', 'prepend', '5.2', [80], '5.3'],
            ['stream_context_create', 'params', '5.2', [81], '5.3'],
            ['stream_copy_to_stream', 'offset', '5.0', [82], '5.1'],
            ['stream_get_contents', 'offset', '5.0', [83], '5.1'],
            ['stream_wrapper_register', 'flags', '5.2.3', [84], '5.3', '5.2'],
            ['stristr', 'before_needle', '5.2', [85], '5.3'],
            ['strstr', 'before_needle', '5.2', [86], '5.3'],
            ['str_word_count', 'characters', '5.0', [87], '5.1'],
            ['substr_count', 'offset', '5.0', [88], '5.1'],
            ['substr_count', 'length', '5.0', [88], '5.1'],
            ['sybase_connect', 'new', '5.2', [89], '5.3'],
            ['timezone_transitions_get', 'timestampBegin', '5.2', [90], '5.3'],
            ['timezone_transitions_get', 'timestampEnd', '5.2', [90], '5.3'],
            ['timezone_identifiers_list', 'timezoneGroup', '5.2', [91], '5.3'],
            ['timezone_identifiers_list', 'countryCode', '5.2', [91], '5.3'],
            ['token_get_all', 'flags', '5.6', [92], '7.0'],
            ['ucwords', 'separators', '5.4.31', [93, 133], '5.6', '5.4'], // Function introduced in 5.4.31 and 5.5.15.
            ['unpack', 'offset', '7.0', [102], '7.1'],
            ['unserialize', 'options', '5.6', [94], '7.0'],
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
        $file = $this->sniffFile(__FILE__, '5.0'); // Low version below the first addition.
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
        return [
            [4],
            [139],
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
