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

use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait;
use PHPCompatibility\Helpers\ScannedCode;
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
 * @since 7.1.0  Now extends the `AbstractNewFeatureSniff` instead of the base `Sniff` class..
 * @since 10.0.0 Now extends the base `AbstractFunctionCallParameterSniff` class
 *               and uses the `ComplexVersionNewFeatureTrait`.
 */
class NewFunctionParametersSniff extends AbstractFunctionCallParameterSniff
{
    use ComplexVersionNewFeatureTrait;

    /**
     * A list of functions which have new parameters, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * The index is the 1-based parameter position of the parameter in the parameter list.
     * If's sufficient to list the first version where the function appears.
     *
     * @since 7.0.0
     * @since 7.0.2 Visibility changed from `public` to `protected`.
     * @since 10.0.0 - The parameter offsets were changed from 0-based to 1-based.
     *               - The property was renamed from `$newFunctionParameters` to `$targetFunctions`.
     *
     * @var array<string, array<int, array<string, bool|string>>>
     */
    protected $targetFunctions = [
        'array_filter' => [
            3 => [
                'name' => 'mode',
                '5.5'  => false,
                '5.6'  => true,
            ],
        ],
        'array_slice' => [
            4 => [
                'name'  => 'preserve_keys',
                '5.0.1' => false,
                '5.0.2' => true,
            ],
        ],
        'array_unique' => [
            2 => [
                'name'  => 'flags',
                '5.2.8' => false,
                '5.2.9' => true,
            ],
        ],
        'assert' => [
            2 => [
                'name'  => 'description',
                '5.4.7' => false,
                '5.4.8' => true,
            ],
        ],
        'base64_decode' => [
            2 => [
                'name' => 'strict',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'bcmod' => [
            3 => [
                'name' => 'scale',
                '7.1'  => false,
                '7.2'  => true,
            ],
        ],
        'class_implements' => [
            2 => [
                'name' => 'autoload',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'class_parents' => [
            2 => [
                'name' => 'autoload',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'clearstatcache' => [
            1 => [
                'name' => 'clear_realpath_cache',
                '5.2'  => false,
                '5.3'  => true,
            ],
            2 => [
                'name' => 'filename',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'copy' => [
            3 => [
                'name' => 'context',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'curl_multi_info_read' => [
            2 => [
                'name' => 'queued_messages',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'date_time_set' => [
            5 => [
                'name' => 'microsecond',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'debug_backtrace' => [
            1 => [
                'name'  => 'options',
                '5.2.4' => false,
                '5.2.5' => true,
            ],
            2 => [
                'name' => 'limit',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'debug_print_backtrace' => [
            1 => [
                'name'  => 'options',
                '5.3.5' => false,
                '5.3.6' => true,
            ],
            2 => [
                'name' => 'limit',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'dirname' => [
            2 => [
                'name' => 'levels',
                '5.6'  => false,
                '7.0'  => true,
            ],
        ],
        'dns_get_record' => [
            5 => [
                'name' => 'raw',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'fgetcsv' => [
            5 => [
                'name' => 'escape',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'file_get_contents' => [
            4 => [
                'name' => 'offset',
                '5.0'  => false,
                '5.1'  => true,
            ],
            5 => [
                'name' => 'length',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'filter_input_array' => [
            3 => [
                'name' => 'add_empty',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'filter_var_array' => [
            3 => [
                'name' => 'add_empty',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'fputcsv' => [
            5 => [
                'name'  => 'escape',
                '5.5.3' => false,
                '5.5.4' => true,
            ],
            6 => [
                'name' => 'eol',
                '8.0'  => false,
                '8.1'  => true,
            ],
        ],
        'getenv' => [
            2 => [
                'name'   => 'local_only',
                '5.5.37' => false,
                '5.5.38' => true, // Also introduced in PHP 5.6.24 and 7.0.9.
            ],
        ],
        'getopt' => [
            3 => [
                'name' => 'rest_index',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'gettimeofday' => [
            1 => [
                'name' => 'as_float',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'get_defined_functions' => [
            1 => [
                'name'   => 'exclude_disabled',
                '7.0.14' => false,
                '7.0.15' => true,
            ],
        ],
        'get_headers' => [
            3 => [
                'name' => 'context',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'get_html_translation_table' => [
            3 => [
                'name'  => 'encoding',
                '5.3.3' => false,
                '5.3.4' => true,
            ],
        ],
        'get_loaded_extensions' => [
            1 => [
                'name'  => 'zend_extensions',
                '5.2.3' => false,
                '5.2.4' => true,
            ],
        ],
        'gzcompress' => [
            3 => [
                'name' => 'encoding',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'gzdeflate' => [
            3 => [
                'name' => 'encoding',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'hash' => [
            4 => [
                'name' => 'options',
                '8.0'  => false,
                '8.1'  => true,
            ],
        ],
        'hash_file' => [
            4 => [
                'name' => 'options',
                '8.0'  => false,
                '8.1'  => true,
            ],
        ],
        'hash_init' => [
            4 => [
                'name' => 'options',
                '8.0'  => false,
                '8.1'  => true,
            ],
        ],
        'htmlentities' => [
            4 => [
                'name'  => 'double_encode',
                '5.2.2' => false,
                '5.2.3' => true,
            ],
        ],
        'htmlspecialchars' => [
            4 => [
                'name'  => 'double_encode',
                '5.2.2' => false,
                '5.2.3' => true,
            ],
        ],
        'http_build_query' => [
            3 => [
                'name'  => 'arg_separator',
                '5.1.1' => false,
                '5.1.2' => true,
            ],
            4 => [
                'name' => 'encoding_type',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'idn_to_ascii' => [
            3 => [
                'name' => 'variant',
                '5.3'  => false,
                '5.4'  => true,
            ],
            4 => [
                'name' => 'idna_info',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'idn_to_utf8' => [
            3 => [
                'name' => 'variant',
                '5.3'  => false,
                '5.4'  => true,
            ],
            4 => [
                'name' => 'idna_info',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'imagecolorset' => [
            6 => [
                'name' => 'alpha',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'imagepng' => [
            3 => [
                'name'  => 'quality',
                '5.1.1' => false,
                '5.1.2' => true,
            ],
            4 => [
                'name'  => 'filters',
                '5.1.2' => false,
                '5.1.3' => true,
            ],
        ],
        'imagerotate' => [
            4 => [
                'name' => 'ignore_transparent',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'imap_open' => [
            5 => [
                'name' => 'retries',
                '5.1'  => false,
                '5.2'  => true,
            ],
            6 => [
                'name'  => 'options',
                '5.3.1' => false,
                '5.3.2' => true,
            ],
        ],
        'imap_reopen' => [
            4 => [
                'name' => 'retries',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'ini_get_all' => [
            2 => [
                'name' => 'details',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'is_a' => [
            3 => [
                'name'  => 'allow_string',
                '5.3.8' => false,
                '5.3.9' => true,
            ],
        ],
        'is_subclass_of' => [
            3 => [
                'name'  => 'allow_string',
                '5.3.8' => false,
                '5.3.9' => true,
            ],
        ],
        'iterator_to_array' => [
            2 => [
                'name'  => 'preserve_keys',
                '5.2.0' => false,
                '5.2.1' => true,
            ],
        ],
        'json_decode' => [
            3 => [
                'name' => 'depth',
                '5.2'  => false,
                '5.3'  => true,
            ],
            4 => [
                'name' => 'flags',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'json_encode' => [
            2 => [
                'name' => 'flags',
                '5.2'  => false,
                '5.3'  => true,
            ],
            3 => [
                'name' => 'depth',
                '5.4'  => false,
                '5.5'  => true,
            ],
        ],
        'ldap_add' => [
            4 => [
                'name' => 'controls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_compare' => [
            5 => [
                'name' => 'controls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_delete' => [
            3 => [
                'name' => 'controls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_exop' => [
            4 => [
                'name' => 'controls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_exop_passwd' => [
            5 => [
                'name' => 'controls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_list' => [
            9 => [
                'name' => 'controls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_mod_add' => [
            4 => [
                'name' => 'controls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_mod_del' => [
            4 => [
                'name' => 'controls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_mod_replace' => [
            4 => [
                'name' => 'controls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_modify_batch' => [
            4 => [
                'name' => 'controls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_parse_result' => [
            7 => [
                'name' => 'controls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_read' => [
            9 => [
                'name' => 'controls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_rename' => [
            6 => [
                'name' => 'controls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'ldap_search' => [
            9 => [
                'name' => 'controls',
                '7.2'  => false,
                '7.3'  => true,
            ],
        ],
        'memory_get_peak_usage' => [
            1 => [
                'name' => 'real_usage',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'memory_get_usage' => [
            1 => [
                'name' => 'real_usage',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'mb_decode_numericentity' => [
            4 => [
                'name' => 'is_hex',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'mb_encode_numericentity' => [
            4 => [
                'name' => 'hex',
                '5.3'  => false,
                '5.4'  => true,
            ],
        ],
        'mb_strrpos' => [
            /*
             * Note: the actual position is 3, but the original 3rd
             * parameter 'encoding' was moved to the 4th position.
             * So the only way to detect if offset is used is when
             * both offset and encoding are set.
             */
            4 => [
                'name' => 'offset',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'mssql_connect' => [
            4 => [
                'name' => 'new_link',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'mysqli_commit' => [
            2 => [
                'name' => 'flags',
                '5.4'  => false,
                '5.5'  => true,
            ],
            3 => [
                'name' => 'name',
                '5.4'  => false,
                '5.5'  => true,
            ],
        ],
        'mysqli_rollback' => [
            2 => [
                'name' => 'flags',
                '5.4'  => false,
                '5.5'  => true,
            ],
            3 => [
                'name' => 'name',
                '5.4'  => false,
                '5.5'  => true,
            ],
        ],
        'nl2br' => [
            2 => [
                'name' => 'use_xhtml',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'openssl_decrypt' => [
            5 => [
                'name'  => 'iv',
                '5.3.2' => false,
                '5.3.3' => true,
            ],
            6 => [
                'name' => 'tag',
                '7.0'  => false,
                '7.1'  => true,
            ],
            7 => [
                'name' => 'aad',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'openssl_encrypt' => [
            5 => [
                'name'  => 'iv',
                '5.3.2' => false,
                '5.3.3' => true,
            ],
            6 => [
                'name' => 'tag',
                '7.0'  => false,
                '7.1'  => true,
            ],
            7 => [
                'name' => 'aad',
                '7.0'  => false,
                '7.1'  => true,
            ],
            8 => [
                'name' => 'tag_length',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'openssl_open' => [
            5 => [
                'name' => 'cipher_algo',
                '5.2'  => false,
                '5.3'  => true,
            ],
            6 => [
                'name' => 'iv',
                '5.6'  => false,
                '7.0'  => true,
            ],
        ],
        'openssl_pkcs7_verify' => [
            6 => [
                'name' => 'content',
                '5.0'  => false,
                '5.1'  => true,
            ],
            7 => [
                'name' => 'output_filename',
                '7.1'  => false,
                '7.2'  => true,
            ],
        ],
        'openssl_seal' => [
            5 => [
                'name' => 'cipher_algo',
                '5.2'  => false,
                '5.3'  => true,
            ],
            6 => [
                'name' => 'iv',
                '5.6'  => false,
                '7.0'  => true,
            ],
        ],
        'openssl_verify' => [
            4 => [
                'name' => 'algorithm',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'parse_ini_file' => [
            3 => [
                'name' => 'scanner_mode',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'parse_url' => [
            2 => [
                'name'  => 'component',
                '5.1.1' => false,
                '5.1.2' => true,
            ],
        ],
        'posix_getrlimit' => [
            1 => [
                'name' => 'resource',
                '8.2'  => false,
                '8.3'  => true,
            ],
        ],
        'pg_escape_bytea' => [
            /*
             * Is in actual fact the first parameter, with a second required param.
             * So we need to check for two parameters being present.
             */
            2 => [
                'name' => 'connection',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'pg_escape_string' => [
            /*
             * Is in actual fact the first parameter, with a second required param.
             * So we need to check for two parameters being present.
             */
            2 => [
                'name' => 'connection',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'pg_fetch_all' => [
            2 => [
                'name' => 'mode',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'pg_last_notice' => [
            2 => [
                'name' => 'mode',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'pg_lo_create' => [
            2 => [
                'name' => 'oid',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'pg_lo_import' => [
            3 => [
                'name' => 'oid',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'pg_meta_data' => [
            3 => [
                'name' => 'extended',
                '5.5'  => false,
                '5.6'  => true,
            ],
        ],
        'pg_select' => [
            5 => [
                'name' => 'mode',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'php_uname' => [
            1 => [
                'name' => 'mode',
                '4.2'  => false,
                '4.3'  => true,
            ],
        ],
        'preg_replace' => [
            5 => [
                'name' => 'count',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'preg_replace_callback' => [
            5 => [
                'name' => 'count',
                '5.0'  => false,
                '5.1'  => true,
            ],
            6 => [
                'name' => 'flags',
                '7.3'  => false,
                '7.4'  => true,
            ],
        ],
        'preg_replace_callback_array' => [
            5 => [
                'name' => 'flags',
                '7.3'  => false,
                '7.4'  => true,
            ],
        ],
        'round' => [
            3 => [
                'name' => 'mode',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'sem_acquire' => [
            2 => [
                'name'  => 'non_blocking',
                '5.6.0' => false,
                '5.6.1' => true,
            ],
        ],
        'session_regenerate_id' => [
            1 => [
                'name' => 'delete_old_session',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'session_set_cookie_params' => [
            5 => [
                'name' => 'httponly',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'session_set_save_handler' => [
            7 => [
                'name'  => 'create_sid',
                '5.5.0' => false,
                '5.5.1' => true,
            ],
            8 => [
                'name' => 'validate_sid',
                '5.6'  => false,
                '7.0'  => true,
            ],
            9 => [
                'name' => 'update_timestamp',
                '5.6'  => false,
                '7.0'  => true,
            ],
        ],
        'session_start' => [
            1 => [
                'name' => 'options',
                '5.6'  => false,
                '7.0'  => true,
            ],
        ],
        'setcookie' => [
            7 => [
                'name' => 'httponly',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'setrawcookie' => [
            7 => [
                'name' => 'httponly',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'simplexml_load_file' => [
            5 => [
                'name' => 'is_prefix',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'simplexml_load_string' => [
            5 => [
                'name' => 'is_prefix',
                '5.1'  => false,
                '5.2'  => true,
            ],
        ],
        'spl_autoload_register' => [
            3 => [
                'name' => 'prepend',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'stream_context_create' => [
            2 => [
                'name' => 'params',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'stream_copy_to_stream' => [
            4 => [
                'name' => 'offset',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'stream_get_contents' => [
            3 => [
                'name' => 'offset',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'stream_wrapper_register' => [
            3 => [
                'name'  => 'flags',
                '5.2.3' => false,
                '5.2.4' => true,
            ],
        ],
        'stristr' => [
            3 => [
                'name' => 'before_needle',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'strrchr' => [
            3 => [
                'name' => 'before_needle',
                '8.2'  => false,
                '8.3'  => true,
            ],
        ],
        'strstr' => [
            3 => [
                'name' => 'before_needle',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'str_word_count' => [
            3 => [
                'name' => 'characters',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'substr_count' => [
            3 => [
                'name' => 'offset',
                '5.0'  => false,
                '5.1'  => true,
            ],
            4 => [
                'name' => 'length',
                '5.0'  => false,
                '5.1'  => true,
            ],
        ],
        'sybase_connect' => [
            6 => [
                'name' => 'new',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'timezone_transitions_get' => [
            2 => [
                'name' => 'timestampBegin',
                '5.2'  => false,
                '5.3'  => true,
            ],
            3 => [
                'name' => 'timestampEnd',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'timezone_identifiers_list' => [
            1 => [
                'name' => 'timezoneGroup',
                '5.2'  => false,
                '5.3'  => true,
            ],
            2 => [
                'name' => 'countryCode',
                '5.2'  => false,
                '5.3'  => true,
            ],
        ],
        'token_get_all' => [
            2 => [
                'name' => 'flags',
                '5.6'  => false,
                '7.0'  => true,
            ],
        ],
        'ucwords' => [
            2 => [
                'name'   => 'separators',
                '5.4.31' => false,
                '5.5.15' => false,
                '5.4.32' => true,
                '5.5.16' => true,
            ],
        ],
        'unpack' => [
            3 => [
                'name' => 'offset',
                '7.0'  => false,
                '7.1'  => true,
            ],
        ],
        'unserialize' => [
            2 => [
                'name' => 'options',
                '5.6'  => false,
                '7.0'  => true,
            ],
        ],
    ];

    /**
     * Should the sniff bow out early for specific PHP versions ?
     *
     * @since 10.0.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return false;
    }

    /**
     * Process the parameters of a matched function.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token in the stack.
     * @param string                      $functionName The token content (function name) which was matched.
     * @param array                       $parameters   Array with information about the parameters.
     *
     * @return void
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $functionLc = \strtolower($functionName);

        foreach ($this->targetFunctions[$functionLc] as $offset => $parameterDetails) {
            $targetParam = PassedParameters::getParameterFromStack($parameters, $offset, $parameterDetails['name']);

            if ($targetParam !== false && $targetParam['clean'] !== '') {
                $firstNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, $targetParam['start'], ($targetParam['end'] + 1), true);

                $itemInfo = [
                    'name'   => $functionName,
                    'nameLc' => $functionLc,
                    'offset' => $offset,
                ];
                $this->handleFeature($phpcsFile, $firstNonEmpty, $itemInfo);
            }
        }
    }


    /**
     * Handle the retrieval of relevant information and - if necessary - throwing of an
     * error for a matched item.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the relevant token in
     *                                               the stack.
     * @param array                       $itemInfo  Base information about the item.
     *
     * @return void
     */
    protected function handleFeature(File $phpcsFile, $stackPtr, array $itemInfo)
    {
        $itemArray   = $this->targetFunctions[$itemInfo['nameLc']][$itemInfo['offset']];
        $versionInfo = $this->getVersionInfo($itemArray);

        if (empty($versionInfo['not_in_version'])
            || ScannedCode::shouldRunOnOrBelow($versionInfo['not_in_version']) === false
        ) {
            return;
        }

        $this->addError($phpcsFile, $stackPtr, $itemInfo, $itemArray, $versionInfo);
    }


    /**
     * Generates the error for this item.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $stackPtr    The position of the relevant token in
     *                                                 the stack.
     * @param array                       $itemInfo    Base information about the item.
     * @param array                       $itemArray   The sub-array with all the details about
     *                                                 this item.
     * @param string[]                    $versionInfo Array with detail (version) information
     *                                                 relevant to the item.
     *
     * @return void
     */
    protected function addError(File $phpcsFile, $stackPtr, array $itemInfo, array $itemArray, array $versionInfo)
    {
        // Overrule the default message template.
        $this->msgTemplate = 'The function %s() does not have a parameter "%s" in PHP version %s or earlier';

        $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['nameLc'] . '_' . $itemArray['name'], $versionInfo);

        $data = $msgInfo['data'];
        \array_splice($data, 1, 0, [$itemArray['name']]);

        $phpcsFile->addError($msgInfo['message'], $stackPtr, $msgInfo['errorcode'], $data);
    }
}
