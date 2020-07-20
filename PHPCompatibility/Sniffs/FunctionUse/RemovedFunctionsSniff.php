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
            '5.1'       => true,
            'extension' => 'pfpro',
        ),
        'pfpro_init' => array(
            '5.1'       => true,
            'extension' => 'pfpro',
        ),
        'pfpro_process_raw' => array(
            '5.1'       => true,
            'extension' => 'pfpro',
        ),
        'pfpro_process' => array(
            '5.1'       => true,
            'extension' => 'pfpro',
        ),
        'pfpro_version' => array(
            '5.1'       => true,
            'extension' => 'pfpro',
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
        'cpdf_add_annotation' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_add_outline' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_arc' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_begin_text' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_circle' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_clip' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_close' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_closepath_fill_stroke' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_closepath_stroke' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_closepath' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_continue_text' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_curveto' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_end_text' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_fill_stroke' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_fill' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_finalize_page' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_finalize' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_global_set_document_limits' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_import_jpeg' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_lineto' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_moveto' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_newpath' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_open' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_output_buffer' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_page_init' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_place_inline_image' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_rect' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_restore' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_rlineto' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_rmoveto' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_rotate_text' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_rotate' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_save_to_file' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_save' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_scale' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_action_url' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_char_spacing' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_creator' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_current_page' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_font_directories' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_font_map_file' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_font' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_horiz_scaling' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_keywords' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_leading' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_page_animation' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_subject' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_text_matrix' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_text_pos' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_text_rendering' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_text_rise' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_title' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_viewer_preferences' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_set_word_spacing' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_setdash' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_setflat' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_setgray_fill' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_setgray_stroke' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_setgray' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_setlinecap' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_setlinejoin' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_setlinewidth' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_setmiterlimit' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_setrgbcolor_fill' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_setrgbcolor_stroke' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_setrgbcolor' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_show_xy' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_show' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_stringwidth' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_stroke' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_text' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'cpdf_translate' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'ircg_channel_mode' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_disconnect' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_eval_ecmascript_params' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_fetch_error_msg' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_get_username' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_html_encode' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_ignore_add' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_ignore_del' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_invite' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_is_conn_alive' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_join' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_kick' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_list' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_lookup_format_messages' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_lusers' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_msg' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_names' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_nick' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_nickname_escape' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_nickname_unescape' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_notice' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_oper' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_part' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_pconnect' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_register_format_messages' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_set_current' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_set_file' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_set_on_die' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_topic' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_who' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'ircg_whois' => array(
            '5.1'       => true,
            'extension' => 'ircg',
        ),
        'dbx_close' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'dbx_compare' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'dbx_connect' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'dbx_error' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'dbx_escape_string' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'dbx_fetch_row' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'dbx_query' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'dbx_sort' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'ingres_autocommit' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_close' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_commit' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_connect' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_fetch_array' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_fetch_object' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_fetch_row' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_field_length' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_field_name' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_field_nullable' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_field_precision' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_field_scale' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_field_type' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_num_fields' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_num_rows' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_pconnect' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_query' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres_rollback' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ovrimos_close' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_commit' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_connect' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_cursor' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_exec' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_execute' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_fetch_into' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_fetch_row' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_field_len' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_field_name' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_field_num' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_field_type' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_free_result' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_longreadlen' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_num_fields' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_num_rows' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_prepare' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_result_all' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_result' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_rollback' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ovrimos_close_all' => array(
            '5.1'       => true,
            'extension' => 'ovrimos',
        ),
        'ora_bind' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_close' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_columnname' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_columnsize' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_columntype' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_commit' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_commitoff' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_commiton' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_do' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_error' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_errorcode' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_exec' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_fetch_into' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_fetch' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_getcolumn' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_logoff' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_logon' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_numcols' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_numrows' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_open' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_parse' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_plogon' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ora_rollback' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'mysqli_embedded_connect' => array(
            '5.1' => true,
        ),
        'mysqli_server_end' => array(
            '5.1' => true,
        ),
        'mysqli_server_init' => array(
            '5.1' => true,
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

        'mysqli_resource' => array(
            '5.1.4' => true,
        ),

        'mysql_createdb' => array(
            '5.1.7'     => true,
            'extension' => 'mysql',
        ),
        'mysql_dropdb' => array(
            '5.1.7'     => true,
            'extension' => 'mysql',
        ),
        'mysql_listtables' => array(
            '5.1.7'     => true,
            'extension' => 'mysql',
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
        'filepro_fieldcount' => array(
            '5.2'       => true,
            'extension' => 'filepro',
        ),
        'filepro_fieldname' => array(
            '5.2'       => true,
            'extension' => 'filepro',
        ),
        'filepro_fieldtype' => array(
            '5.2'       => true,
            'extension' => 'filepro',
        ),
        'filepro_fieldwidth' => array(
            '5.2'       => true,
            'extension' => 'filepro',
        ),
        'filepro_retrieve' => array(
            '5.2'       => true,
            'extension' => 'filepro',
        ),
        'filepro_rowcount' => array(
            '5.2'       => true,
            'extension' => 'filepro',
        ),
        'filepro' => array(
            '5.2'       => true,
            'extension' => 'filepro',
        ),

        'ifx_affected_rows' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_blobinfile_mode' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_byteasvarchar' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_close' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_connect' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_copy_blob' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_create_blob' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_create_char' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_do' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_error' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_errormsg' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_fetch_row' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_fieldproperties' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_fieldtypes' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_free_blob' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_free_char' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_free_result' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_get_blob' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_get_char' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_getsqlca' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_htmltbl_result' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_nullformat' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_num_fields' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_num_rows' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_pconnect' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_prepare' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_query' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_textasvarchar' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_update_blob' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifx_update_char' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifxus_close_slob' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifxus_create_slob' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifxus_free_slob' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifxus_open_slob' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifxus_read_slob' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifxus_seek_slob' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifxus_tell_slob' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'ifxus_write_slob' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
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
        'dbase_add_record' => array(
            '5.3'       => true,
            'extension' => 'dbase',
        ),
        'dbase_close' => array(
            '5.3'       => true,
            'extension' => 'dbase',
        ),
        'dbase_create' => array(
            '5.3'       => true,
            'extension' => 'dbase',
        ),
        'dbase_delete_record' => array(
            '5.3'       => true,
            'extension' => 'dbase',
        ),
        'dbase_get_header_info' => array(
            '5.3'       => true,
            'extension' => 'dbase',
        ),
        'dbase_get_record_with_names' => array(
            '5.3'       => true,
            'extension' => 'dbase',
        ),
        'dbase_get_record' => array(
            '5.3'       => true,
            'extension' => 'dbase',
        ),
        'dbase_numfields' => array(
            '5.3'       => true,
            'extension' => 'dbase',
        ),
        'dbase_numrecords' => array(
            '5.3'       => true,
            'extension' => 'dbase',
        ),
        'dbase_open' => array(
            '5.3'       => true,
            'extension' => 'dbase',
        ),
        'dbase_pack' => array(
            '5.3'       => true,
            'extension' => 'dbase',
        ),
        'dbase_replace_record' => array(
            '5.3'       => true,
            'extension' => 'dbase',
        ),
        'fbsql_affected_rows' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_autocommit' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_blob_size' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_change_user' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_clob_size' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_close' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_commit' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_connect' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_create_blob' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_create_clob' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_create_db' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_data_seek' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_database_password' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_database' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_db_query' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_db_status' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_drop_db' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_errno' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_error' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_fetch_array' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_fetch_assoc' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_fetch_field' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_fetch_lengths' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_fetch_object' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_fetch_row' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_field_flags' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_field_len' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_field_name' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_field_seek' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_field_table' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_field_type' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_free_result' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_get_autostart_info' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_hostname' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_insert_id' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_list_dbs' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_list_fields' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_list_tables' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_next_result' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_num_fields' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_num_rows' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_password' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_pconnect' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_query' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_read_blob' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_read_clob' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_result' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_rollback' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_rows_fetched' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_select_db' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_set_characterset' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_set_lob_mode' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_set_password' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_set_transaction' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_start_db' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_stop_db' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_table_name' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_tablename' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_username' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql_warnings' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'msql_affected_rows' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_close' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_connect' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_create_db' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_createdb' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_data_seek' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_db_query' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_dbname' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_drop_db' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_error' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_fetch_array' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_fetch_field' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_fetch_object' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_fetch_row' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_field_flags' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_field_len' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_field_name' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_field_seek' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_field_table' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_field_type' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_fieldflags' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_fieldlen' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_fieldname' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_fieldtable' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_fieldtype' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_free_result' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_list_dbs' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_list_fields' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_list_tables' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_num_fields' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_num_rows' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_numfields' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_numrows' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_pconnect' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_query' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_regcase' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_result' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_select_db' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql_tablename' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'mysqli_disable_reads_from_master' => array(
            '5.3' => true,
        ),
        'mysqli_disable_rpl_parse' => array(
            '5.3' => true,
        ),
        'mysqli_enable_reads_from_master' => array(
            '5.3' => true,
        ),
        'mysqli_enable_rpl_parse' => array(
            '5.3' => true,
        ),
        'mysqli_master_query' => array(
            '5.3' => true,
        ),
        'mysqli_rpl_parse_enabled' => array(
            '5.3' => true,
        ),
        'mysqli_rpl_probe' => array(
            '5.3' => true,
        ),
        'mysqli_slave_query' => array(
            '5.3' => true,
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
            '8.0' => true,
            'alternative' => null,
        ),
        'mcrypt_generic_end' => array(
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'mcrypt_generic_deinit()',
            'extension'   => 'mcrypt',
        ),
        'mysql_db_query' => array(
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'mysqli::select_db() and mysqli::query()',
            'extension'   => 'mysql',
        ),
        'mysql_escape_string' => array(
            '4.3'         => false,
            '7.0'         => true,
            'alternative' => 'mysqli::real_escape_string()',
            'extension'   => 'mysql',
        ),
        'mysql_list_dbs' => array(
            '5.4'         => false,
            '7.0'         => true,
            'alternative' => null,
            'extension'   => 'mysql',
        ),
        'mysql_list_fields' => array(
            '5.4'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
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
        'sqlite_array_query' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_busy_timeout' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_changes' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_close' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_column' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_create_aggregate' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_create_function' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_current' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_error_string' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_escape_string' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_exec' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_factory' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_fetch_all' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_fetch_array' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_fetch_column_types' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_fetch_object' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_fetch_single' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_fetch_string' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_field_name' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_has_more' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_has_prev' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_key' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_last_error' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_last_insert_rowid' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_libencoding' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_libversion' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_next' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_num_fields' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_num_rows' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_open' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_popen' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_prev' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_query' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_rewind' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_seek' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_single_query' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_udf_decode_binary' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_udf_encode_binary' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_unbuffered_query' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'sqlite_valid' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),

        'mssql_bind' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_close' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_connect' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_data_seek' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_execute' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_fetch_array' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_fetch_assoc' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_fetch_batch' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_fetch_field' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_fetch_object' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_fetch_row' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_field_length' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_field_name' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_field_seek' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_field_type' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_free_result' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_free_statement' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_get_last_message' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_guid_string' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_init' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_min_error_severity' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_min_message_severity' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_next_result' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_num_fields' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_num_rows' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_pconnect' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_query' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_result' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_rows_affected' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql_select_db' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mysql_affected_rows' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_client_encoding' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_close' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_connect' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_create_db' => array(
            '4.3'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_data_seek' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_db_name' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_drop_db' => array(
            '4.3'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_errno' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_error' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_fetch_array' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_fetch_assoc' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_fetch_field' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_fetch_lengths' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_fetch_object' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_fetch_row' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_field_flags' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_field_len' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_field_name' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_field_seek' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_field_table' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_field_type' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_free_result' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_get_client_info' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_get_host_info' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_get_proto_info' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_get_server_info' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_info' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_insert_id' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_list_processes' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_list_tables' => array(
            '4.3.7'     => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_num_fields' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_num_rows' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_pconnect' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_ping' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_query' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_real_escape_string' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_result' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_select_db' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_set_charset' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_stat' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_tablename' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_thread_id' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_unbuffered_query' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_fieldname' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_fieldtable' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_fieldlen' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_fieldtype' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_fieldflags' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_selectdb' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_freeresult' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_numfields' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_numrows' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_listdbs' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_listfields' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_dbname' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql_table_name' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'sybase_affected_rows' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_close' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_connect' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_data_seek' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_deadlock_retry_count' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_fetch_array' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_fetch_assoc' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_fetch_field' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_fetch_object' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_fetch_row' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_field_seek' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_free_result' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_get_last_message' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_min_client_severity' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_min_error_severity' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_min_message_severity' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_min_server_severity' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_num_fields' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_num_rows' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_pconnect' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_query' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_result' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_select_db' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_set_message_handler' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase_unbuffered_query' => array(
            '7.0'       => true,
            'extension' => 'sybase',
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
            '8.0' => true,
            'alternative' => 'imagecreatefromjpeg() and imagewbmp()',
        ),
        'png2wbmp' => array(
            '7.2' => false,
            '8.0' => true,
            'alternative' => 'imagecreatefrompng() or imagewbmp()',
        ),
        '__autoload' => array(
            '7.2' => false,
            'alternative' => 'SPL autoload',
        ),
        'create_function' => array(
            '7.2' => false,
            '8.0' => true,
            'alternative' => 'an anonymous function',
        ),
        'each' => array(
            '7.2' => false,
            '8.0' => true,
            'alternative' => 'a foreach loop or ArrayIterator',
        ),
        'gmp_random' => array(
            '7.2' => false,
            '8.0' => true,
            'alternative' => 'gmp_random_bits() or gmp_random_range()',
        ),
        'read_exif_data' => array(
            '7.2' => false,
            '8.0' => true,
            'alternative' => 'exif_read_data()',
        ),

        'image2wbmp' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => 'imagewbmp()',
        ),
        'mbregex_encoding' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => 'mb_regex_encoding()',
        ),
        'mbereg' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => 'mb_ereg()',
        ),
        'mberegi' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => 'mb_eregi()',
        ),
        'mbereg_replace' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => 'mb_ereg_replace()',
        ),
        'mberegi_replace' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => 'mb_eregi_replace()',
        ),
        'mbsplit' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => 'mb_split()',
        ),
        'mbereg_match' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => 'mb_ereg_match()',
        ),
        'mbereg_search' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => 'mb_ereg_search()',
        ),
        'mbereg_search_pos' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => 'mb_ereg_search_pos()',
        ),
        'mbereg_search_regs' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => 'mb_ereg_search_regs()',
        ),
        'mbereg_search_init' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => 'mb_ereg_search_init()',
        ),
        'mbereg_search_getregs' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => 'mb_ereg_search_getregs()',
        ),
        'mbereg_search_getpos' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => 'mb_ereg_search_getpos()',
        ),
        'mbereg_search_setpos' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => 'mb_ereg_search_setpos()',
        ),
        'fgetss' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => null,
        ),
        'gzgetss' => array(
            '7.3' => false,
            '8.0' => true,
            'alternative' => null,
        ),

        'convert_cyr_string' => array(
            '7.4' => false,
            '8.0' => true,
            'alternative' => 'mb_convert_encoding(), iconv() or UConverter',
        ),
        'ezmlm_hash' => array(
            '7.4' => false,
            '8.0' => true,
            'alternative' => null,
        ),
        'get_magic_quotes_gpc' => array(
            '7.4' => false,
            '8.0' => true,
            'alternative' => null,
        ),
        'get_magic_quotes_runtime' => array(
            '7.4' => false,
            '8.0' => true,
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
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_affected_rows' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_backup' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_blob_add' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_blob_cancel' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_blob_close' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_blob_create' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_blob_echo' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_blob_get' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_blob_import' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_blob_info' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_blob_open' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_close' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_commit_ret' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_commit' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_connect' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_db_info' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_delete_user' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_drop_db' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_errcode' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_errmsg' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_execute' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_fetch_assoc' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_fetch_object' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_fetch_row' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_field_info' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_free_event_handler' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_free_query' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_free_result' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_gen_id' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_maintain_db' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_modify_user' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_name_result' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_num_fields' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_num_params' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_param_info' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_pconnect' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_prepare' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_query' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_restore' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_rollback_ret' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_rollback' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_server_info' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_service_attach' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_service_detach' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_set_event_handler' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_trans' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase_wait_event' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_add_user' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_affected_rows' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_backup' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_blob_add' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_blob_cancel' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_blob_close' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_blob_create' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_blob_echo' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_blob_get' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_blob_import' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_blob_info' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_blob_open' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_close' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_commit_ret' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_commit' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_connect' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_db_info' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_delete_user' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_drop_db' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_errcode' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_errmsg' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_execute' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_fetch_assoc' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_fetch_object' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_fetch_row' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_field_info' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_free_event_handler' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_free_query' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_free_result' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_gen_id' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_maintain_db' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_modify_user' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_name_result' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_num_fields' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_num_params' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_param_info' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_pconnect' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_prepare' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_query' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_restore' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_rollback_ret' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_rollback' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_server_info' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_service_attach' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_service_detach' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_set_event_handler' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_trans' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'fbird_wait_event' => array(
            '7.4'       => true,
            'extension' => 'ibase',
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
        'mysqli_embedded_server_end' => array(
            '7.4' => true,
        ),
        'mysqli_embedded_server_start' => array(
            '7.4' => true,
        ),

        'xmlrpc_decode_request' => array(
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ),
        'xmlrpc_decode' => array(
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ),
        'xmlrpc_encode_request' => array(
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ),
        'xmlrpc_encode' => array(
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ),
        'xmlrpc_get_type' => array(
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ),
        'xmlrpc_is_fault' => array(
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ),
        'xmlrpc_parse_method_descriptions' => array(
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ),
        'xmlrpc_server_add_introspection_data' => array(
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ),
        'xmlrpc_server_call_method' => array(
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ),
        'xmlrpc_server_create' => array(
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ),
        'xmlrpc_server_destroy' => array(
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ),
        'xmlrpc_server_register_introspection_callback' => array(
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ),
        'xmlrpc_server_register_method' => array(
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ),
        'xmlrpc_set_type' => array(
            '8.0'       => true,
            'extension' => 'xmlrpc',
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
