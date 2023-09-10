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

use PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;

/**
 * Detect calls to new native PHP functions.
 *
 * PHP version All
 *
 * @since 5.5
 * @since 5.6    Now extends the base `Sniff` class instead of the upstream
 *               `Generic.PHP.ForbiddenFunctions` sniff.
 * @since 7.1.0  Now extends the `AbstractNewFeatureSniff` instead of the base `Sniff` class..
 * @since 10.0.0 Now extends the base `Sniff` class and uses the `ComplexVersionNewFeatureTrait`.
 */
class NewFunctionsSniff extends Sniff
{
    use ComplexVersionNewFeatureTrait;

    /**
     * A list of new functions, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the function appears.
     *
     * @since 5.5
     * @since 5.6   Visibility changed from `protected` to `public`.
     * @since 7.0.2 Visibility changed back from `public` to `protected`.
     *              The earlier change was made to be in line with the upstream sniff,
     *              but that sniff is no longer being extended.
     * @since 7.0.8 Renamed from `$forbiddenFunctions` to the more descriptive `$newFunctions`.
     *
     * @var array<string, array<string, bool|string>>
     */
    protected $newFunctions = [
        'class_implements' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ],
        'class_parents' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ],
        'spl_classes' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ],
        'ob_tidyhandler' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'tidy_access_count' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'tidy_config_count' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'tidy_error_count' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'tidy_get_output' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'tidy_warning_count' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'is_soap_fault' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'use_soap_error_handler' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'dom_import_simplexml' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'simplexml_import_dom' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'simplexml',
        ],
        'simplexml_load_file' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'simplexml',
        ],
        'simplexml_load_string' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'simplexml',
        ],
        'fbsql_set_password' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'fbsql',
        ],
        'sqlite_array_query' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_busy_timeout' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_changes' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_close' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_column' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_create_aggregate' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_create_function' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_current' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_error_string' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_escape_string' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_exec' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_factory' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_fetch_all' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_fetch_array' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_fetch_column_types' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_fetch_object' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_fetch_single' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_fetch_string' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_field_name' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_has_more' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_has_prev' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_key' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_last_error' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_last_insert_rowid' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_libencoding' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_libversion' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_next' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_num_fields' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_num_rows' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_open' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_popen' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_prev' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_query' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_rewind' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_seek' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_single_query' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_udf_decode_binary' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_udf_encode_binary' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_unbuffered_query' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_valid' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'mysqli_affected_rows' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'mysqli_get_client_info' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_get_client_version' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_connect_errno' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_connect_error' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_errno' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_error' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_field_count' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_get_host_info' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_get_proto_info' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_get_server_version' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_info' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_insert_id' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_sqlstate' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_warning_count' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_autocommit' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_change_user' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_character_set_name' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_close' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_commit' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_connect' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_debug' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_dump_debug_info' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_get_server_info' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_init' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_kill' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_more_results' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_multi_query' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_next_result' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_options' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_ping' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_prepare' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_query' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_real_connect' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_real_escape_string' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_real_query' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_rollback' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_select_db' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_set_local_infile_default' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_set_local_infile_handler' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_ssl_set' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stat' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_init' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_store_result' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_thread_id' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_thread_safe' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_use_result' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_affected_rows' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_errno' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_error' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_field_count' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_insert_id' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_num_rows' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_param_count' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_sqlstate' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_attr_get' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_attr_set' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_bind_param' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_bind_result' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_close' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_data_seek' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_execute' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_fetch' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_free_result' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_prepare' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_reset' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_result_metadata' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_send_long_data' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_store_result' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_field_tell' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_num_fields' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_fetch_lengths' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_num_rows' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_data_seek' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_fetch_array' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_fetch_assoc' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_fetch_field_direct' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_fetch_field' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_fetch_fields' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_fetch_object' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_fetch_row' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_field_seek' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_free_result' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_bind_param' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_bind_result' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_client_encoding' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_disable_rpl_parse' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_enable_reads_from_master' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_enable_rpl_parse' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_escape_string' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_execute' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_fetch' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_get_metadata' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_master_query' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_param_count' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_report' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_rpl_parse_enabled' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_rpl_probe' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_send_long_data' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_set_opt' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_slave_query' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_disable_reads_from_master' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_embedded_connect' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_resource' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_server_end' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_server_init' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_result_current_field' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_result_data_seek' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_result_fetch_array' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_result_fetch_assoc' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_result_fetch_field_direct' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_result_fetch_field' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_result_fetch_fields' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_result_fetch_object' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_result_fetch_row' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_result_field_count' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_result_field_seek' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_result_free' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_result_lengths' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_result_num_rows' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],

        'interface_exists' => [
            '5.0.1' => false,
            '5.0.2' => true,
        ],

        'mysqli_set_charset' => [
            '5.0.4'     => false,
            '5.0.5'     => true,
            'extension' => 'mysqli',
        ],

        'fputcsv' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'imageconvolution' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'gd',
        ],
        'iterator_apply' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'iterator_count' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'iterator_to_array' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'spl_autoload_call' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'spl_autoload_extensions' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'spl_autoload_functions' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'spl_autoload_register' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'spl_autoload_unregister' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'spl_autoload' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'date_default_timezone_get' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'date_default_timezone_set' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'strptime' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'readline_callback_handler_install' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'readline',
        ],
        'readline_callback_handler_remove' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'readline',
        ],
        'readline_callback_read_char' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'readline',
        ],
        'readline_on_new_line' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'readline',
        ],
        'readline_redisplay' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'readline',
        ],
        'posix_access' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'posix',
        ],
        'posix_mknod' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'posix',
        ],
        'time_sleep_until' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'stream_context_get_default' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'stream_filter_remove' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'stream_socket_enable_crypto' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'stream_socket_pair' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'stream_wrapper_restore' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'stream_wrapper_unregister' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'inet_ntop' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'inet_pton' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'apache_reset_timeout' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'htmlspecialchars_decode' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'array_diff_key' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'array_diff_ukey' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'array_intersect_key' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'array_intersect_ukey' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'array_product' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'property_exists' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'libxml_clear_errors' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'libxml_get_errors' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'libxml_get_last_error' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'libxml_set_streams_context' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'libxml_use_internal_errors' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'fbsql_rows_fetched' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_set_characterset' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'fbsql',
        ],
        'mysqli_get_charset' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_get_warnings' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_get_warnings' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_embedded_server_end' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_embedded_server_start' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'mysqli',
        ],
        'pg_execute' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pgsql',
        ],
        'pg_fetch_all_columns' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pgsql',
        ],
        'pg_field_type_oid' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pgsql',
        ],
        'pg_prepare' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pgsql',
        ],
        'pg_query_params' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pgsql',
        ],
        'pg_result_error_field' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pgsql',
        ],
        'pg_send_execute' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pgsql',
        ],
        'pg_send_prepare' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pgsql',
        ],
        'pg_send_query_params' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pgsql',
        ],
        'pg_set_error_verbosity' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pgsql',
        ],
        'pg_transaction_status' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pgsql',
        ],

        'date_sun_info' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'hash_algos' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ],
        'hash_file' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ],
        'hash_final' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ],
        'hash_hmac' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ],
        'hash_hmac_file' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ],
        'hash_init' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ],
        'hash_update_file' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ],
        'hash_update_stream' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ],
        'hash_update' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ],
        'hash' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ],

        'xmlwriter_end_attribute' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_end_cdata' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_end_comment' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_end_document' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_end_dtd_attlist' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_end_dtd_element' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_end_dtd' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_end_element' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_end_pi' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_flush' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_open_memory' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_open_uri' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_output_memory' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_set_indent_string' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_set_indent' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_start_attribute_ns' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_start_attribute' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_start_cdata' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_start_comment' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_start_document' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_start_dtd_attlist' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_start_dtd_element' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_start_dtd' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_start_element_ns' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_start_element' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_start_pi' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_text' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_write_attribute_ns' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_write_attribute' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_write_cdata' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_write_comment' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_write_dtd_attlist' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_write_dtd_element' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_write_dtd' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_write_element_ns' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_write_element' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_write_pi' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],
        'oci_bind_array_by_name' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'oci8',
        ],

        'imap_savebody' => [
            '5.1.2'     => false,
            '5.1.3'     => true,
            'extension' => 'imap',
        ],
        'lchgrp' => [
            '5.1.2' => false,
            '5.1.3' => true,
        ],
        'lchown' => [
            '5.1.2' => false,
            '5.1.3' => true,
        ],
        'timezone_name_from_abbr' => [
            '5.1.2' => false,
            '5.1.3' => true,
        ],
        'sys_getloadavg' => [
            '5.1.2' => false,
            '5.1.3' => true,
        ],
        'curl_setopt_array' => [
            '5.1.2'     => false,
            '5.1.3'     => true,
            'extension' => 'curl',
        ],

        'array_fill_keys' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'error_get_last' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'image_type_to_extension' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'gd',
        ],
        'memory_get_peak_usage' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'date_create' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'date_date_set' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'date_format' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'date_isodate_set' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'date_modify' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'date_offset_get' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'date_parse' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'date_time_set' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'date_timezone_get' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'date_timezone_set' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'timezone_abbreviations_list' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'timezone_identifiers_list' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'timezone_name_get' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'timezone_offset_get' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'timezone_open' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'timezone_transitions_get' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'mb_stripos' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'mbstring',
        ],
        'mb_stristr' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'mbstring',
        ],
        'mb_strrchr' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'mbstring',
        ],
        'mb_strrichr' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'mbstring',
        ],
        'mb_strripos' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'mbstring',
        ],
        'mb_strstr' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'mbstring',
        ],
        'ming_setswfcompression' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'ming',
        ],
        'openssl_csr_get_public_key' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'openssl',
        ],
        'openssl_csr_get_subject' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'openssl',
        ],
        'openssl_pkey_get_details' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'openssl',
        ],
        'spl_object_hash' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'spl',
        ],
        'preg_last_error' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'pg_field_table' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'pgsql',
        ],
        'posix_initgroups' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'posix',
        ],
        'gmp_nextprime' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'gmp',
        ],
        'xmlwriter_full_end_element' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_write_raw' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'xmlwriter',
        ],
        'filter_has_var' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'filter_id' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'filter_input_array' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'filter_input' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'filter_list' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'filter_var_array' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'filter_var' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'json_decode' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'json',
        ],
        'json_encode' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'json',
        ],
        'zip_close' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'zip',
        ],
        'zip_entry_close' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'zip',
        ],
        'zip_entry_compressedsize' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'zip',
        ],
        'zip_entry_compressionmethod' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'zip',
        ],
        'zip_entry_filesize' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'zip',
        ],
        'zip_entry_name' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'zip',
        ],
        'zip_entry_open' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'zip',
        ],
        'zip_entry_read' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'zip',
        ],
        'zip_open' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'zip',
        ],
        'zip_read' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'zip',
        ],
        'stream_notification_callback' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'snmp_set_oid_output_format' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'snmp',
        ],
        'snmp2_get' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'snmp',
        ],
        'snmp2_getnext' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'snmp',
        ],
        'snmp2_real_walk' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'snmp',
        ],
        'snmp2_set' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'snmp',
        ],
        'snmp2_walk' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'snmp',
        ],

        'sys_get_temp_dir' => [
            '5.2.0' => false,
            '5.2.1' => true,
        ],
        'stream_socket_shutdown' => [
            '5.2.0' => false,
            '5.2.1' => true,
        ],
        'xmlwriter_start_dtd_entity' => [
            '5.2.0'     => false,
            '5.2.1'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_end_dtd_entity' => [
            '5.2.0'     => false,
            '5.2.1'     => true,
            'extension' => 'xmlwriter',
        ],
        'xmlwriter_write_dtd_entity' => [
            '5.2.0'     => false,
            '5.2.1'     => true,
            'extension' => 'xmlwriter',
        ],

        'imagegrabscreen' => [
            '5.2.1'     => false,
            '5.2.2'     => true,
            'extension' => 'gd',
        ],
        'imagegrabwindow' => [
            '5.2.1'     => false,
            '5.2.2'     => true,
            'extension' => 'gd',
        ],
        'openssl_pkcs12_export_to_file' => [
            '5.2.1'     => false,
            '5.2.2'     => true,
            'extension' => 'openssl',
        ],
        'openssl_pkcs12_export' => [
            '5.2.1'     => false,
            '5.2.2'     => true,
            'extension' => 'openssl',
        ],
        'openssl_pkcs12_read' => [
            '5.2.1'     => false,
            '5.2.2'     => true,
            'extension' => 'openssl',
        ],

        'mysql_set_charset' => [
            '5.2.2'     => false,
            '5.2.3'     => true,
            'extension' => 'mysql',
        ],

        'php_ini_loaded_file' => [
            '5.2.3' => false,
            '5.2.4' => true,
        ],
        'stream_is_local' => [
            '5.2.3' => false,
            '5.2.4' => true,
        ],

        'libxml_disable_entity_loader' => [
            '5.2.11'    => false,
            '5.2.12'    => true,
            'extension' => 'libxml',
        ],

        'array_replace' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'array_replace_recursive' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'class_alias' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'forward_static_call' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'forward_static_call_array' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'gc_collect_cycles' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'gc_disable' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'gc_enable' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'gc_enabled' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'get_called_class' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'gethostname' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'header_remove' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'lcfirst' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'parse_ini_string' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'quoted_printable_encode' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'str_getcsv' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'stream_context_set_default' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'stream_supports_lock' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'stream_context_get_params' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'date_add' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'date_create_from_format' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'date_diff' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'date_get_last_errors' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'date_interval_create_from_date_string' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'date_interval_format' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'date_parse_from_format' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'date_sub' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'date_timestamp_get' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'date_timestamp_set' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'timezone_location_get' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'timezone_version_get' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'gmp_testbit' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'gmp',
        ],
        'hash_copy' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'hash',
        ],
        'imap_gc' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'imap',
        ],
        'imap_utf8_to_mutf7' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'imap',
        ],
        'imap_mutf7_to_utf8' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'imap',
        ],
        'json_last_error' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'json',
        ],
        'mysqli_get_cache_stats' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_fetch_all' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_get_connection_status' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_poll' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_reap_async_query' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_get_connection_stats' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_get_client_stats' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_refresh' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_result_fetch_all' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_get_result' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_more_results' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_next_result' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'openssl_random_pseudo_bytes' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'openssl',
        ],
        'pcntl_signal_dispatch' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'pcntl_sigprocmask' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'pcntl_sigtimedwait' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'pcntl_sigwaitinfo' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'preg_filter' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'msg_queue_exists' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'sem',
        ],
        'shm_has_vars' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'sem',
        ],
        'acosh' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'asinh' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'atanh' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'expm1' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'log1p' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'enchant_broker_describe' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_broker_dict_exists' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_broker_free_dict' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_broker_free' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_broker_get_error' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_broker_init' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_broker_list_dicts' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_broker_request_dict' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_broker_request_pwl_dict' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_broker_set_ordering' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_dict_add_to_personal' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_dict_add_to_session' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_dict_check' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_dict_describe' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_dict_get_error' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_dict_is_in_session' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_dict_quick_check' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_dict_store_replacement' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'enchant_dict_suggest' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'finfo_buffer' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],
        'finfo_close' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],
        'finfo_file' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],
        'finfo_open' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],
        'finfo_set_flags' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],
        'collator_asort' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'collator_compare' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'collator_create' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'collator_get_attribute' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'collator_get_error_code' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'collator_get_error_message' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'collator_get_locale' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'collator_get_strength' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'collator_set_attribute' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'collator_set_strength' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'collator_sort_with_sort_keys' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'collator_sort' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_create' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_get_datetype' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_get_timetype' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_get_calendar' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_set_calendar' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_get_timezone_id' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_set_timezone_id' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_set_pattern' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_get_pattern' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_get_locale' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_set_lenient' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_is_lenient' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_format' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_parse' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_localtime' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_get_error_code' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'datefmt_get_error_message' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'grapheme_extract' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'grapheme_stripos' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'grapheme_stristr' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'grapheme_strlen' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'grapheme_strpos' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'grapheme_strripos' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'grapheme_strrpos' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'grapheme_strstr' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'grapheme_substr' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'idn_to_ascii' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'idn_to_utf8' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'intl_error_name' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'intl_get_error_code' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'intl_get_error_message' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'intl_is_failure' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_accept_from_http' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_canonicalize' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_compose' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_filter_matches' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_get_all_variants' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_get_default' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_get_display_language' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_get_display_name' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_get_display_region' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_get_display_script' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_get_display_variant' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_get_keywords' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_get_primary_language' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_get_region' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_get_script' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_lookup' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_parse' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'locale_set_default' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'msgfmt_create' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'msgfmt_format_message' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'msgfmt_format' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'msgfmt_get_error_code' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'msgfmt_get_error_message' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'msgfmt_get_locale' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'msgfmt_get_pattern' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'msgfmt_parse_message' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'msgfmt_parse' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'msgfmt_set_pattern' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'normalizer_is_normalized' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'normalizer_normalize' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'numfmt_create' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'numfmt_format_currency' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'numfmt_format' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'numfmt_get_attribute' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'numfmt_get_error_code' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'numfmt_get_error_message' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'numfmt_get_locale' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'numfmt_get_pattern' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'numfmt_get_symbol' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'numfmt_get_text_attribute' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'numfmt_parse_currency' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'numfmt_parse' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'numfmt_set_attribute' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'numfmt_set_pattern' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'numfmt_set_symbol' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'numfmt_set_text_attribute' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'openssl_decrypt' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'openssl',
        ],
        'openssl_dh_compute_key' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'openssl',
        ],
        'openssl_digest' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'openssl',
        ],
        'openssl_encrypt' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'openssl',
        ],
        'openssl_get_cipher_methods' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'openssl',
        ],
        'openssl_get_md_methods' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'openssl',
        ],
        'mb_encoding_aliases' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mbstring',
        ],

        'enchant_broker_get_dict_path' => [
            '5.3.0'     => false,
            '5.3.1'     => true,
            'extension' => 'enchant',
        ],
        'enchant_broker_set_dict_path' => [
            '5.3.0'     => false,
            '5.3.1'     => true,
            'extension' => 'enchant',
        ],

        'collator_get_sort_key' => [
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'intl',
        ],
        'resourcebundle_count' => [
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'intl',
        ],
        'resourcebundle_create' => [
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'intl',
        ],
        'resourcebundle_get_error_code' => [
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'intl',
        ],
        'resourcebundle_get_error_message' => [
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'intl',
        ],
        'resourcebundle_get' => [
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'intl',
        ],
        'resourcebundle_locales' => [
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'intl',
        ],
        'realpath_cache_get' => [
            '5.3.1' => false,
            '5.3.2' => true,
        ],
        'realpath_cache_size' => [
            '5.3.1' => false,
            '5.3.2' => true,
        ],
        'stream_resolve_include_path' => [
            '5.3.1' => false,
            '5.3.2' => true,
        ],
        'oci_set_action' => [
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'oci8',
        ],
        'oci_set_client_info' => [
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'oci8',
        ],
        'oci_set_client_identifier' => [
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'oci8',
        ],
        'oci_set_edition' => [
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'oci8',
        ],
        'oci_set_module_name' => [
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'oci8',
        ],

        'stream_set_read_buffer' => [
            '5.3.2' => false,
            '5.3.3' => true,
        ],
        'openssl_cipher_iv_length' => [
            '5.3.2'     => false,
            '5.3.3'     => true,
            'extension' => 'openssl',
        ],
        'fastcgi_finish_request' => [
            '5.3.2'     => false,
            '5.3.3'     => true,
            'extension' => 'fastcgi',
        ],

        'pcntl_errno' => [
            '5.3.3'     => false,
            '5.3.4'     => true,
            'extension' => 'pcntl',
        ],
        'pcntl_get_last_error' => [
            '5.3.3'     => false,
            '5.3.4'     => true,
            'extension' => 'pcntl',
        ],
        'pcntl_strerror' => [
            '5.3.3'     => false,
            '5.3.4'     => true,
            'extension' => 'pcntl',
        ],

        'imap_fetchmime' => [
            '5.3.5'     => false,
            '5.3.6'     => true,
            'extension' => 'imap',
        ],

        'oci_client_version' => [
            '5.3.6'     => false,
            '5.3.7'     => true,
            'extension' => 'oci8',
        ],

        'hex2bin' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'http_response_code' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'get_declared_traits' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'getimagesizefromstring' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'gd',
        ],
        'imagecreatefromwebp' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'gd',
        ],
        'imagewebp' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'gd',
        ],
        'stream_set_chunk_size' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'socket_import_stream' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'trait_exists' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'header_register_callback' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'class_uses' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'spl',
        ],
        'session_status' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'session_register_shutdown' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'mysqli_error_list' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt_error_list' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'mysqli',
        ],
        'libxml_set_external_entity_loader' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'libxml',
        ],
        'ldap_control_paged_result' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'ldap',
        ],
        'ldap_control_paged_result_response' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'ldap',
        ],
        'transliterator_create' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'transliterator_create_from_rules' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'transliterator_create_inverse' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'transliterator_get_error_code' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'transliterator_get_error_message' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'transliterator_list_ids' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'transliterator_transliterate' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'zlib_decode' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'zlib',
        ],
        'zlib_encode' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'zlib',
        ],
        'gzdecode' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'zlib',
        ],

        'mb_ereg_replace_callback' => [
            '5.4.0'     => false,
            '5.4.1'     => true,
            'extension' => 'mbstring',
        ],

        'pg_escape_literal' => [
            '5.4.3'     => false,
            '5.4.4'     => true,
            'extension' => 'pgsql',
        ],
        'pg_escape_identifier' => [
            '5.4.3'     => false,
            '5.4.4'     => true,
            'extension' => 'pgsql',
        ],

        'array_column' => [
            '5.4' => false,
            '5.5' => true,
        ],
        'boolval' => [
            '5.4' => false,
            '5.5' => true,
        ],
        'json_last_error_msg' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'json',
        ],
        'password_get_info' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'password',
        ],
        'password_hash' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'password',
        ],
        'password_needs_rehash' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'password',
        ],
        'password_verify' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'password',
        ],
        'hash_pbkdf2' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'hash',
        ],
        'openssl_pbkdf2' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'openssl',
        ],
        'curl_escape' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'curl_file_create' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'curl_multi_setopt' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'curl_multi_strerror' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'curl_pause' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'curl_reset' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'curl_share_close' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'curl_share_init' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'curl_share_setopt' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'curl_strerror' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'curl_unescape' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'date_create_immutable_from_format' => [
            '5.4' => false,
            '5.5' => true,
        ],
        'date_create_immutable' => [
            '5.4' => false,
            '5.5' => true,
        ],
        'imageaffine' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'imageaffinematrixconcat' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'imageaffinematrixget' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'imagecrop' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'imagecropauto' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'imageflip' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'imagepalettetotruecolor' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'imagescale' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'imagesetinterpolation' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'mysqli_begin_transaction' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_release_savepoint' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_savepoint' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'mysqli',
        ],
        'socket_sendmsg' => [
            '5.4' => false,
            '5.5' => true,
        ],
        'socket_recvmsg' => [
            '5.4' => false,
            '5.5' => true,
        ],
        'socket_cmsg_space' => [
            '5.4' => false,
            '5.5' => true,
        ],
        'cli_get_process_title' => [
            '5.4' => false,
            '5.5' => true,
        ],
        'cli_set_process_title' => [
            '5.4' => false,
            '5.5' => true,
        ],
        'datefmt_format_object' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'datefmt_get_calendar_object' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'datefmt_get_timezone' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'datefmt_set_timezone' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_create_instance' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_keyword_values_for_locale' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_now' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_available_locales' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_time' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_set_time' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_add' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_set_time_zone' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_after' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_before' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_set' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_roll' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_clear' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_field_difference' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_actual_maximum' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_actual_minumum' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_day_of_week_type' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_first_day_of_week' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_greatest_minimum' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_least_maximum' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_locale' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_maximum' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_minimal_days_in_first_week' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_minimum' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_time_zone' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_type' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_weekend_transition' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_in_daylight_time' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_is_equivalent_to' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_is_lenient' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_is_set' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_is_weekend' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_equals' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_repeated_wall_time_option' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_skipped_wall_time_option' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_set_first_day_of_week' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_set_lenient' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_set_minimal_days_in_first_week' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_set_repeated_wall_time_option' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_set_skipped_wall_time_option' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_from_date_time' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_to_date_time' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_error_code' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlcal_get_error_message' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlgregcal_create_instance' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlgregcal_set_gregorian_change' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlgregcal_get_gregorian_change' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intlgregcal_is_leap_year' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_create_time_zone' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_create_default' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_get_id' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_get_gmt' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_get_unknown' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_create_enumeration' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_count_equivalent_ids' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_create_time_zone_id_enumeration' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_get_canonical_id' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_get_region' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_get_tz_data_version' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_get_equivalent_id' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_use_daylight_time' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_get_offset' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_get_raw_offset' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_has_same_rules' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_get_display_name' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_get_dst_savings' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_from_date_time_zone' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_to_date_time_zone' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_get_error_code' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'intltz_get_error_message' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'opcache_get_configuration' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache_get_status' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache_invalidate' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache_reset' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],

        'opcache_compile_file' => [
            '5.5.4'     => false,
            '5.5.5'     => true,
            'extension' => 'opcache',
        ],

        'opcache_is_script_cached' => [
            '5.5.10'    => false,
            '5.5.11'    => true,
            'extension' => 'opcache',
        ],

        'gmp_root' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'gmp',
        ],
        'gmp_rootrem' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'gmp',
        ],
        'hash_equals' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'hash',
        ],
        'ldap_escape' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'ldap',
        ],
        'ldap_modify_batch' => [
            '5.4.25'    => false,
            '5.5.9'     => false,
            '5.4.26'    => true,
            '5.5.10'    => true,
            '5.6.0'     => true,
            'extension' => 'ldap',
        ],
        'mysqli_get_links_stats' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'mysqli',
        ],
        'openssl_get_cert_locations' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'openssl',
        ],
        'openssl_x509_fingerprint' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'openssl',
        ],
        'openssl_spki_new' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'openssl',
        ],
        'openssl_spki_verify' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'openssl',
        ],
        'openssl_spki_export_challenge' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'openssl',
        ],
        'openssl_spki_export' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'openssl',
        ],
        'phpdbg_clear' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'phpdbg',
        ],
        'phpdbg_color' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'phpdbg',
        ],
        'phpdbg_exec' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'phpdbg',
        ],
        'phpdbg_prompt' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'phpdbg',
        ],
        'pg_connect_poll' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'pg_consume_input' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'pg_flush' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'pg_lo_truncate' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'pg_socket' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'session_abort' => [
            '5.5' => false,
            '5.6' => true,
        ],
        'session_reset' => [
            '5.5' => false,
            '5.6' => true,
        ],
        'oci_get_implicit_resultset' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'oci8',
        ],

        'gmp_export' => [
            '5.6.0'     => false,
            '5.6.1'     => true,
            'extension' => 'gmp',
        ],
        'gmp_import' => [
            '5.6.0'     => false,
            '5.6.1'     => true,
            'extension' => 'gmp',
        ],

        'gmp_random_bits' => [
            '5.6.2'     => false,
            '5.6.3'     => true,
            'extension' => 'gmp',
        ],
        'gmp_random_range' => [
            '5.6.2'     => false,
            '5.6.3'     => true,
            'extension' => 'gmp',
        ],
        'phpdbg_break_file' => [
            '5.6.2'     => false,
            '5.6.3'     => true,
            'extension' => 'phpdbg',
        ],
        'phpdbg_break_function' => [
            '5.6.2'     => false,
            '5.6.3'     => true,
            'extension' => 'phpdbg',
        ],
        'phpdbg_break_method' => [
            '5.6.2'     => false,
            '5.6.3'     => true,
            'extension' => 'phpdbg',
        ],
        'phpdbg_break_next' => [
            '5.6.2'     => false,
            '5.6.3'     => true,
            'extension' => 'phpdbg',
        ],

        'random_bytes' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'csprng',
        ],
        'random_int' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'csprng',
        ],
        'error_clear_last' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'gmp_random_seed' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'gmp',
        ],
        'intdiv' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'preg_replace_callback_array' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'gc_mem_caches' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'get_resources' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'phpdbg_end_oplog' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'phpdbg',
        ],
        'phpdbg_get_executable' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'phpdbg',
        ],
        'phpdbg_start_oplog' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'phpdbg',
        ],
        'posix_setrlimit' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'inflate_add' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        'deflate_add' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        'inflate_init' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        'deflate_init' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],

        'socket_export_stream' => [
            '7.0.6' => false,
            '7.0.7' => true,
        ],

        'curl_multi_errno' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'curl',
        ],
        'curl_share_errno' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'curl',
        ],
        'curl_share_strerror' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'curl',
        ],
        'is_iterable' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'pcntl_async_signals' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'pcntl',
        ],
        'pcntl_signal_get_handler' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'pcntl',
        ],
        'session_create_id' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'session_gc' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'sapi_windows_cp_set' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'sapi_windows_cp_get' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'sapi_windows_cp_is_utf8' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'sapi_windows_cp_conv' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'openssl_get_curve_names' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'openssl',
        ],
        'intltz_get_id_for_windows_id' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'intl',
        ],
        'intltz_get_windows_id' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'intl',
        ],

        'hash_hkdf' => [
            '7.1.1'     => false,
            '7.1.2'     => true,
            'extension' => 'hash',
        ],
        'oci_register_taf_callback' => [
            '7.1.6'     => false,
            '7.1.7'     => true,
            'extension' => 'oci8',
        ],
        'oci_unregister_taf_callback' => [
            '7.1.8'     => false,
            '7.1.9'     => true,
            'extension' => 'oci8',
        ],

        'stream_isatty' => [
            '7.1' => false,
            '7.2' => true,
        ],
        'sapi_windows_vt100_support' => [
            '7.1' => false,
            '7.2' => true,
        ],
        'ftp_append' => [
            '7.1' => false,
            '7.2' => true,
        ],
        'ftp_mlsd' => [
            '7.1' => false,
            '7.2' => true,
        ],
        'hash_hmac_algos' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'hash',
        ],
        'imagebmp' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'gd',
        ],
        'imagecreatefrombmp' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'gd',
        ],
        'imagegetclip' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'gd',
        ],
        'imageopenpolygon' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'gd',
        ],
        'imageresolution' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'gd',
        ],
        'imagesetclip' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'gd',
        ],
        'inflate_get_read_len' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'zlib',
        ],
        'inflate_get_status' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'zlib',
        ],
        'ldap_exop' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'ldap',
        ],
        'ldap_exop_passwd' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'ldap',
        ],
        'ldap_exop_whoami' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'ldap',
        ],
        'ldap_parse_exop' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'ldap',
        ],
        'mb_chr' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mbstring',
        ],
        'mb_ord' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mbstring',
        ],
        'mb_scrub' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mbstring',
        ],
        'socket_addrinfo_lookup' => [
            '7.1' => false,
            '7.2' => true,
        ],
        'socket_addrinfo_connect' => [
            '7.1' => false,
            '7.2' => true,
        ],
        'socket_addrinfo_bind' => [
            '7.1' => false,
            '7.2' => true,
        ],
        'socket_addrinfo_explain' => [
            '7.1' => false,
            '7.2' => true,
        ],
        'spl_object_id' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'spl',
        ],
        'sodium_add' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_base642bin' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_bin2base64' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_bin2hex' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_compare' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_aead_aes256gcm_decrypt' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_aead_aes256gcm_encrypt' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_aead_aes256gcm_is_available' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_aead_aes256gcm_keygen' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_aead_chacha20poly1305_decrypt' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_aead_chacha20poly1305_encrypt' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_aead_chacha20poly1305_ietf_decrypt' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_aead_chacha20poly1305_ietf_encrypt' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_aead_chacha20poly1305_ietf_keygen' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_aead_chacha20poly1305_keygen' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_aead_xchacha20poly1305_ietf_decrypt' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_aead_xchacha20poly1305_ietf_encrypt' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_aead_xchacha20poly1305_ietf_keygen' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_auth_keygen' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_auth_verify' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_auth' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_box_keypair_from_secretkey_and_publickey' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_box_keypair' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_box_open' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_box_publickey_from_secretkey' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_box_publickey' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_box_seal_open' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_box_seal' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_box_secretkey' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_box_seed_keypair' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_box' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_generichash_final' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_generichash_init' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_generichash_keygen' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_generichash_update' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_generichash' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_kdf_derive_from_key' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_kdf_keygen' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_kx_client_session_keys' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_kx_keypair' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_kx_publickey' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_kx_secretkey' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_kx_seed_keypair' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_kx_server_session_keys' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_pwhash_scryptsalsa208sha256_str_verify' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_pwhash_scryptsalsa208sha256_str' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_pwhash_scryptsalsa208sha256' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_pwhash_str_needs_rehash' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_pwhash_str_verify' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_pwhash_str' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_pwhash' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_scalarmult_base' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_scalarmult' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_secretbox_keygen' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_secretbox_open' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_secretbox' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_secretstream_xchacha20poly1305_init_pull' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_secretstream_xchacha20poly1305_init_push' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_secretstream_xchacha20poly1305_keygen' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_secretstream_xchacha20poly1305_pull' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_secretstream_xchacha20poly1305_push' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_secretstream_xchacha20poly1305_rekey' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_shorthash_keygen' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_shorthash' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_sign_detached' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_sign_ed25519_pk_to_curve25519' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_sign_ed25519_sk_to_curve25519' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_sign_keypair_from_secretkey_and_publickey' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_sign_keypair' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_sign_open' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_sign_publickey_from_secretkey' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_sign_publickey' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_sign_secretkey' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_sign_seed_keypair' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_sign_verify_detached' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_sign' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_stream_keygen' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_stream_xor' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_stream' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_hex2bin' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_increment' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_memcmp' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_memzero' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_pad' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'sodium_unpad' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'openssl_pkcs7_read' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'openssl',
        ],

        // Introduced in 7.2.14 and 7.3.1 simultanously.
        'oci_set_call_timeout' => [
            '7.2.13'    => false,
            '7.2.14'    => true,
            'extension' => 'oci8',
        ],
        // Introduced in 7.2.14 and 7.3.1 simultanously.
        'oci_set_db_operation' => [
            '7.2.13'    => false,
            '7.2.14'    => true,
            'extension' => 'oci8',
        ],

        'hrtime' => [
            '7.2' => false,
            '7.3' => true,
        ],
        'is_countable' => [
            '7.2' => false,
            '7.3' => true,
        ],
        'array_key_first' => [
            '7.2' => false,
            '7.3' => true,
        ],
        'array_key_last' => [
            '7.2' => false,
            '7.3' => true,
        ],
        'fpm_get_status' => [
            '7.2' => false,
            '7.3' => true,
        ],
        'net_get_interfaces' => [
            '7.2' => false,
            '7.3' => true,
        ],
        'gmp_binomial' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'gmp',
        ],
        'gmp_lcm' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'gmp',
        ],
        'gmp_perfect_power' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'gmp',
        ],
        'gmp_kronecker' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'gmp',
        ],
        'ldap_add_ext' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'ldap_bind_ext' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'ldap_delete_ext' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'ldap_exop_refresh' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'ldap_mod_add_ext' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'ldap_mod_replace_ext' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'ldap_mod_del_ext' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'ldap_rename_ext' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'normalizer_get_raw_decomposition' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'intl',
        ],
        'openssl_pkey_derive' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'openssl',
        ],
        'socket_wsaprotocol_info_export' => [
            '7.2' => false,
            '7.3' => true,
        ],
        'socket_wsaprotocol_info_import' => [
            '7.2' => false,
            '7.3' => true,
        ],
        'socket_wsaprotocol_info_release' => [
            '7.2' => false,
            '7.3' => true,
        ],
        'gc_status' => [
            '7.2' => false,
            '7.3' => true,
        ],

        'get_mangled_object_vars' => [
            '7.3' => false,
            '7.4' => true,
        ],
        'imagecreatefromtga' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'gd',
        ],
        'mb_str_split' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'mbstring',
        ],
        'openssl_x509_verify' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'openssl',
        ],
        'password_algos' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'password',
        ],
        'pcntl_unshare' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'pcntl',
        ],
        'sapi_windows_set_ctrl_handler' => [
            '7.3' => false,
            '7.4' => true,
        ],
        'sapi_windows_generate_ctrl_event' => [
            '7.3' => false,
            '7.4' => true,
        ],

        'fdiv' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'get_debug_type' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'get_resource_id' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'preg_last_error_msg' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'str_contains' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'str_ends_with' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'str_starts_with' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'imagegetinterpolation' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'gd',
        ],
        'enchant_dict_add' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'enchant',
        ],
        'enchant_dict_is_added' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'enchant',
        ],
        'ldap_count_references' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'ldap',
        ],
        'openssl_cms_encrypt' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'openssl',
        ],
        'openssl_cms_decrypt' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'openssl',
        ],
        'openssl_cms_read' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'openssl',
        ],
        'openssl_cms_sign' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'openssl',
        ],
        'openssl_cms_verify' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'openssl',
        ],

        'array_is_list' => [
            '8.0' => false,
            '8.1' => true,
        ],
        'enum_exists' => [
            '8.0' => false,
            '8.1' => true,
        ],
        'fsync' => [
            '8.0' => false,
            '8.1' => true,
        ],
        'fdatasync' => [
            '8.0' => false,
            '8.1' => true,
        ],
        'imagecreatefromavif' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'gd',
        ],
        'imageavif' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'gd',
        ],
        'mysqli_fetch_column' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'mysqli',
        ],
        'pcntl_rfork' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'pcntl',
        ],
        'sodium_crypto_stream_xchacha20' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_stream_xchacha20_keygen' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_stream_xchacha20_xor' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_core_ristretto255_add' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_core_ristretto255_from_hash' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_core_ristretto255_is_valid_point' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_core_ristretto255_random' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_core_ristretto255_scalar_add' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_core_ristretto255_scalar_complement' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_core_ristretto255_scalar_invert' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_core_ristretto255_scalar_mul' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_core_ristretto255_scalar_negate' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_core_ristretto255_scalar_random' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_core_ristretto255_scalar_reduce' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_core_ristretto255_scalar_sub' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_core_ristretto255_sub' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_scalarmult_ristretto255' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'sodium_crypto_scalarmult_ristretto255_base' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],

        'ini_parse_quantity' => [
            '8.1' => false,
            '8.2' => true,
        ],
        'memory_reset_peak_usage' => [
            '8.1' => false,
            '8.2' => true,
        ],
        'curl_upkeep' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'mysqli_execute_query' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'mysqli',
        ],
        'oci_set_prefetch_lob' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'oci8',
        ],
        'odbc_connection_string_is_quoted' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'odbc',
        ],
        'odbc_connection_string_should_quote' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'odbc',
        ],
        'odbc_connection_string_quote' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'odbc',
        ],
        'openssl_cipher_key_length' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'openssal',
        ],
        'sodium_crypto_stream_xchacha20_xor_ic' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sodium',
        ],
        'libxml_get_external_entity_loader' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'libxml',
        ],

        'imap_is_open' => [
            '8.2.0'     => false,
            '8.2.1'     => true,
            'extension' => 'imap',
        ],

        'stream_context_set_options' => [
            '8.2' => false,
            '8.3' => true,
        ],
        'json_validate' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'json',
        ],
        'ldap_connect_wallet' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'ldap',
        ],
        'ldap_exop_sync' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'ldap',
        ],
        'mb_str_pad' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'mbstring',
        ],
        'pg_set_error_context_visibility' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pgsql',
        ],
        'pg_enter_pipeline_mode' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pgsql',
        ],
        'pg_exit_pipeline_mode' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pgsql',
        ],
        'pg_pipeline_sync' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pgsql',
        ],
        'pg_pipeline_status' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pgsql',
        ],
        'posix_sysconf' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'posix_pathconf' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'posix_fpathconf' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'posix_eaccess' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'socket_atmark' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 5.6
     *
     * @return array<int|string>
     */
    public function register()
    {
        // Handle case-insensitivity of function names.
        $this->newFunctions = \array_change_key_case($this->newFunctions, \CASE_LOWER);

        return [\T_STRING];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 5.5
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

        if (isset($this->newFunctions[$functionLc]) === false) {
            return;
        }

        $nextToken = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($nextToken === false
            || $tokens[$nextToken]['code'] !== \T_OPEN_PARENTHESIS
            || isset($tokens[$nextToken]['parenthesis_owner']) === true
        ) {
            return;
        }

        $ignore  = [\T_NEW => true];
        $ignore += Collections::objectOperators();

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

        $itemInfo = [
            'name'   => $function,
            'nameLc' => $functionLc,
        ];
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
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
        $itemArray   = $this->newFunctions[$itemInfo['nameLc']];
        $versionInfo = $this->getVersionInfo($itemArray);

        if (empty($versionInfo['not_in_version'])
            || ScannedCode::shouldRunOnOrBelow($versionInfo['not_in_version']) === false
        ) {
            return;
        }

        $this->addError($phpcsFile, $stackPtr, $itemInfo, $versionInfo);
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
     * @param string[]                    $versionInfo Array with detail (version) information
     *                                                 relevant to the item.
     *
     * @return void
     */
    protected function addError(File $phpcsFile, $stackPtr, array $itemInfo, array $versionInfo)
    {
        // Overrule the default message template.
        $this->msgTemplate = 'The function %s() is not present in PHP version %s or earlier';

        $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['name'], $versionInfo);

        $phpcsFile->addError($msgInfo['message'], $stackPtr, $msgInfo['errorcode'], $msgInfo['data']);
    }
}
