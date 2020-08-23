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
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * Detect calls to new native PHP functions.
 *
 * PHP version All
 *
 * @since 5.5
 * @since 5.6   Now extends the base `Sniff` class instead of the upstream
 *              `Generic.PHP.ForbiddenFunctions` sniff.
 * @since 7.1.0 Now extends the `AbstractNewFeatureSniff` instead of the base `Sniff` class..
 */
class NewFunctionsSniff extends AbstractNewFeatureSniff
{
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
     * @var array(string => array(string => bool))
     */
    protected $newFunctions = array(
        'class_implements' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ),
        'class_parents' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ),
        'spl_classes' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ),
        'ob_tidyhandler' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ),
        'tidy_access_count' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ),
        'tidy_config_count' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ),
        'tidy_error_count' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ),
        'tidy_get_output' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ),
        'tidy_warning_count' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ),
        'is_soap_fault' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ),
        'use_soap_error_handler' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ),
        'dom_import_simplexml' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'simplexml_import_dom' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'simplexml',
        ),
        'simplexml_load_file' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'simplexml',
        ),
        'simplexml_load_string' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'simplexml',
        ),
        'fbsql_set_password' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'sqlite_array_query' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_busy_timeout' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_changes' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_close' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_column' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_create_aggregate' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_create_function' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_current' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_error_string' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_escape_string' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_exec' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_factory' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_fetch_all' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_fetch_array' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_fetch_column_types' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_fetch_object' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_fetch_single' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_fetch_string' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_field_name' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_has_more' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_has_prev' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_key' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_last_error' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_last_insert_rowid' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_libencoding' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_libversion' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_next' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_num_fields' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_num_rows' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_open' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_popen' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_prev' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_query' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_rewind' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_seek' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_single_query' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_udf_decode_binary' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_udf_encode_binary' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_unbuffered_query' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_valid' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'mysqli_affected_rows' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ),
        'mysqli_get_client_info' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_get_client_version' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_connect_errno' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_connect_error' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_errno' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_error' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_field_count' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_get_host_info' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_get_proto_info' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_get_server_version' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_info' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_insert_id' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_sqlstate' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_warning_count' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_autocommit' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_change_user' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_character_set_name' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_close' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_commit' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_connect' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_debug' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_dump_debug_info' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_get_server_info' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_init' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_kill' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_more_results' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_multi_query' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_next_result' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_options' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_ping' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_prepare' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_query' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_real_connect' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_real_escape_string' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_real_query' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_rollback' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_select_db' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_set_local_infile_default' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_set_local_infile_handler' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_ssl_set' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stat' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_init' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_store_result' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_thread_id' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_thread_safe' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_use_result' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_affected_rows' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_errno' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_error' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_field_count' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_insert_id' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_num_rows' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_param_count' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_sqlstate' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_attr_get' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_attr_set' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_bind_param' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_bind_result' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_close' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_data_seek' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_execute' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_fetch' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_free_result' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_prepare' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_reset' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_result_metadata' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_send_long_data' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_store_result' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_field_tell' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_num_fields' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_fetch_lengths' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_num_rows' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_data_seek' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_fetch_array' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_fetch_assoc' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_fetch_field_direct' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_fetch_field' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_fetch_fields' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_fetch_object' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_fetch_row' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_field_seek' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_free_result' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_bind_param' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_bind_result' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_client_encoding' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_disable_rpl_parse' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_enable_reads_from_master' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_enable_rpl_parse' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_escape_string' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_execute' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_fetch' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_get_metadata' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_master_query' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_param_count' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_report' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_rpl_parse_enabled' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_rpl_probe' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_send_long_data' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_set_opt' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_slave_query' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_disable_reads_from_master' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_embedded_connect' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_resource' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_server_end' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_server_init' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_result_current_field' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_result_data_seek' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_result_fetch_array' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_result_fetch_assoc' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_result_fetch_field_direct' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_result_fetch_field' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_result_fetch_fields' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_result_fetch_object' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_result_fetch_row' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_result_field_count' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_result_field_seek' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_result_free' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_result_lengths' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_result_num_rows' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ),

        'interface_exists' => array(
            '5.0.1' => false,
            '5.0.2' => true,
        ),

        'mysqli_set_charset' => array(
            '5.0.4'     => false,
            '5.0.5'     => true,
            'extension' => 'mysqli',
        ),

        'fputcsv' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'imageconvolution' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'iterator_apply' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'iterator_count' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'iterator_to_array' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'spl_autoload_call' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'spl_autoload_extensions' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'spl_autoload_functions' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'spl_autoload_register' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'spl_autoload_unregister' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'spl_autoload' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'date_default_timezone_get' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'date_default_timezone_set' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'strptime' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'readline_callback_handler_install' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'readline_callback_handler_remove' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'readline_callback_read_char' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'readline_on_new_line' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'readline_redisplay' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'posix_access' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'posix_mknod' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'time_sleep_until' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'stream_context_get_default' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'stream_filter_remove' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'stream_socket_enable_crypto' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'stream_socket_pair' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'stream_wrapper_restore' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'stream_wrapper_unregister' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'inet_ntop' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'inet_pton' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'apache_reset_timeout' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'htmlspecialchars_decode' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'array_diff_key' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'array_diff_ukey' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'array_intersect_key' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'array_intersect_ukey' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'array_product' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'property_exists' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'libxml_clear_errors' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ),
        'libxml_get_errors' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ),
        'libxml_get_last_error' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ),
        'libxml_set_streams_context' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ),
        'libxml_use_internal_errors' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ),
        'fbsql_rows_fetched' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'fbsql_set_characterset' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'mysqli_get_charset' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_get_warnings' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_get_warnings' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_embedded_server_end' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_embedded_server_start' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'mysqli',
        ),
        'pg_execute' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'pg_fetch_all_columns' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'pg_field_type_oid' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'pg_prepare' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'pg_query_params' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'pg_result_error_field' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'pg_send_execute' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'pg_send_prepare' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'pg_send_query_params' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'pg_set_error_verbosity' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'pg_transaction_status' => array(
            '5.0' => false,
            '5.1' => true,
        ),

        'date_sun_info' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
        ),
        'hash_algos' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ),
        'hash_file' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ),
        'hash_final' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ),
        'hash_hmac' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ),
        'hash_hmac_file' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ),
        'hash_init' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ),
        'hash_update_file' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ),
        'hash_update_stream' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ),
        'hash_update' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ),
        'hash' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ),

        'xmlwriter_end_attribute' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_end_cdata' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_end_comment' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_end_document' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_end_dtd_attlist' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_end_dtd_element' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_end_dtd' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_end_element' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_end_pi' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_flush' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_open_memory' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_open_uri' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_output_memory' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_set_indent_string' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_set_indent' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_start_attribute_ns' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_start_attribute' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_start_cdata' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_start_comment' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_start_document' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_start_dtd_attlist' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_start_dtd_element' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_start_dtd' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_start_element_ns' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_start_element' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_start_pi' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_text' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_write_attribute_ns' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_write_attribute' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_write_cdata' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_write_comment' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_write_dtd_attlist' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_write_dtd_element' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_write_dtd' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_write_element_ns' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_write_element' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_write_pi' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ),
        'oci_bind_array_by_name' => array(
            '5.1.1' => false,
            '5.1.2' => true,
        ),

        'imap_savebody' => array(
            '5.1.2' => false,
            '5.1.3' => true,
        ),
        'lchgrp' => array(
            '5.1.2' => false,
            '5.1.3' => true,
        ),
        'lchown' => array(
            '5.1.2' => false,
            '5.1.3' => true,
        ),
        'timezone_name_from_abbr' => array(
            '5.1.2' => false,
            '5.1.3' => true,
        ),
        'sys_getloadavg' => array(
            '5.1.2' => false,
            '5.1.3' => true,
        ),
        'curl_setopt_array' => array(
            '5.1.2' => false,
            '5.1.3' => true,
        ),

        'array_fill_keys' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'error_get_last' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'image_type_to_extension' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'memory_get_peak_usage' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'date_create' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'date_date_set' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'date_format' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'date_isodate_set' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'date_modify' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'date_offset_get' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'date_parse' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'date_time_set' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'date_timezone_get' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'date_timezone_set' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'timezone_abbreviations_list' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'timezone_identifiers_list' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'timezone_name_get' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'timezone_offset_get' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'timezone_open' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'timezone_transitions_get' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'mb_stripos' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'mb_stristr' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'mb_strrchr' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'mb_strrichr' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'mb_strripos' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'mb_strstr' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'ming_setswfcompression' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'openssl_csr_get_public_key' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'openssl_csr_get_subject' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'openssl_pkey_get_details' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'spl_object_hash' => array(
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'spl',
        ),
        'preg_last_error' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'pg_field_table' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'posix_initgroups' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'gmp_nextprime' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'xmlwriter_full_end_element' => array(
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_write_raw' => array(
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'xmlwriter',
        ),
        'filter_has_var' => array(
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ),
        'filter_id' => array(
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ),
        'filter_input_array' => array(
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ),
        'filter_input' => array(
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ),
        'filter_list' => array(
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ),
        'filter_var_array' => array(
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ),
        'filter_var' => array(
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ),
        'json_decode' => array(
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'json',
        ),
        'json_encode' => array(
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'json',
        ),
        'zip_close' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'zip_entry_close' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'zip_entry_compressedsize' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'zip_entry_compressionmethod' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'zip_entry_filesize' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'zip_entry_name' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'zip_entry_open' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'zip_entry_read' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'zip_open' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'zip_read' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'stream_notification_callback' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'snmp_set_oid_output_format' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'snmp2_get' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'snmp2_getnext' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'snmp2_real_walk' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'snmp2_set' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'snmp2_walk' => array(
            '5.1' => false,
            '5.2' => true,
        ),

        'sys_get_temp_dir' => array(
            '5.2.0' => false,
            '5.2.1' => true,
        ),
        'stream_socket_shutdown' => array(
            '5.2.0' => false,
            '5.2.1' => true,
        ),
        'xmlwriter_start_dtd_entity' => array(
            '5.2.0'       => false,
            '5.2.1'       => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_end_dtd_entity' => array(
            '5.2.0'       => false,
            '5.2.1'       => true,
            'extension' => 'xmlwriter',
        ),
        'xmlwriter_write_dtd_entity' => array(
            '5.2.0'       => false,
            '5.2.1'       => true,
            'extension' => 'xmlwriter',
        ),

        'imagegrabscreen' => array(
            '5.2.1' => false,
            '5.2.2' => true,
        ),
        'imagegrabwindow' => array(
            '5.2.1' => false,
            '5.2.2' => true,
        ),
        'openssl_pkcs12_export_to_file' => array(
            '5.2.1' => false,
            '5.2.2' => true,
        ),
        'openssl_pkcs12_export' => array(
            '5.2.1' => false,
            '5.2.2' => true,
        ),
        'openssl_pkcs12_read' => array(
            '5.2.1' => false,
            '5.2.2' => true,
        ),

        'mysql_set_charset' => array(
            '5.2.2' => false,
            '5.2.3' => true,
        ),

        'php_ini_loaded_file' => array(
            '5.2.3' => false,
            '5.2.4' => true,
        ),
        'stream_is_local' => array(
            '5.2.3' => false,
            '5.2.4' => true,
        ),

        'libxml_disable_entity_loader' => array(
            '5.2.11'    => false,
            '5.2.12'    => true,
            'extension' => 'libxml',
        ),

        'array_replace' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'array_replace_recursive' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'class_alias' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'forward_static_call' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'forward_static_call_array' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'gc_collect_cycles' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'gc_disable' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'gc_enable' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'gc_enabled' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'get_called_class' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'gethostname' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'header_remove' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'lcfirst' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'parse_ini_string' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'quoted_printable_encode' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'str_getcsv' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'stream_context_set_default' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'stream_supports_lock' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'stream_context_get_params' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'date_add' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'date_create_from_format' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'date_diff' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'date_get_last_errors' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'date_interval_create_from_date_string' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'date_interval_format' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'date_parse_from_format' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'date_sub' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'date_timestamp_get' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'date_timestamp_set' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'timezone_location_get' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'timezone_version_get' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'gmp_testbit' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'hash_copy' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'hash',
        ),
        'imap_gc' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'imap_utf8_to_mutf7' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'imap_mutf7_to_utf8' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'json_last_error' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'json',
        ),
        'mysqli_get_cache_stats' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_fetch_all' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_get_connection_status' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_poll' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_reap_async_query' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_get_connection_stats' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_get_client_stats' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_refresh' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_result_fetch_all' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_get_result' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_more_results' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_next_result' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ),
        'openssl_random_pseudo_bytes' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'pcntl_signal_dispatch' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'pcntl_sigprocmask' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'pcntl_sigtimedwait' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'pcntl_sigwaitinfo' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'preg_filter' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'msg_queue_exists' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'shm_has_vars' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'acosh' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'asinh' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'atanh' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'expm1' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'log1p' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'enchant_broker_describe' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_broker_dict_exists' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_broker_free_dict' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_broker_free' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_broker_get_error' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_broker_init' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_broker_list_dicts' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_broker_request_dict' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_broker_request_pwl_dict' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_broker_set_ordering' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_dict_add_to_personal' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_dict_add_to_session' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_dict_check' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_dict_describe' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_dict_get_error' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_dict_is_in_session' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_dict_quick_check' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_dict_store_replacement' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'enchant_dict_suggest' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ),
        'finfo_buffer' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ),
        'finfo_close' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ),
        'finfo_file' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ),
        'finfo_open' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ),
        'finfo_set_flags' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ),
        'collator_asort' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'collator_compare' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'collator_create' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'collator_get_attribute' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'collator_get_error_code' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'collator_get_error_message' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'collator_get_locale' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'collator_get_strength' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'collator_set_attribute' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'collator_set_strength' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'collator_sort_with_sort_keys' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'collator_sort' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_create' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_get_datetype' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_get_timetype' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_get_calendar' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_set_calendar' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_get_timezone_id' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_set_timezone_id' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_set_pattern' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_get_pattern' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_get_locale' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_set_lenient' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_is_lenient' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_format' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_parse' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_localtime' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_get_error_code' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'datefmt_get_error_message' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'grapheme_extract' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'grapheme_stripos' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'grapheme_stristr' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'grapheme_strlen' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'grapheme_strpos' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'grapheme_strripos' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'grapheme_strrpos' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'grapheme_strstr' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'grapheme_substr' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'idn_to_ascii' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'idn_to_utf8' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'intl_error_name' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'intl_get_error_code' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'intl_get_error_message' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'intl_is_failure' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_accept_from_http' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_canonicalize' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_compose' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_filter_matches' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_get_all_variants' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_get_default' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_get_display_language' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_get_display_name' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_get_display_region' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_get_display_script' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_get_display_variant' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_get_keywords' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_get_primary_language' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_get_region' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_get_script' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_lookup' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_parse' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'locale_set_default' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'msgfmt_create' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'msgfmt_format_message' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'msgfmt_format' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'msgfmt_get_error_code' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'msgfmt_get_error_message' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'msgfmt_get_locale' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'msgfmt_get_pattern' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'msgfmt_parse_message' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'msgfmt_parse' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'msgfmt_set_pattern' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'normalizer_is_normalized' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'normalizer_normalize' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'numfmt_create' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'numfmt_format_currency' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'numfmt_format' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'numfmt_get_attribute' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'numfmt_get_error_code' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'numfmt_get_error_message' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'numfmt_get_locale' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'numfmt_get_pattern' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'numfmt_get_symbol' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'numfmt_get_text_attribute' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'numfmt_parse_currency' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'numfmt_parse' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'numfmt_set_attribute' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'numfmt_set_pattern' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'numfmt_set_symbol' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'numfmt_set_text_attribute' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'openssl_decrypt' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'openssl_dh_compute_key' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'openssl_digest' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'openssl_encrypt' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'openssl_get_cipher_methods' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'openssl_get_md_methods' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'mb_encoding_aliases' => array(
            '5.2' => false,
            '5.3' => true,
        ),

        'enchant_broker_get_dict_path' => array(
            '5.3.0'     => false,
            '5.3.1'     => true,
            'extension' => 'enchant',
        ),
        'enchant_broker_set_dict_path' => array(
            '5.3.0'     => false,
            '5.3.1'     => true,
            'extension' => 'enchant',
        ),

        'collator_get_sort_key' => array(
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'intl',
        ),
        'resourcebundle_count' => array(
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'intl',
        ),
        'resourcebundle_create' => array(
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'intl',
        ),
        'resourcebundle_get_error_code' => array(
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'intl',
        ),
        'resourcebundle_get_error_message' => array(
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'intl',
        ),
        'resourcebundle_get' => array(
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'intl',
        ),
        'resourcebundle_locales' => array(
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'intl',
        ),
        'realpath_cache_get' => array(
            '5.3.1' => false,
            '5.3.2' => true,
        ),
        'realpath_cache_size' => array(
            '5.3.1' => false,
            '5.3.2' => true,
        ),
        'stream_resolve_include_path' => array(
            '5.3.1' => false,
            '5.3.2' => true,
        ),
        'oci_set_action' => array(
            '5.3.1' => false,
            '5.3.2' => true,
        ),
        'oci_set_client_info' => array(
            '5.3.1' => false,
            '5.3.2' => true,
        ),
        'oci_set_client_identifier' => array(
            '5.3.1' => false,
            '5.3.2' => true,
        ),
        'oci_set_edition' => array(
            '5.3.1' => false,
            '5.3.2' => true,
        ),
        'oci_set_module_name' => array(
            '5.3.1' => false,
            '5.3.2' => true,
        ),

        'stream_set_read_buffer' => array(
            '5.3.2' => false,
            '5.3.3' => true,
        ),
        'openssl_cipher_iv_length' => array(
            '5.3.2' => false,
            '5.3.3' => true,
        ),
        'fastcgi_finish_request' => array(
            '5.3.2'     => false,
            '5.3.3'     => true,
            'extension' => 'fastcgi',
        ),

        'pcntl_errno' => array(
            '5.3.3' => false,
            '5.3.4' => true,
        ),
        'pcntl_get_last_error' => array(
            '5.3.3' => false,
            '5.3.4' => true,
        ),
        'pcntl_strerror' => array(
            '5.3.3' => false,
            '5.3.4' => true,
        ),

        'imap_fetchmime' => array(
            '5.3.5' => false,
            '5.3.6' => true,
        ),

        'oci_client_version' => array(
            '5.3.6' => false,
            '5.3.7' => true,
        ),

        'hex2bin' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'http_response_code' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'get_declared_traits' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'getimagesizefromstring' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'imagecreatefromwebp' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'imagewebp' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'stream_set_chunk_size' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'socket_import_stream' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'trait_exists' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'header_register_callback' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'class_uses' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'spl',
        ),
        'session_status' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'session_register_shutdown' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'mysqli_error_list' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_stmt_error_list' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'mysqli',
        ),
        'libxml_set_external_entity_loader' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'libxml',
        ),
        'ldap_control_paged_result' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'ldap_control_paged_result_response' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'transliterator_create' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ),
        'transliterator_create_from_rules' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ),
        'transliterator_create_inverse' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ),
        'transliterator_get_error_code' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ),
        'transliterator_get_error_message' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ),
        'transliterator_list_ids' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ),
        'transliterator_transliterate' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ),
        'zlib_decode' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'zlib_encode' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'gzdecode' => array(
            '5.3' => false,
            '5.4' => true,
        ),

        'mb_ereg_replace_callback' => array(
            '5.4.0' => false,
            '5.4.1' => true,
        ),

        'pg_escape_literal' => array(
            '5.4.3' => false,
            '5.4.4' => true,
        ),
        'pg_escape_identifier' => array(
            '5.4.3' => false,
            '5.4.4' => true,
        ),

        'array_column' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'boolval' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'json_last_error_msg' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'json',
        ),
        'password_get_info' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'password',
        ),
        'password_hash' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'password',
        ),
        'password_needs_rehash' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'password',
        ),
        'password_verify' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'password',
        ),
        'hash_pbkdf2' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'hash',
        ),
        'openssl_pbkdf2' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'curl_escape' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'curl_file_create' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'curl_multi_setopt' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'curl_multi_strerror' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'curl_pause' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'curl_reset' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'curl_share_close' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'curl_share_init' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'curl_share_setopt' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'curl_strerror' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'curl_unescape' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'date_create_immutable_from_format' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'date_create_immutable' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'imageaffine' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'imageaffinematrixconcat' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'imageaffinematrixget' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'imagecrop' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'imagecropauto' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'imageflip' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'imagepalettetotruecolor' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'imagescale' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'imagesetinterpolation' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'mysqli_begin_transaction' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_release_savepoint' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'mysqli',
        ),
        'mysqli_savepoint' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'mysqli',
        ),
        'socket_sendmsg' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'socket_recvmsg' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'socket_cmsg_space' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'cli_get_process_title' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'cli_set_process_title' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'datefmt_format_object' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'datefmt_get_calendar_object' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'datefmt_get_timezone' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'datefmt_set_timezone' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_create_instance' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_keyword_values_for_locale' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_now' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_available_locales' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_time' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_set_time' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_add' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_set_time_zone' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_after' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_before' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_set' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_roll' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_clear' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_field_difference' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_actual_maximum' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_actual_minumum' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_day_of_week_type' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_first_day_of_week' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_greatest_minimum' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_least_maximum' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_locale' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_maximum' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_minimal_days_in_first_week' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_minimum' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_time_zone' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_type' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_weekend_transition' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_in_daylight_time' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_is_equivalent_to' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_is_lenient' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_is_set' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_is_weekend' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_equals' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_repeated_wall_time_option' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_skipped_wall_time_option' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_set_first_day_of_week' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_set_lenient' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_set_minimal_days_in_first_week' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_set_repeated_wall_time_option' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_set_skipped_wall_time_option' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_from_date_time' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_to_date_time' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_error_code' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlcal_get_error_message' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlgregcal_create_instance' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlgregcal_set_gregorian_change' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlgregcal_get_gregorian_change' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intlgregcal_is_leap_year' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_create_time_zone' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_create_default' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_get_id' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_get_gmt' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_get_unknown' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_create_enumeration' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_count_equivalent_ids' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_create_time_zone_id_enumeration' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_get_canonical_id' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_get_region' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_get_tz_data_version' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_get_equivalent_id' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_use_daylight_time' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_get_offset' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_get_raw_offset' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_has_same_rules' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_get_display_name' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_get_dst_savings' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_from_date_time_zone' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_to_date_time_zone' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_get_error_code' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'intltz_get_error_message' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'opcache_get_configuration' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ),
        'opcache_get_status' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ),
        'opcache_invalidate' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ),
        'opcache_reset' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ),

        'opcache_compile_file' => array(
            '5.5.4'     => false,
            '5.5.5'     => true,
            'extension' => 'opcache',
        ),

        'opcache_is_script_cached' => array(
            '5.5.10'    => false,
            '5.5.11'    => true,
            'extension' => 'opcache',
        ),

        'gmp_root' => array(
            '5.5' => false,
            '5.6' => true,
        ),
        'gmp_rootrem' => array(
            '5.5' => false,
            '5.6' => true,
        ),
        'hash_equals' => array(
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'hash',
        ),
        'ldap_escape' => array(
            '5.5' => false,
            '5.6' => true,
        ),
        'ldap_modify_batch' => array(
            '5.4.25' => false,
            '5.5.9'  => false,
            '5.4.26' => true,
            '5.5.10' => true,
            '5.6.0'  => true,
        ),
        'mysqli_get_links_stats' => array(
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'mysqli',
        ),
        'openssl_get_cert_locations' => array(
            '5.5' => false,
            '5.6' => true,
        ),
        'openssl_x509_fingerprint' => array(
            '5.5' => false,
            '5.6' => true,
        ),
        'openssl_spki_new' => array(
            '5.5' => false,
            '5.6' => true,
        ),
        'openssl_spki_verify' => array(
            '5.5' => false,
            '5.6' => true,
        ),
        'openssl_spki_export_challenge' => array(
            '5.5' => false,
            '5.6' => true,
        ),
        'openssl_spki_export' => array(
            '5.5' => false,
            '5.6' => true,
        ),
        'phpdbg_clear' => array(
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'phpdbg',
        ),
        'phpdbg_color' => array(
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'phpdbg',
        ),
        'phpdbg_exec' => array(
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'phpdbg',
        ),
        'phpdbg_prompt' => array(
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'phpdbg',
        ),
        'pg_connect_poll' => array(
            '5.5' => false,
            '5.6' => true,
        ),
        'pg_consume_input' => array(
            '5.5' => false,
            '5.6' => true,
        ),
        'pg_flush' => array(
            '5.5' => false,
            '5.6' => true,
        ),
        'pg_lo_truncate' => array(
            '5.5' => false,
            '5.6' => true,
        ),
        'pg_socket' => array(
            '5.5' => false,
            '5.6' => true,
        ),
        'session_abort' => array(
            '5.5' => false,
            '5.6' => true,
        ),
        'session_reset' => array(
            '5.5' => false,
            '5.6' => true,
        ),
        'oci_get_implicit_resultset' => array(
            '5.5' => false,
            '5.6' => true,
        ),

        'gmp_export' => array(
            '5.6.0' => false,
            '5.6.1' => true,
        ),
        'gmp_import' => array(
            '5.6.0' => false,
            '5.6.1' => true,
        ),

        'gmp_random_bits' => array(
            '5.6.2'     => false,
            '5.6.3'     => true,
        ),
        'gmp_random_range' => array(
            '5.6.2'     => false,
            '5.6.3'     => true,
        ),
        'phpdbg_break_file' => array(
            '5.6.2'     => false,
            '5.6.3'     => true,
            'extension' => 'phpdbg',
        ),
        'phpdbg_break_function' => array(
            '5.6.2'     => false,
            '5.6.3'     => true,
            'extension' => 'phpdbg',
        ),
        'phpdbg_break_method' => array(
            '5.6.2'     => false,
            '5.6.3'     => true,
            'extension' => 'phpdbg',
        ),
        'phpdbg_break_next' => array(
            '5.6.2'     => false,
            '5.6.3'     => true,
            'extension' => 'phpdbg',
        ),

        'random_bytes' => array(
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'csprng',
        ),
        'random_int' => array(
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'csprng',
        ),
        'error_clear_last' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'gmp_random_seed' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'intdiv' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'preg_replace_callback_array' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'gc_mem_caches' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'get_resources' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'phpdbg_end_oplog' => array(
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'phpdbg',
        ),
        'phpdbg_get_executable' => array(
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'phpdbg',
        ),
        'phpdbg_start_oplog' => array(
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'phpdbg',
        ),
        'posix_setrlimit' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'inflate_add' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'deflate_add' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'inflate_init' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'deflate_init' => array(
            '5.6' => false,
            '7.0' => true,
        ),

        'socket_export_stream' => array(
            '7.0.6' => false,
            '7.0.7' => true,
        ),

        'curl_multi_errno' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'curl_share_errno' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'curl_share_strerror' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'is_iterable' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'pcntl_async_signals' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'pcntl_signal_get_handler' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'session_create_id' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'session_gc' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'sapi_windows_cp_set' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'sapi_windows_cp_get' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'sapi_windows_cp_is_utf8' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'sapi_windows_cp_conv' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'openssl_get_curve_names' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'intltz_get_id_for_windows_id' => array(
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'intl',
        ),
        'intltz_get_windows_id' => array(
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'intl',
        ),

        'hash_hkdf' => array(
            '7.1.1'     => false,
            '7.1.2'     => true,
            'extension' => 'hash',
        ),
        'oci_register_taf_callback' => array(
            '7.1.6' => false,
            '7.1.7' => true,
        ),
        'oci_unregister_taf_callback' => array(
            '7.1.8' => false,
            '7.1.9' => true,
        ),

        'stream_isatty' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'sapi_windows_vt100_support' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'ftp_append' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'ftp_mlsd' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'hash_hmac_algos' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'hash',
        ),
        'imagebmp' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'imagecreatefrombmp' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'imagegetclip' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'imageopenpolygon' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'imageresolution' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'imagesetclip' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'inflate_get_read_len' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'inflate_get_status' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'ldap_exop' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'ldap_exop_passwd' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'ldap_exop_whoami' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'ldap_parse_exop' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'mb_chr' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'mb_ord' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'mb_scrub' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'socket_addrinfo_lookup' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'socket_addrinfo_connect' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'socket_addrinfo_bind' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'socket_addrinfo_explain' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'spl_object_id' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'spl',
        ),
        'sodium_add' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_base642bin' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_bin2base64' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_bin2hex' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_compare' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_aead_aes256gcm_decrypt' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_aead_aes256gcm_encrypt' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_aead_aes256gcm_is_available' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_aead_aes256gcm_keygen' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_aead_chacha20poly1305_decrypt' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_aead_chacha20poly1305_encrypt' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_aead_chacha20poly1305_ietf_decrypt' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_aead_chacha20poly1305_ietf_encrypt' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_aead_chacha20poly1305_ietf_keygen' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_aead_chacha20poly1305_keygen' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_aead_xchacha20poly1305_ietf_decrypt' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_aead_xchacha20poly1305_ietf_encrypt' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_aead_xchacha20poly1305_ietf_keygen' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_auth_keygen' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_auth_verify' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_auth' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_box_keypair_from_secretkey_and_publickey' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_box_keypair' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_box_open' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_box_publickey_from_secretkey' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_box_publickey' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_box_seal_open' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_box_seal' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_box_secretkey' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_box_seed_keypair' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_box' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_generichash_final' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_generichash_init' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_generichash_keygen' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_generichash_update' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_generichash' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_kdf_derive_from_key' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_kdf_keygen' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_kx_client_session_keys' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_kx_keypair' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_kx_publickey' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_kx_secretkey' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_kx_seed_keypair' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_kx_server_session_keys' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_pwhash_scryptsalsa208sha256_str_verify' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_pwhash_scryptsalsa208sha256_str' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_pwhash_scryptsalsa208sha256' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_pwhash_str_needs_rehash' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_pwhash_str_verify' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_pwhash_str' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_pwhash' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_scalarmult_base' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_scalarmult' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_secretbox_keygen' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_secretbox_open' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_secretbox' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_secretstream_xchacha20poly1305_init_pull' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_secretstream_xchacha20poly1305_init_push' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_secretstream_xchacha20poly1305_keygen' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_secretstream_xchacha20poly1305_pull' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_secretstream_xchacha20poly1305_push' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_secretstream_xchacha20poly1305_rekey' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_shorthash_keygen' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_shorthash' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_sign_detached' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_sign_ed25519_pk_to_curve25519' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_sign_ed25519_sk_to_curve25519' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_sign_keypair_from_secretkey_and_publickey' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_sign_keypair' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_sign_open' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_sign_publickey_from_secretkey' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_sign_publickey' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_sign_secretkey' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_sign_seed_keypair' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_sign_verify_detached' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_sign' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_stream_keygen' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_stream_xor' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_crypto_stream' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_hex2bin' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_increment' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_memcmp' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_memzero' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_pad' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'sodium_unpad' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ),
        'openssl_pkcs7_read' => array(
            '7.1' => false,
            '7.2' => true,
        ),

        // Introduced in 7.2.14 and 7.3.1 simultanously.
        'oci_set_call_timeout' => array(
            '7.2.13' => false,
            '7.2.14' => true,
        ),
        // Introduced in 7.2.14 and 7.3.1 simultanously.
        'oci_set_db_operation' => array(
            '7.2.13' => false,
            '7.2.14' => true,
        ),

        'hrtime' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'is_countable' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'array_key_first' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'array_key_last' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'fpm_get_status' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'net_get_interfaces' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'gmp_binomial' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'gmp_lcm' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'gmp_perfect_power' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'gmp_kronecker' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'ldap_add_ext' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'ldap_bind_ext' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'ldap_delete_ext' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'ldap_exop_refresh' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'ldap_mod_add_ext' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'ldap_mod_replace_ext' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'ldap_mod_del_ext' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'ldap_rename_ext' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'normalizer_get_raw_decomposition' => array(
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'intl',
        ),
        'openssl_pkey_derive' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'socket_wsaprotocol_info_export' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'socket_wsaprotocol_info_import' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'socket_wsaprotocol_info_release' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'gc_status' => array(
            '7.2' => false,
            '7.3' => true,
        ),

        'get_mangled_object_vars' => array(
            '7.3' => false,
            '7.4' => true,
        ),
        'imagecreatefromtga' => array(
            '7.3' => false,
            '7.4' => true,
        ),
        'mb_str_split' => array(
            '7.3' => false,
            '7.4' => true,
        ),
        'openssl_x509_verify' => array(
            '7.3' => false,
            '7.4' => true,
        ),
        'password_algos' => array(
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'password',
        ),
        'pcntl_unshare' => array(
            '7.3' => false,
            '7.4' => true,
        ),
        'sapi_windows_set_ctrl_handler' => array(
            '7.3' => false,
            '7.4' => true,
        ),
        'sapi_windows_generate_ctrl_event' => array(
            '7.3' => false,
            '7.4' => true,
        ),

        'fdiv' => array(
            '7.4' => false,
            '8.0' => true,
        ),
        'get_debug_type' => array(
            '7.4' => false,
            '8.0' => true,
        ),
        'get_resource_id' => array(
            '7.4' => false,
            '8.0' => true,
        ),
        'preg_last_error_msg' => array(
            '7.4' => false,
            '8.0' => true,
        ),
        'str_contains' => array(
            '7.4' => false,
            '8.0' => true,
        ),
        'str_ends_with' => array(
            '7.4' => false,
            '8.0' => true,
        ),
        'str_starts_with' => array(
            '7.4' => false,
            '8.0' => true,
        ),
        'imagegetinterpolation' => array(
            '7.4' => false,
            '8.0' => true,
        ),
        'enchant_dict_add' => array(
            '7.4' => false,
            '8.0' => true,
        ),
        'enchant_dict_is_added' => array(
            '7.4' => false,
            '8.0' => true,
        ),
        'ldap_count_references' => array(
            '7.4' => false,
            '8.0' => true,
        ),
        'openssl_cms_encrypt' => array(
            '7.4' => false,
            '8.0' => true,
        ),
        'openssl_cms_decrypt' => array(
            '7.4' => false,
            '8.0' => true,
        ),
        'openssl_cms_read' => array(
            '7.4' => false,
            '8.0' => true,
        ),
        'openssl_cms_sign' => array(
            '7.4' => false,
            '8.0' => true,
        ),
        'openssl_cms_verify' => array(
            '7.4' => false,
            '8.0' => true,
        ),
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 5.6
     *
     * @return array
     */
    public function register()
    {
        // Handle case-insensitivity of function names.
        $this->newFunctions = \array_change_key_case($this->newFunctions, \CASE_LOWER);

        return array(\T_STRING);
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
        $functionLc = strtolower($function);

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

        $ignore = array(
            \T_DOUBLE_COLON    => true,
            \T_OBJECT_OPERATOR => true,
            \T_NEW             => true,
        );

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

        $itemInfo = array(
            'name'   => $function,
            'nameLc' => $functionLc,
        );
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
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
        return $this->newFunctions[$itemInfo['nameLc']];
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
        return 'The function %s() is not present in PHP version %s or earlier';
    }
}
