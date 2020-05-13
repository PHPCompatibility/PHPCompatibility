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

use PHPCompatibility\AbstractRemovedFeatureSniff;
use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * Detect calls to deprecated/removed native PHP functions.
 *
 * Suggests alternative if available.
 *
 * PHP version All
 *
 * @since 5.5
 * @since 5.6   Now extends the base `Sniff` class instead of the upstream
 *              `Generic.PHP.ForbiddenFunctions` sniff.
 * @since 7.1.0 Now extends the `AbstractRemovedFeatureSniff` instead of the base `Sniff` class.
 * @since 9.0.0 Renamed from `DeprecatedFunctionsSniff` to `RemovedFunctionsSniff`.
 */
class RemovedFunctionsSniff extends AbstractRemovedFeatureSniff
{
    /**
     * A list of deprecated and removed functions with their alternatives.
     *
     * The array lists : version number with false (deprecated) or true (removed) and an alternative function.
     * If no alternative exists, it is NULL, i.e, the function should just not be used.
     *
     * @since 5.5
     * @since 5.6   Visibility changed from `protected` to `public`.
     * @since 7.0.2 Visibility changed back from `public` to `protected`.
     *              The earlier change was made to be in line with the upstream sniff,
     *              but that sniff is no longer being extended.
     * @since 7.0.8 Property renamed from `$forbiddenFunctions` to `$removedFunctions`.
     *
     * @var array(string => array(string => bool|string|null))
     */
    protected $removedFunctions = array(
        'crack_check' => array(
            '5.0'       => true,
            'extension' => 'crack',
        ),
        'crack_closedict' => array(
            '5.0'       => true,
            'extension' => 'crack',
        ),
        'crack_getlastmessage' => array(
            '5.0'       => true,
            'extension' => 'crack',
        ),
        'crack_opendict' => array(
            '5.0'       => true,
            'extension' => 'crack',
        ),

        'php_check_syntax' => array(
            '5.0.5' => true,
            'alternative' => null,
        ),

        'pfpro_cleanup' => array(
            '5.1' => true,
            'alternative' => null,
        ),
        'pfpro_init' => array(
            '5.1' => true,
            'alternative' => null,
        ),
        'pfpro_process_raw' => array(
            '5.1' => true,
            'alternative' => null,
        ),
        'pfpro_process' => array(
            '5.1' => true,
            'alternative' => null,
        ),
        'pfpro_version' => array(
            '5.1' => true,
            'alternative' => null,
        ),
        'm_checkstatus' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_completeauthorizations' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_connect' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_connectionerror' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_deletetrans' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_destroyconn' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_destroyengine' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_getcell' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_getcellbynum' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_getcommadelimited' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_getheader' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_initconn' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_initengine' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_iscommadelimited' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_maxconntimeout' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_monitor' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_numcolumns' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_numrows' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_parsecommadelimited' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_responsekeys' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_responseparam' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_returnstatus' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_setblocking' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_setdropfile' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_setip' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_setssl_cafile' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_setssl_files' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_setssl' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_settimeout' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_sslcert_gen_hash' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_transactionssent' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_transinqueue' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_transkeyval' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_transnew' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_transsend' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_uwait' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_validateidentifier' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_verifyconnection' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'm_verifysslcert' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'dio_close' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'dio_fcntl' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'dio_open' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'dio_read' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'dio_seek' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'dio_stat' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'dio_tcsetattr' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'dio_truncate' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'dio_write' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'fam_cancel_monitor' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'fam_close' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'fam_monitor_collection' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'fam_monitor_directory' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'fam_monitor_file' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'fam_next_event' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'fam_open' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'fam_pending' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'fam_resume_monitor' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'fam_suspend_monitor' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'yp_all' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'yp_cat' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'yp_err_string' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'yp_errno' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'yp_first' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'yp_get_default_domain' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'yp_master' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'yp_match' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'yp_next' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'yp_order' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),

        'udm_add_search_limit' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_alloc_agent_array' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_alloc_agent' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_api_version' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_cat_list' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_cat_path' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_check_charset' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_clear_search_limits' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_crc32' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_errno' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_error' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_find' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_free_agent' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_free_ispell_data' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_free_res' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_get_doc_count' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_get_res_field' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_get_res_param' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_hash32' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_load_ispell_data' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'udm_set_agent_param' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'w32api_deftype' => array(
            '5.1'       => true,
            'extension' => 'w32api',
        ),
        'w32api_init_dtype' => array(
            '5.1'       => true,
            'extension' => 'w32api',
        ),
        'w32api_invoke_function' => array(
            '5.1'       => true,
            'extension' => 'w32api',
        ),
        'w32api_register_function' => array(
            '5.1'       => true,
            'extension' => 'w32api',
        ),
        'w32api_set_call_method' => array(
            '5.1'       => true,
            'extension' => 'w32api',
        ),

        'msession_connect' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_count' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_create' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_destroy' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_disconnect' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_find' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_get_array' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_get_data' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_get' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_inc' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_list' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_listvar' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_lock' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_plugin' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_randstr' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_set_array' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_set_data' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_set' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_timeout' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_uniq' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),
        'msession_unlock' => array(
            '5.1.3'     => true,
            'extension' => 'msession',
        ),

        'hwapi_attribute_new' => array(
            '5.2'       => true,
            'extension' => 'hwapi',
        ),
        'hwapi_content_new' => array(
            '5.2'       => true,
            'extension' => 'hwapi',
        ),
        'hwapi_hgcsp' => array(
            '5.2'       => true,
            'extension' => 'hwapi',
        ),
        'hwapi_object_new' => array(
            '5.2'       => true,
            'extension' => 'hwapi',
        ),

        'ncurses_addch' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_addchnstr' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_addchstr' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_addnstr' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_addstr' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_assume_default_colors' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_attroff' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_attron' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_attrset' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_baudrate' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_beep' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_bkgd' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_bkgdset' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_border' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_bottom_panel' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_can_change_color' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_cbreak' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_clear' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_clrtobot' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_clrtoeol' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_color_content' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_color_set' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_curs_set' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_def_prog_mode' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_def_shell_mode' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_define_key' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_del_panel' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_delay_output' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_delch' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_deleteln' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_delwin' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_doupdate' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_echo' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_echochar' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_end' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_erase' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_erasechar' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_filter' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_flash' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_flushinp' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_getch' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_getmaxyx' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_getmouse' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_getyx' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_halfdelay' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_has_colors' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_has_ic' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_has_il' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_has_key' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_hide_panel' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_hline' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_inch' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_init_color' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_init_pair' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_init' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_insch' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_insdelln' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_insertln' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_insstr' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_instr' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_isendwin' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_keyok' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_keypad' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_killchar' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_longname' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_meta' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_mouse_trafo' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_mouseinterval' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_mousemask' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_move_panel' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_move' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_mvaddch' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_mvaddchnstr' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_mvaddchstr' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_mvaddnstr' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_mvaddstr' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_mvcur' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_mvdelch' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_mvgetch' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_mvhline' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_mvinch' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_mvvline' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_mvwaddstr' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_napms' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_new_panel' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_newpad' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_newwin' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_nl' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_nocbreak' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_noecho' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_nonl' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_noqiflush' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_noraw' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_pair_content' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_panel_above' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_panel_below' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_panel_window' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_pnoutrefresh' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_prefresh' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_putp' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_qiflush' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_raw' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_refresh' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_replace_panel' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_reset_prog_mode' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_reset_shell_mode' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_resetty' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_savetty' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_scr_dump' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_scr_init' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_scr_restore' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_scr_set' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_scrl' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_show_panel' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_slk_attr' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_slk_attroff' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_slk_attron' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_slk_attrset' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_slk_clear' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_slk_color' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_slk_init' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_slk_noutrefresh' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_slk_refresh' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_slk_restore' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_slk_set' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_slk_touch' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_standend' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_standout' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_start_color' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_termattrs' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_termname' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_timeout' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_top_panel' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_typeahead' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_ungetch' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_ungetmouse' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_update_panels' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_use_default_colors' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_use_env' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_use_extended_names' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_vidattr' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_vline' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_waddch' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_waddstr' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_wattroff' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_wattron' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_wattrset' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_wborder' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_wclear' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_wcolor_set' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_werase' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_wgetch' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_whline' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_wmouse_trafo' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_wmove' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_wnoutrefresh' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_wrefresh' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_wstandend' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_wstandout' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'ncurses_wvline' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'fdf_add_doc_javascript' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_add_template' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_close' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_create' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_enum_values' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_errno' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_error' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_get_ap' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_get_attachment' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_get_encoding' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_get_file' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_get_flags' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_get_opt' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_get_status' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_get_value' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_get_version' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_header' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_next_field_name' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_open_string' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_open' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_remove_item' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_save_string' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_save' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_set_ap' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_set_encoding' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_set_file' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_set_flags' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_set_javascript_action' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_set_on_import_javascript' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_set_opt' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_set_status' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_set_submit_form_action' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_set_target_frame' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_set_value' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'fdf_set_version' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'ming_keypress' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'ming_setcubicthreshold' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'ming_setscale' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'ming_setswfcompression' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'ming_useconstants' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'ming_useswfversion' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),

        'call_user_method' => array(
            '4.1' => false,
            '7.0' => true,
            'alternative' => 'call_user_func()',
        ),
        'call_user_method_array' => array(
            '4.1' => false,
            '7.0' => true,
            'alternative' => 'call_user_func_array()',
        ),
        'define_syslog_variables' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => null,
        ),
        'dl' => array(
            '5.3' => false,
            'alternative' => null,
        ),
        'ereg' => array(
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'preg_match()',
            'extension'   => 'ereg',
        ),
        'ereg_replace' => array(
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'preg_replace()',
            'extension'   => 'ereg',
        ),
        'eregi' => array(
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'preg_match() (with the i modifier)',
            'extension'   => 'ereg',
        ),
        'eregi_replace' => array(
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'preg_replace() (with the i modifier)',
            'extension'   => 'ereg',
        ),
        'imagepsbbox' => array(
            '7.0' => true,
            'alternative' => null,
        ),
        'imagepsencodefont' => array(
            '7.0' => true,
            'alternative' => null,
        ),
        'imagepsextendfont' => array(
            '7.0' => true,
            'alternative' => null,
        ),
        'imagepsfreefont' => array(
            '7.0' => true,
            'alternative' => null,
        ),
        'imagepsloadfont' => array(
            '7.0' => true,
            'alternative' => null,
        ),
        'imagepsslantfont' => array(
            '7.0' => true,
            'alternative' => null,
        ),
        'imagepstext' => array(
            '7.0' => true,
            'alternative' => null,
        ),
        'import_request_variables' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => null,
        ),
        'ldap_sort' => array(
            '7.0' => false,
            'alternative' => null,
        ),
        'mcrypt_generic_end' => array(
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'mcrypt_generic_deinit()',
            'extension'   => 'mcrypt',
        ),
        'mysql_db_query' => array(
            '5.3' => false,
            '7.0' => true,
            'alternative' => 'mysqli::select_db() and mysqli::query()',
        ),
        'mysql_escape_string' => array(
            '5.3' => false,
            '7.0' => true,
            'alternative' => 'mysqli::real_escape_string()',
        ),
        'mysql_list_dbs' => array(
            '5.4' => false,
            '7.0' => true,
            'alternative' => null,
        ),
        'mysqli_bind_param' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => 'mysqli_stmt::bind_param()',
        ),
        'mysqli_bind_result' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => 'mysqli_stmt::bind_result()',
        ),
        'mysqli_client_encoding' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => 'mysqli::character_set_name()',
        ),
        'mysqli_fetch' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => 'mysqli_stmt::fetch()',
        ),
        'mysqli_param_count' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => 'mysqli_stmt_param_count()',
        ),
        'mysqli_get_metadata' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => 'mysqli_stmt::result_metadata()',
        ),
        'mysqli_send_long_data' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => 'mysqli_stmt::send_long_data()',
        ),
        'magic_quotes_runtime' => array(
            '5.3' => false,
            '7.0' => true,
            'alternative' => null,
        ),
        'session_register' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => '$_SESSION',
        ),
        'session_unregister' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => '$_SESSION',
        ),
        'session_is_registered' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => '$_SESSION',
        ),
        'set_magic_quotes_runtime' => array(
            '5.3' => false,
            '7.0' => true,
            'alternative' => null,
        ),
        'set_socket_blocking' => array(
            '5.3' => false,
            '7.0' => true,
            'alternative' => 'stream_set_blocking()',
        ),
        'split' => array(
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'preg_split(), explode() or str_split()',
            'extension'   => 'ereg',
        ),
        'spliti' => array(
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'preg_split() (with the i modifier)',
            'extension'   => 'ereg',
        ),
        'sql_regcase' => array(
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => null,
            'extension'   => 'ereg',
        ),
        'php_logo_guid' => array(
            '5.5' => true,
            'alternative' => null,
        ),
        'php_egg_logo_guid' => array(
            '5.5' => true,
            'alternative' => null,
        ),
        'php_real_logo_guid' => array(
            '5.5' => true,
            'alternative' => null,
        ),
        'zend_logo_guid' => array(
            '5.5' => true,
            'alternative' => 'text string "PHPE9568F35-D428-11d2-A769-00AA001ACF42"',
        ),
        'datefmt_set_timezone_id' => array(
            '5.5' => false,
            '7.0' => true,
            'alternative' => 'IntlDateFormatter::setTimeZone()',
        ),
        'mcrypt_ecb' => array(
            '5.5'         => false,
            '7.0'         => true,
            'alternative' => 'mcrypt_decrypt()/mcrypt_encrypt()',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_cbc' => array(
            '5.5'         => false,
            '7.0'         => true,
            'alternative' => 'mcrypt_decrypt()/mcrypt_encrypt()',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_cfb' => array(
            '5.5'         => false,
            '7.0'         => true,
            'alternative' => 'mcrypt_decrypt()/mcrypt_encrypt()',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_ofb' => array(
            '5.5'         => false,
            '7.0'         => true,
            'alternative' => 'mcrypt_decrypt()/mcrypt_encrypt()',
            'extension'   => 'mcrypt',
        ),
        'ocibindbyname' => array(
            '5.4' => false,
            'alternative' => 'oci_bind_by_name()',
        ),
        'ocicancel' => array(
            '5.4' => false,
            'alternative' => 'oci_cancel()',
        ),
        'ocicloselob' => array(
            '5.4' => false,
            'alternative' => 'OCI-Lob::close()',
        ),
        'ocicollappend' => array(
            '5.4' => false,
            'alternative' => 'OCI-Collection::append()',
        ),
        'ocicollassign' => array(
            '5.4' => false,
            'alternative' => 'OCI-Collection::assign()',
        ),
        'ocicollassignelem' => array(
            '5.4' => false,
            'alternative' => 'OCI-Collection::assignElem()',
        ),
        'ocicollgetelem' => array(
            '5.4' => false,
            'alternative' => 'OCI-Collection::getElem()',
        ),
        'ocicollmax' => array(
            '5.4' => false,
            'alternative' => 'OCI-Collection::max()',
        ),
        'ocicollsize' => array(
            '5.4' => false,
            'alternative' => 'OCI-Collection::size()',
        ),
        'ocicolltrim' => array(
            '5.4' => false,
            'alternative' => 'OCI-Collection::trim()',
        ),
        'ocicolumnisnull' => array(
            '5.4' => false,
            'alternative' => 'oci_field_is_null()',
        ),
        'ocicolumnname' => array(
            '5.4' => false,
            'alternative' => 'oci_field_name()',
        ),
        'ocicolumnprecision' => array(
            '5.4' => false,
            'alternative' => 'oci_field_precision()',
        ),
        'ocicolumnscale' => array(
            '5.4' => false,
            'alternative' => 'oci_field_scale()',
        ),
        'ocicolumnsize' => array(
            '5.4' => false,
            'alternative' => 'oci_field_size()',
        ),
        'ocicolumntype' => array(
            '5.4' => false,
            'alternative' => 'oci_field_type()',
        ),
        'ocicolumntyperaw' => array(
            '5.4' => false,
            'alternative' => 'oci_field_type_raw()',
        ),
        'ocicommit' => array(
            '5.4' => false,
            'alternative' => 'oci_commit()',
        ),
        'ocidefinebyname' => array(
            '5.4' => false,
            'alternative' => 'oci_define_by_name()',
        ),
        'ocierror' => array(
            '5.4' => false,
            'alternative' => 'oci_error()',
        ),
        'ociexecute' => array(
            '5.4' => false,
            'alternative' => 'oci_execute()',
        ),
        'ocifetch' => array(
            '5.4' => false,
            'alternative' => 'oci_fetch()',
        ),
        'ocifetchinto' => array(
            '5.4' => false,
            'alternative' => null,
        ),
        'ocifetchstatement' => array(
            '5.4' => false,
            'alternative' => 'oci_fetch_all()',
        ),
        'ocifreecollection' => array(
            '5.4' => false,
            'alternative' => 'OCI-Collection::free()',
        ),
        'ocifreecursor' => array(
            '5.4' => false,
            'alternative' => 'oci_free_statement()',
        ),
        'ocifreedesc' => array(
            '5.4' => false,
            'alternative' => 'OCI-Lob::free()',
        ),
        'ocifreestatement' => array(
            '5.4' => false,
            'alternative' => 'oci_free_statement()',
        ),
        'ociinternaldebug' => array(
            '5.4' => false,
            'alternative' => 'oci_internal_debug()',
        ),
        'ociloadlob' => array(
            '5.4' => false,
            'alternative' => 'OCI-Lob::load()',
        ),
        'ocilogoff' => array(
            '5.4' => false,
            'alternative' => 'oci_close()',
        ),
        'ocilogon' => array(
            '5.4' => false,
            'alternative' => 'oci_connect()',
        ),
        'ocinewcollection' => array(
            '5.4' => false,
            'alternative' => 'oci_new_collection()',
        ),
        'ocinewcursor' => array(
            '5.4' => false,
            'alternative' => 'oci_new_cursor()',
        ),
        'ocinewdescriptor' => array(
            '5.4' => false,
            'alternative' => 'oci_new_descriptor()',
        ),
        'ocinlogon' => array(
            '5.4' => false,
            'alternative' => 'oci_new_connect()',
        ),
        'ocinumcols' => array(
            '5.4' => false,
            'alternative' => 'oci_num_fields()',
        ),
        'ociparse' => array(
            '5.4' => false,
            'alternative' => 'oci_parse()',
        ),
        'ociplogon' => array(
            '5.4' => false,
            'alternative' => 'oci_pconnect()',
        ),
        'ociresult' => array(
            '5.4' => false,
            'alternative' => 'oci_result()',
        ),
        'ocirollback' => array(
            '5.4' => false,
            'alternative' => 'oci_rollback()',
        ),
        'ocirowcount' => array(
            '5.4' => false,
            'alternative' => 'oci_num_rows()',
        ),
        'ocisavelob' => array(
            '5.4' => false,
            'alternative' => 'OCI-Lob::save()',
        ),
        'ocisavelobfile' => array(
            '5.4' => false,
            'alternative' => 'OCI-Lob::import()',
        ),
        'ociserverversion' => array(
            '5.4' => false,
            'alternative' => 'oci_server_version()',
        ),
        'ocisetprefetch' => array(
            '5.4' => false,
            'alternative' => 'oci_set_prefetch()',
        ),
        'ocistatementtype' => array(
            '5.4' => false,
            'alternative' => 'oci_statement_type()',
        ),
        'ociwritelobtofile' => array(
            '5.4' => false,
            'alternative' => 'OCI-Lob::export()',
        ),
        'ociwritetemporarylob' => array(
            '5.4' => false,
            'alternative' => 'OCI-Lob::writeTemporary()',
        ),
        'mysqli_get_cache_stats' => array(
            '5.4' => true,
            'alternative' => null,
        ),

        'mcrypt_create_iv' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'random_bytes() or OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_decrypt' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_enc_get_algorithms_name' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_enc_get_block_size' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_enc_get_iv_size' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_enc_get_key_size' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_enc_get_modes_name' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_enc_get_supported_key_sizes' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_enc_is_block_algorithm_mode' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_enc_is_block_algorithm' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_enc_is_block_mode' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_enc_self_test' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_encrypt' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_generic_deinit' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_generic_init' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_generic' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_get_block_size' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_get_cipher_name' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_get_iv_size' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_get_key_size' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_list_algorithms' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_list_modes' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_module_close' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_module_get_algo_block_size' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_module_get_algo_key_size' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_module_get_supported_key_sizes' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_module_is_block_algorithm_mode' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_module_is_block_algorithm' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_module_is_block_mode' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_module_open' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mcrypt_module_self_test' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'mdecrypt_generic' => array(
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ),
        'jpeg2wbmp' => array(
            '7.2' => false,
            'alternative' => 'imagecreatefromjpeg() and imagewbmp()',
        ),
        'png2wbmp' => array(
            '7.2' => false,
            'alternative' => 'imagecreatefrompng() or imagewbmp()',
        ),
        '__autoload' => array(
            '7.2' => false,
            'alternative' => 'SPL autoload',
        ),
        'create_function' => array(
            '7.2' => false,
            'alternative' => 'an anonymous function',
        ),
        'each' => array(
            '7.2' => false,
            'alternative' => 'a foreach loop',
        ),
        'gmp_random' => array(
            '7.2' => false,
            'alternative' => 'gmp_random_bits() or gmp_random_range()',
        ),
        'read_exif_data' => array(
            '7.2' => false,
            'alternative' => 'exif_read_data()',
        ),

        'image2wbmp' => array(
            '7.3' => false,
            'alternative' => 'imagewbmp()',
        ),
        'mbregex_encoding' => array(
            '7.3' => false,
            'alternative' => 'mb_regex_encoding()',
        ),
        'mbereg' => array(
            '7.3' => false,
            'alternative' => 'mb_ereg()',
        ),
        'mberegi' => array(
            '7.3' => false,
            'alternative' => 'mb_eregi()',
        ),
        'mbereg_replace' => array(
            '7.3' => false,
            'alternative' => 'mb_ereg_replace()',
        ),
        'mberegi_replace' => array(
            '7.3' => false,
            'alternative' => 'mb_eregi_replace()',
        ),
        'mbsplit' => array(
            '7.3' => false,
            'alternative' => 'mb_split()',
        ),
        'mbereg_match' => array(
            '7.3' => false,
            'alternative' => 'mb_ereg_match()',
        ),
        'mbereg_search' => array(
            '7.3' => false,
            'alternative' => 'mb_ereg_search()',
        ),
        'mbereg_search_pos' => array(
            '7.3' => false,
            'alternative' => 'mb_ereg_search_pos()',
        ),
        'mbereg_search_regs' => array(
            '7.3' => false,
            'alternative' => 'mb_ereg_search_regs()',
        ),
        'mbereg_search_init' => array(
            '7.3' => false,
            'alternative' => 'mb_ereg_search_init()',
        ),
        'mbereg_search_getregs' => array(
            '7.3' => false,
            'alternative' => 'mb_ereg_search_getregs()',
        ),
        'mbereg_search_getpos' => array(
            '7.3' => false,
            'alternative' => 'mb_ereg_search_getpos()',
        ),
        'mbereg_search_setpos' => array(
            '7.3' => false,
            'alternative' => 'mb_ereg_search_setpos()',
        ),
        'fgetss' => array(
            '7.3' => false,
            'alternative' => null,
        ),
        'gzgetss' => array(
            '7.3' => false,
            'alternative' => null,
        ),

        'convert_cyr_string' => array(
            '7.4' => false,
            'alternative' => 'mb_convert_encoding(), iconv() or UConverter',
        ),
        'ezmlm_hash' => array(
            '7.4' => false,
            'alternative' => null,
        ),
        'get_magic_quotes_gpc' => array(
            '7.4' => false,
            'alternative' => null,
        ),
        'get_magic_quotes_runtime' => array(
            '7.4' => false,
            'alternative' => null,
        ),
        'hebrevc' => array(
            '7.4' => false,
            'alternative' => null,
        ),
        'is_real' => array(
            '7.4' => false,
            'alternative' => 'is_float()',
        ),
        'money_format' => array(
            '7.4' => false,
            'alternative' => 'NumberFormatter::formatCurrency()',
        ),
        'restore_include_path' => array(
            '7.4' => false,
            'alternative' => "ini_restore('include_path')",
        ),
        'ibase_add_user' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_affected_rows' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_backup' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_blob_add' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_blob_cancel' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_blob_close' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_blob_create' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_blob_echo' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_blob_get' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_blob_import' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_blob_info' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_blob_open' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_close' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_commit_ret' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_commit' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_connect' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_db_info' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_delete_user' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_drop_db' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_errcode' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_errmsg' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_execute' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_fetch_assoc' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_fetch_object' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_fetch_row' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_field_info' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_free_event_handler' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_free_query' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_free_result' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_gen_id' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_maintain_db' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_modify_user' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_name_result' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_num_fields' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_num_params' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_param_info' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_pconnect' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_prepare' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_query' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_restore' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_rollback_ret' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_rollback' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_server_info' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_service_attach' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_service_detach' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_set_event_handler' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_trans' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ibase_wait_event' => array(
            '7.4' => true,
            'alternative' => null,
        ),
        'ldap_control_paged_result_response' => array(
            '7.4' => false,
            'alternative' => 'ldap_search()',
        ),
        'ldap_control_paged_result' => array(
            '7.4' => false,
            'alternative' => 'ldap_search()',
        ),
        'recode_file' => array(
            '7.4'         => true,
            'alternative' => 'the iconv or mbstring extension',
            'extension'   => 'recode',
        ),
        'recode_string' => array(
            '7.4'         => true,
            'alternative' => 'the iconv or mbstring extension',
            'extension'   => 'recode',
        ),
        'recode' => array(
            '7.4'         => true,
            'alternative' => 'the iconv or mbstring extension',
            'extension'   => 'recode',
        ),
        'wddx_add_vars' => array(
            '7.4'         => true,
            'alternative' => null,
            'extension'   => 'wddx',
        ),
        'wddx_deserialize' => array(
            '7.4'         => true,
            'alternative' => null,
            'extension'   => 'wddx',
        ),
        'wddx_packet_end' => array(
            '7.4'         => true,
            'alternative' => null,
            'extension'   => 'wddx',
        ),
        'wddx_packet_start' => array(
            '7.4'         => true,
            'alternative' => null,
            'extension'   => 'wddx',
        ),
        'wddx_serialize_value' => array(
            '7.4'         => true,
            'alternative' => null,
            'extension'   => 'wddx',
        ),
        'wddx_serialize_vars' => array(
            '7.4'         => true,
            'alternative' => null,
            'extension'   => 'wddx',
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
        $this->removedFunctions = \array_change_key_case($this->removedFunctions, \CASE_LOWER);

        return array(\T_STRING);
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $function   = $tokens[$stackPtr]['content'];
        $functionLc = strtolower($function);

        if (isset($this->removedFunctions[$functionLc]) === false) {
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
        return $this->removedFunctions[$itemInfo['nameLc']];
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
        return 'Function %s() is ';
    }
}
