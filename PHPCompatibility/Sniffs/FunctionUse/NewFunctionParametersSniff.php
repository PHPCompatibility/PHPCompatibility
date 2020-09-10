<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionUse;

use PHPCompatibility\AbstractNewFeatureSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Detect use of new function parameters in calls to native PHP functions.
 *
 * PHP version All
 *
 * @link https://www.php.net/manual/en/doc.changelog.php
 *
 * @since 7.0.0
 * @since 7.1.0 Now extends the `AbstractNewFeatureSniff` instead of the base `Sniff` class..
 */
class NewFunctionParametersSniff extends AbstractNewFeatureSniff
{

    /**
     * A list of functions which have new parameters, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * The index is the location of the parameter in the parameter list, starting at 0 !
     * If's sufficient to list the first version where the function appears.
     *
     * @since 7.0.0
     * @since 7.0.2 Visibility changed from `public` to `protected`.
     *
     * @var array
     */
    protected $newFunctionParameters = [
        'array_filter' => [
            2 => [
                'name' => 'flag',
                '5.5'  => false,
                '5.6'  => true,
            ],
        ],
        'array_slice' => [
            1 => [
                'name'  => 'preserve_keys',
                '5.0.1' => false,
                '5.0.2' => true,
            ],
        ],
        'array_unique' => [
            1 => [
                'name'  => 'sort_flags',
                '5.2.8' => false,
                '5.2.9' => true,
            ],
        ],
        'assert' => [
            1 => [
                'name'  => 'description',
                '5.4.7' => false,
                '5.4.8' => true,
            ],
        ],
        'base64_decode' => [
            1 => [
                'name' => 'strict',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'bcmod' => [
            2 => [
                'name' => 'scale',
                '7.1'  => false,
                '7.2'  => true,
            ],
        ],
        'class_implements' => [
            1 => [
                'name' => 'autoload',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'class_parents' => [
            1 => [
                'name' => 'autoload',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'clearstatcache' => [
            0 => [
                'name' => 'clear_realpath_cache',
                '5.2'  => false,
                '5.3'  => true,
            ],
            1 => [
                'name' => 'filename',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'copy' => [
            2 => [
                'name' => 'context',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'curl_multi_info_read' => [
            1 => [
                'name' => 'msgs_in_queue',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'date_time_set' => [
            4 => [
                'name' => 'microseconds',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'debug_backtrace' => [
            0 => [
                'name'  => 'options',
                '5.2.4' => false,
                '5.2.5' => true,
            ],
            1 => [
                'name' => 'limit',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'debug_print_backtrace' => [
            0 => [
                'name'  => 'options',
                '5.3.5' => false,
                '5.3.6' => true,
            ],
            1 => [
                'name' => 'limit',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'dirname' => [
            1 => [
                'name' => 'levels',
                '5.6'  => false,
                '7.0'  => true,
            ],
        ],
        'dns_get_record' => [
            4 => [
                'name' => 'raw',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'fgetcsv' => [
            4 => [
                'name' => 'escape',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'fputcsv' => [
            4 => [
                'name'  => 'escape_char',
                '5.5.3' => false,
                '5.5.4' => true,
            ],
        ],
        'file_get_contents' => [
            3 => [
                'name' => 'offset',
                '5.0'  => false,
                '5.1'  => true,
            ],
            4 => [
                'name' => 'maxlen',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'filter_input_array' => [
            2 => [
                'name' => 'add_empty',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'filter_var_array' => [
            2 => [
                'name' => 'add_empty',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'getenv' => [
            1 => [
                'name'   => 'local_only',
                '5.5.37' => false,
                '5.5.38' => true, // Also introduced in PHP 5.6.24 and 7.0.9.
            ],
        ],
        'getopt' => [
            2 => [
                'name' => 'optind',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'gettimeofday' => [
            0 => [
                'name' => 'return_float',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'get_defined_functions' => [
            0 => [
                'name'   => 'exclude_disabled',
                '7.0.14' => false,
                '7.0.15' => true,
            ],
        ],
        'get_headers' => [
            2 => [
                'name' => 'context',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'get_html_translation_table' => [
            2 => [
                'name'  => 'encoding',
                '5.3.3' => false,
                '5.3.4' => true,
            ],
        ],
        'get_loaded_extensions' => [
            0 => [
                'name'  => 'zend_extensions',
                '5.2.3' => false,
                '5.2.4' => true,
            ],
        ],
        'gzcompress' => [
            2 => [
                'name' => 'encoding',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'gzdeflate' => [
            2 => [
                'name' => 'encoding',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'htmlentities' => [
            3 => [
                'name'  => 'double_encode',
                '5.2.2' => false,
                '5.2.3' => true,
            ],
        ],
        'htmlspecialchars' => [
            3 => [
                'name'  => 'double_encode',
                '5.2.2' => false,
                '5.2.3' => true,
            ],
        ],
        'http_build_query' => [
            2 => [
                'name'  => 'arg_separator',
                '5.1.1' => false,
                '5.1.2' => true,
            ],
            3 => [
                'name' => 'enc_type',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'idn_to_ascii' => [
            2 => [
                'name' => 'variant',
                '5.3'  => false,
                '5.4'  => true,
            ],
            3 => [
                'name' => 'idna_info',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'idn_to_utf8' => [
            2 => [
                'name' => 'variant',
                '5.3'  => false,
                '5.4'  => true,
            ],
            3 => [
                'name' => 'idna_info',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'imagecolorset' => [
            5 => [
                'name' => 'alpha',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'imagepng' => [
            2 => [
                'name'  => 'quality',
                '5.1.1' => false,
                '5.1.2' => true,
            ],
            3 => [
                'name'  => 'filters',
                '5.1.2' => false,
                '5.1.3' => true,
            ],
        ],
        'imagerotate' => [
            3 => [
                'name' => 'ignore_transparent',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'imap_open' => [
            4 => [
                'name' => 'n_retries',
                '5.1'  => false,
                '5.2'  => true,
            ],
            5 => [
                'name'  => 'params',
                '5.3.1' => false,
                '5.3.2' => true,
            ],
        ],
        'imap_reopen' => [
            3 => [
                'name' => 'n_retries',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'ini_get_all' => [
            1 => [
                'name' => 'details',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'is_a' => [
            2 => [
                'name'  => 'allow_string',
                '5.3.8' => false,
                '5.3.9' => true,
            ],
        ],
        'is_subclass_of' => [
            2 => [
                'name'  => 'allow_string',
                '5.3.8' => false,
                '5.3.9' => true,
            ],
        ],
        'iterator_to_array' => [
            1 => [
                'name'  => 'use_keys',
                '5.2.0' => false,
                '5.2.1' => true,
            ],
        ],
        'json_decode' => [
            2 => [
                'name' => 'depth',
                '5.2'  => false,
                '5.3'  => true,
            ],
            3 => [
                'name' => 'options',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'json_encode' => [
            1 => [
                'name' => 'options',
                '5.2'  => false,
                '5.3'  => true,
            ],
            2 => [
                'name' => 'depth',
                '5.4'  => false,
                '5.5'  => true,
            ],
        ],
        'ldap_add' => [
            3 => [
                'name' => 'serverctrls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_compare' => [
            4 => [
                'name' => 'serverctrls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_delete' => [
            2 => [
                'name' => 'serverctrls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_exop' => [
            3 => [
                'name' => 'serverctrls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_exop_passwd' => [
            4 => [
                'name' => 'serverctrls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_list' => [
            8 => [
                'name' => 'serverctrls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_mod_add' => [
            3 => [
                'name' => 'serverctrls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_mod_del' => [
            3 => [
                'name' => 'serverctrls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_mod_replace' => [
            3 => [
                'name' => 'serverctrls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_modify_batch' => [
            3 => [
                'name' => 'serverctrls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_parse_result' => [
            6 => [
                'name' => 'serverctrls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_read' => [
            8 => [
                'name' => 'serverctrls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_rename' => [
            5 => [
                'name' => 'serverctrls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_search' => [
            8 => [
                'name' => 'serverctrls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'memory_get_peak_usage' => [
            0 => [
                'name' => 'real_usage',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'memory_get_usage' => [
            0 => [
                'name' => 'real_usage',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'mb_decode_numericentity' => [
            3 => [
                'name' => 'is_hex',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'mb_encode_numericentity' => [
            3 => [
                'name' => 'is_hex',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'mb_strrpos' => [
            /*
             * Note: the actual position is 2, but the original 3rd
             * parameter 'encoding' was moved to the 4th position.
             * So the only way to detect if offset is used is when
             * both offset and encoding are set.
             */
            3 => [
                'name' => 'offset',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'mssql_connect' => [
            3 => [
                'name' => 'new_link',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'mysqli_commit' => [
            1 => [
                'name' => 'flags',
                '5.4'  => false,
                '5.5'  => true,
            ],
            2 => [
                'name' => 'name',
                '5.4'  => false,
                '5.5'  => true,
            ],
        ],
        'mysqli_rollback' => [
            1 => [
                'name' => 'flags',
                '5.4'  => false,
                '5.5'  => true,
            ],
            2 => [
                'name' => 'name',
                '5.4'  => false,
                '5.5'  => true,
            ],
        ],
        'nl2br' => [
            1 => [
                'name' => 'is_xhtml',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'openssl_decrypt' => [
            4 => [
                'name'  => 'iv',
                '5.3.2' => false,
                '5.3.3' => true,
            ],
            5 => [
                'name' => 'tag',
                '7.0'  => false,
                '7.1'  => true,
            ],
            6 => [
                'name' => 'aad',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'openssl_encrypt' => [
            4 => [
                'name'  => 'iv',
                '5.3.2' => false,
                '5.3.3' => true,
            ],
            5 => [
                'name' => 'tag',
                '7.0'  => false,
                '7.1'  => true,
            ],
            6 => [
                'name' => 'aad',
                '7.0'  => false,
                '7.1'  => true,
            ],
            7 => [
                'name' => 'tag_length',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'openssl_open' => [
            4 => [
                'name' => 'method',
                '5.2'  => false,
                '5.3'  => true,
            ],
            5 => [
                'name' => 'iv',
                '5.6'  => false,
                '7.0'  => true,
            ],
        ],
        'openssl_pkcs7_verify' => [
            5 => [
                'name' => 'content',
                '5.0'  => false,
                '5.1'  => true,
            ],
            6 => [
                'name' => 'p7bfilename',
                '7.1'  => false,
                '7.2'  => true,
            ],
        ],
        'openssl_seal' => [
            4 => [
                'name' => 'method',
                '5.2'  => false,
                '5.3'  => true,
            ],
            5 => [
                'name' => 'iv',
                '5.6'  => false,
                '7.0'  => true,
            ],
        ],
        'openssl_verify' => [
            3 => [
                'name' => 'signature_alg',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'parse_ini_file' => [
            2 => [
                'name' => 'scanner_mode',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'parse_url' => [
            1 => [
                'name'  => 'component',
                '5.1.1' => false,
                '5.1.2' => true,
            ],
        ],
        'pg_escape_bytea' => [
            /*
             * Is in actual fact the first parameter (0), with a second required param.
             * So we need to check for two parameters being present.
             */
            1 => [
                'name' => 'connection',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'pg_escape_string' => [
            /*
             * Is in actual fact the first parameter (0), with a second required param.
             * So we need to check for two parameters being present.
             */
            1 => [
                'name' => 'connection',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'pg_fetch_all' => [
            1 => [
                'name' => 'result_type',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'pg_last_notice' => [
            1 => [
                'name' => 'option',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'pg_lo_create' => [
            1 => [
                'name' => 'object_id',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'pg_lo_import' => [
            2 => [
                'name' => 'object_id',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'pg_meta_data' => [
            2 => [
                'name' => 'extended',
                '5.5'  => false,
                '5.6'  => true,
            ],
        ],
        'pg_select' => [
            4 => [
                'name' => 'result_type',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'php_uname' => [
            0 => [
                'name' => 'mode',
                '4.2'  => false,
                '4.3'  => true,
            ],
        ],
        'preg_replace' => [
            4 => [
                'name' => 'count',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'preg_replace_callback' => [
            4 => [
                'name' => 'count',
                '5.0'  => false,
                '5.1'  => true,
            ],
            5 => [
                'name' => 'flags',
                '7.3'  => false,
                '7.4'  => true,
            ],
        ],
        'preg_replace_callback_array' => [
            4 => [
                'name' => 'flags',
                '7.3'  => false,
                '7.4'  => true,
            ],
        ],
        'round' => [
            2 => [
                'name' => 'mode',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'sem_acquire' => [
            1 => [
                'name'  => 'nowait',
                '5.6.0' => false,
                '5.6.1' => true,
            ],
        ],
        'session_regenerate_id' => [
            0 => [
                'name' => 'delete_old_session',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'session_set_cookie_params' => [
            4 => [
                'name' => 'httponly',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'session_set_save_handler' => [
            6 => [
                'name'  => 'create_sid',
                '5.5.0' => false,
                '5.5.1' => true,
            ],
            7 => [
                'name' => 'validate_sid',
                '5.6'  => false,
                '7.0'  => true,
            ],
            8 => [
                'name' => 'update_timestamp',
                '5.6'  => false,
                '7.0'  => true,
            ],
        ],
        'session_start' => [
            0 => [
                'name' => 'options',
                '5.6'  => false,
                '7.0'  => true,
            ],
        ],
        'setcookie' => [
            6 => [
                'name' => 'httponly',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'setrawcookie' => [
            6 => [
                'name' => 'httponly',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'simplexml_load_file' => [
            4 => [
                'name' => 'is_prefix',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'simplexml_load_string' => [
            4 => [
                'name' => 'is_prefix',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'spl_autoload_register' => [
            2 => [
                'name' => 'prepend',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'stream_context_create' => [
            1 => [
                'name' => 'params',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'stream_copy_to_stream' => [
            3 => [
                'name' => 'offset',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'stream_get_contents' => [
            2 => [
                'name' => 'offset',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'stream_wrapper_register' => [
            2 => [
                'name'  => 'flags',
                '5.2.3' => false,
                '5.2.4' => true,
            ],
        ],
        'stristr' => [
            2 => [
                'name' => 'before_needle',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'strstr' => [
            2 => [
                'name' => 'before_needle',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'str_word_count' => [
            2 => [
                'name' => 'charlist',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'substr_count' => [
            2 => [
                'name' => 'offset',
                '5.0'  => false,
                '5.1'  => true,
            ],
            3 => [
                'name' => 'length',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'sybase_connect' => [
            5 => [
                'name' => 'new',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'timezone_transitions_get' => [
            1 => [
                'name' => 'timestamp_begin',
                '5.2'  => false,
                '5.3'  => true,
            ],
            2 => [
                'name' => 'timestamp_end',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'timezone_identifiers_list' => [
            0 => [
                'name' => 'what',
                '5.2'  => false,
                '5.3'  => true,
            ],
            1 => [
                'name' => 'country',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'token_get_all' => [
            1 => [
                'name' => 'flags',
                '5.6'  => false,
                '7.0'  => true,
            ],
        ],
        'ucwords' => [
            1 => [
                'name'   => 'delimiters',
                '5.4.31' => false,
                '5.5.15' => false,
                '5.4.32' => true,
                '5.5.16' => true,
            ],
        ],
        'unpack' => [
            2 => [
                'name' => 'offset',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'unserialize' => [
            1 => [
                'name' => 'options',
                '5.6'  => false,
                '7.0'  => true,
            ],
        ],
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.0
     *
     * @return array
     */
    public function register()
    {
        // Handle case-insensitivity of function names.
        $this->newFunctionParameters = \array_change_key_case($this->newFunctionParameters, \CASE_LOWER);

        return [\T_STRING];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $function   = $tokens[$stackPtr]['content'];
        $functionLc = \strtolower($function);

        if (isset($this->newFunctionParameters[$functionLc]) === false) {
            return;
        }

        $nextToken = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($nextToken === false
            || $tokens[$nextToken]['code'] !== \T_OPEN_PARENTHESIS
            || isset($tokens[$nextToken]['parenthesis_owner']) === true
        ) {
            return;
        }

        $ignore = [
            \T_DOUBLE_COLON    => true,
            \T_OBJECT_OPERATOR => true,
            \T_NEW             => true,
        ];

        $prevToken = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if (isset($ignore[$tokens[$prevToken]['code']]) === true) {
            // Not a call to a PHP function.
            return;

        } elseif ($tokens[$prevToken]['code'] === \T_NS_SEPARATOR) {
            $prevPrevToken = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($prevToken - 1), null, true);
            if ($tokens[$prevPrevToken]['code'] === \T_STRING
                || $tokens[$prevPrevToken]['code'] === \T_NAMESPACE
            ) {
                // Namespaced function.
                return;
            }
        }

        $parameterCount = PassedParameters::getParameterCount($phpcsFile, $stackPtr);
        if ($parameterCount === 0) {
            return;
        }

        // If the parameter count returned > 0, we know there will be valid open parenthesis.
        $openParenthesis      = $phpcsFile->findNext(Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        $parameterOffsetFound = $parameterCount - 1;

        foreach ($this->newFunctionParameters[$functionLc] as $offset => $parameterDetails) {
            if ($offset <= $parameterOffsetFound) {
                $itemInfo = [
                    'name'   => $function,
                    'nameLc' => $functionLc,
                    'offset' => $offset,
                ];
                $this->handleFeature($phpcsFile, $openParenthesis, $itemInfo);
            }
        }
    }


    /**
     * Get the relevant sub-array for a specific item from a multi-dimensional array.
     *
     * @since 7.1.0
     *
     * @param array $itemInfo Base information about the item.
     *
     * @return array Version and other information about the item.
     */
    public function getItemArray(array $itemInfo)
    {
        return $this->newFunctionParameters[$itemInfo['nameLc']][$itemInfo['offset']];
    }


    /**
     * Get an array of the non-PHP-version array keys used in a sub-array.
     *
     * @since 7.1.0
     *
     * @return array
     */
    protected function getNonVersionArrayKeys()
    {
        return ['name'];
    }


    /**
     * Retrieve the relevant detail (version) information for use in an error message.
     *
     * @since 7.1.0
     *
     * @param array $itemArray Version and other information about the item.
     * @param array $itemInfo  Base information about the item.
     *
     * @return array
     */
    public function getErrorInfo(array $itemArray, array $itemInfo)
    {
        $errorInfo              = parent::getErrorInfo($itemArray, $itemInfo);
        $errorInfo['paramName'] = $itemArray['name'];

        return $errorInfo;
    }


    /**
     * Get the item name to be used for the creation of the error code.
     *
     * @since 7.1.0
     *
     * @param array $itemInfo  Base information about the item.
     * @param array $errorInfo Detail information about an item.
     *
     * @return string
     */
    protected function getItemName(array $itemInfo, array $errorInfo)
    {
        return $itemInfo['name'] . '_' . $errorInfo['paramName'];
    }


    /**
     * Get the error message template for this sniff.
     *
     * @since 7.1.0
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return 'The function %s() does not have a parameter "%s" in PHP version %s or earlier';
    }


    /**
     * Allow for concrete child classes to filter the error data before it's passed to PHPCS.
     *
     * @since 7.1.0
     *
     * @param array $data      The error data array which was created.
     * @param array $itemInfo  Base information about the item this error message applies to.
     * @param array $errorInfo Detail information about an item this error message applies to.
     *
     * @return array
     */
    protected function filterErrorData(array $data, array $itemInfo, array $errorInfo)
    {
        \array_shift($data);
        \array_unshift($data, $itemInfo['name'], $errorInfo['paramName']);
        return $data;
    }
}
