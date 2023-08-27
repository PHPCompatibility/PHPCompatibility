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

use PHPCompatibility\Helpers\ComplexVersionDeprecatedRemovedFeatureTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\MessageHelper;

/**
 * Detect calls to deprecated/removed native PHP functions.
 *
 * Suggests alternative if available.
 *
 * PHP version All
 *
 * @since 5.5
 * @since 5.6    Now extends the base `Sniff` class instead of the upstream
 *               `Generic.PHP.ForbiddenFunctions` sniff.
 * @since 7.1.0  Now extends the `AbstractRemovedFeatureSniff` instead of the base `Sniff` class.
 * @since 9.0.0  Renamed from `DeprecatedFunctionsSniff` to `RemovedFunctionsSniff`.
 * @since 10.0.0 Now extends the base `Sniff` class and uses the `ComplexVersionDeprecatedRemovedFeatureTrait`.
 */
class RemovedFunctionsSniff extends Sniff
{
    use ComplexVersionDeprecatedRemovedFeatureTrait;

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
     * @var array<string, array<string, bool|string|null>>
     */
    protected $removedFunctions = [
        'crack_check' => [
            '5.0'       => true,
            'extension' => 'crack',
        ],
        'crack_closedict' => [
            '5.0'       => true,
            'extension' => 'crack',
        ],
        'crack_getlastmessage' => [
            '5.0'       => true,
            'extension' => 'crack',
        ],
        'crack_opendict' => [
            '5.0'       => true,
            'extension' => 'crack',
        ],

        'php_check_syntax' => [
            '5.0.5' => true,
        ],

        'pfpro_cleanup' => [
            '5.1'       => true,
            'extension' => 'pfpro',
        ],
        'pfpro_init' => [
            '5.1'       => true,
            'extension' => 'pfpro',
        ],
        'pfpro_process_raw' => [
            '5.1'       => true,
            'extension' => 'pfpro',
        ],
        'pfpro_process' => [
            '5.1'       => true,
            'extension' => 'pfpro',
        ],
        'pfpro_version' => [
            '5.1'       => true,
            'extension' => 'pfpro',
        ],
        'm_checkstatus' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_completeauthorizations' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_connect' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_connectionerror' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_deletetrans' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_destroyconn' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_destroyengine' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_getcell' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_getcellbynum' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_getcommadelimited' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_getheader' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_initconn' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_initengine' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_iscommadelimited' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_maxconntimeout' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_monitor' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_numcolumns' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_numrows' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_parsecommadelimited' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_responsekeys' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_responseparam' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_returnstatus' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_setblocking' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_setdropfile' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_setip' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_setssl_cafile' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_setssl_files' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_setssl' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_settimeout' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_sslcert_gen_hash' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_transactionssent' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_transinqueue' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_transkeyval' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_transnew' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_transsend' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_uwait' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_validateidentifier' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_verifyconnection' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'm_verifysslcert' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'dio_close' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'dio_fcntl' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'dio_open' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'dio_read' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'dio_seek' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'dio_stat' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'dio_tcsetattr' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'dio_truncate' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'dio_write' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'fam_cancel_monitor' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'fam_close' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'fam_monitor_collection' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'fam_monitor_directory' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'fam_monitor_file' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'fam_next_event' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'fam_open' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'fam_pending' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'fam_resume_monitor' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'fam_suspend_monitor' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'yp_all' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'yp_cat' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'yp_err_string' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'yp_errno' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'yp_first' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'yp_get_default_domain' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'yp_master' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'yp_match' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'yp_next' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'yp_order' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'udm_add_search_limit' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_alloc_agent_array' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_alloc_agent' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_api_version' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_cat_list' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_cat_path' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_check_charset' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_clear_search_limits' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_crc32' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_errno' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_error' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_find' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_free_agent' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_free_ispell_data' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_free_res' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_get_doc_count' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_get_res_field' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_get_res_param' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_hash32' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_load_ispell_data' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'udm_set_agent_param' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'w32api_deftype' => [
            '5.1'       => true,
            'extension' => 'w32api',
        ],
        'w32api_init_dtype' => [
            '5.1'       => true,
            'extension' => 'w32api',
        ],
        'w32api_invoke_function' => [
            '5.1'       => true,
            'extension' => 'w32api',
        ],
        'w32api_register_function' => [
            '5.1'       => true,
            'extension' => 'w32api',
        ],
        'w32api_set_call_method' => [
            '5.1'       => true,
            'extension' => 'w32api',
        ],
        'cpdf_add_annotation' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_add_outline' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_arc' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_begin_text' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_circle' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_clip' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_close' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_closepath_fill_stroke' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_closepath_stroke' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_closepath' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_continue_text' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_curveto' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_end_text' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_fill_stroke' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_fill' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_finalize_page' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_finalize' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_global_set_document_limits' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_import_jpeg' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_lineto' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_moveto' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_newpath' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_open' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_output_buffer' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_page_init' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_place_inline_image' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_rect' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_restore' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_rlineto' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_rmoveto' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_rotate_text' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_rotate' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_save_to_file' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_save' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_scale' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_action_url' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_char_spacing' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_creator' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_current_page' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_font_directories' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_font_map_file' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_font' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_horiz_scaling' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_keywords' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_leading' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_page_animation' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_subject' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_text_matrix' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_text_pos' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_text_rendering' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_text_rise' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_title' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_viewer_preferences' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_set_word_spacing' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_setdash' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_setflat' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_setgray_fill' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_setgray_stroke' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_setgray' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_setlinecap' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_setlinejoin' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_setlinewidth' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_setmiterlimit' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_setrgbcolor_fill' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_setrgbcolor_stroke' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_setrgbcolor' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_show_xy' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_show' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_stringwidth' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_stroke' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_text' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'cpdf_translate' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'ircg_channel_mode' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_disconnect' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_eval_ecmascript_params' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_fetch_error_msg' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_get_username' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_html_encode' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_ignore_add' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_ignore_del' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_invite' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_is_conn_alive' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_join' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_kick' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_list' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_lookup_format_messages' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_lusers' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_msg' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_names' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_nick' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_nickname_escape' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_nickname_unescape' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_notice' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_oper' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_part' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_pconnect' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_register_format_messages' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_set_current' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_set_file' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_set_on_die' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_topic' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_who' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'ircg_whois' => [
            '5.1'       => true,
            'extension' => 'ircg',
        ],
        'dbx_close' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'dbx_compare' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'dbx_connect' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'dbx_error' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'dbx_escape_string' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'dbx_fetch_row' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'dbx_query' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'dbx_sort' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'ingres_autocommit' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_close' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_commit' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_connect' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_fetch_array' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_fetch_object' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_fetch_row' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_field_length' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_field_name' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_field_nullable' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_field_precision' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_field_scale' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_field_type' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_num_fields' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_num_rows' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_pconnect' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_query' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres_rollback' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ovrimos_close' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_commit' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_connect' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_cursor' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_exec' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_execute' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_fetch_into' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_fetch_row' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_field_len' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_field_name' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_field_num' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_field_type' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_free_result' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_longreadlen' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_num_fields' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_num_rows' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_prepare' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_result_all' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_result' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_rollback' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ovrimos_close_all' => [
            '5.1'       => true,
            'extension' => 'ovrimos',
        ],
        'ora_bind' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_close' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_columnname' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_columnsize' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_columntype' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_commit' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_commitoff' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_commiton' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_do' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_error' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_errorcode' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_exec' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_fetch_into' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_fetch' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_getcolumn' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_logoff' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_logon' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_numcols' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_numrows' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_open' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_parse' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_plogon' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ora_rollback' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'mysqli_embedded_connect' => [
            '5.1'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_server_end' => [
            '5.1'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_server_init' => [
            '5.1'       => true,
            'extension' => 'mysqli',
        ],

        'msession_connect' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_count' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_create' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_destroy' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_disconnect' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_find' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_get_array' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_get_data' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_get' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_inc' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_list' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_listvar' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_lock' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_plugin' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_randstr' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_set_array' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_set_data' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_set' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_timeout' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_uniq' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],
        'msession_unlock' => [
            '5.1.3'     => true,
            'extension' => 'msession',
        ],

        'mysqli_resource' => [
            '5.1.4'     => true,
            'extension' => 'mysqli',
        ],

        'mysql_createdb' => [
            '5.1.7'     => true,
            'extension' => 'mysql',
        ],
        'mysql_dropdb' => [
            '5.1.7'     => true,
            'extension' => 'mysql',
        ],
        'mysql_listtables' => [
            '5.1.7'     => true,
            'extension' => 'mysql',
        ],

        'hwapi_attribute_new' => [
            '5.2'       => true,
            'extension' => 'hwapi',
        ],
        'hwapi_content_new' => [
            '5.2'       => true,
            'extension' => 'hwapi',
        ],
        'hwapi_hgcsp' => [
            '5.2'       => true,
            'extension' => 'hwapi',
        ],
        'hwapi_object_new' => [
            '5.2'       => true,
            'extension' => 'hwapi',
        ],
        'filepro_fieldcount' => [
            '5.2'       => true,
            'extension' => 'filepro',
        ],
        'filepro_fieldname' => [
            '5.2'       => true,
            'extension' => 'filepro',
        ],
        'filepro_fieldtype' => [
            '5.2'       => true,
            'extension' => 'filepro',
        ],
        'filepro_fieldwidth' => [
            '5.2'       => true,
            'extension' => 'filepro',
        ],
        'filepro_retrieve' => [
            '5.2'       => true,
            'extension' => 'filepro',
        ],
        'filepro_rowcount' => [
            '5.2'       => true,
            'extension' => 'filepro',
        ],
        'filepro' => [
            '5.2'       => true,
            'extension' => 'filepro',
        ],

        'ifx_affected_rows' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_blobinfile_mode' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_byteasvarchar' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_close' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_connect' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_copy_blob' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_create_blob' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_create_char' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_do' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_error' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_errormsg' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_fetch_row' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_fieldproperties' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_fieldtypes' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_free_blob' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_free_char' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_free_result' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_get_blob' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_get_char' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_getsqlca' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_htmltbl_result' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_nullformat' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_num_fields' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_num_rows' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_pconnect' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_prepare' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_query' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_textasvarchar' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_update_blob' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx_update_char' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifxus_close_slob' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifxus_create_slob' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifxus_free_slob' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifxus_open_slob' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifxus_read_slob' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifxus_seek_slob' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifxus_tell_slob' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifxus_write_slob' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],

        'ncurses_addch' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_addchnstr' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_addchstr' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_addnstr' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_addstr' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_assume_default_colors' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_attroff' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_attron' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_attrset' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_baudrate' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_beep' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_bkgd' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_bkgdset' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_border' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_bottom_panel' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_can_change_color' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_cbreak' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_clear' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_clrtobot' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_clrtoeol' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_color_content' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_color_set' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_curs_set' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_def_prog_mode' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_def_shell_mode' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_define_key' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_del_panel' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_delay_output' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_delch' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_deleteln' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_delwin' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_doupdate' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_echo' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_echochar' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_end' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_erase' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_erasechar' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_filter' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_flash' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_flushinp' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_getch' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_getmaxyx' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_getmouse' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_getyx' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_halfdelay' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_has_colors' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_has_ic' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_has_il' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_has_key' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_hide_panel' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_hline' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_inch' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_init_color' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_init_pair' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_init' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_insch' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_insdelln' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_insertln' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_insstr' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_instr' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_isendwin' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_keyok' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_keypad' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_killchar' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_longname' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_meta' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_mouse_trafo' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_mouseinterval' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_mousemask' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_move_panel' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_move' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_mvaddch' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_mvaddchnstr' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_mvaddchstr' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_mvaddnstr' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_mvaddstr' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_mvcur' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_mvdelch' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_mvgetch' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_mvhline' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_mvinch' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_mvvline' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_mvwaddstr' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_napms' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_new_panel' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_newpad' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_newwin' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_nl' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_nocbreak' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_noecho' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_nonl' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_noqiflush' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_noraw' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_pair_content' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_panel_above' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_panel_below' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_panel_window' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_pnoutrefresh' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_prefresh' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_putp' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_qiflush' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_raw' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_refresh' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_replace_panel' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_reset_prog_mode' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_reset_shell_mode' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_resetty' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_savetty' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_scr_dump' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_scr_init' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_scr_restore' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_scr_set' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_scrl' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_show_panel' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_slk_attr' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_slk_attroff' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_slk_attron' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_slk_attrset' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_slk_clear' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_slk_color' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_slk_init' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_slk_noutrefresh' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_slk_refresh' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_slk_restore' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_slk_set' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_slk_touch' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_standend' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_standout' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_start_color' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_termattrs' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_termname' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_timeout' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_top_panel' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_typeahead' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_ungetch' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_ungetmouse' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_update_panels' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_use_default_colors' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_use_env' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_use_extended_names' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_vidattr' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_vline' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_waddch' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_waddstr' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_wattroff' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_wattron' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_wattrset' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_wborder' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_wclear' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_wcolor_set' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_werase' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_wgetch' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_whline' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_wmouse_trafo' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_wmove' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_wnoutrefresh' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_wrefresh' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_wstandend' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_wstandout' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'ncurses_wvline' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'fdf_add_doc_javascript' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_add_template' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_close' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_create' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_enum_values' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_errno' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_error' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_get_ap' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_get_attachment' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_get_encoding' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_get_file' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_get_flags' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_get_opt' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_get_status' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_get_value' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_get_version' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_header' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_next_field_name' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_open_string' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_open' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_remove_item' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_save_string' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_save' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_set_ap' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_set_encoding' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_set_file' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_set_flags' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_set_javascript_action' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_set_on_import_javascript' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_set_opt' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_set_status' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_set_submit_form_action' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_set_target_frame' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_set_value' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'fdf_set_version' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'ming_keypress' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'ming_setcubicthreshold' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'ming_setscale' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'ming_setswfcompression' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'ming_useconstants' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'ming_useswfversion' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'dbase_add_record' => [
            '5.3'       => true,
            'extension' => 'dbase',
        ],
        'dbase_close' => [
            '5.3'       => true,
            'extension' => 'dbase',
        ],
        'dbase_create' => [
            '5.3'       => true,
            'extension' => 'dbase',
        ],
        'dbase_delete_record' => [
            '5.3'       => true,
            'extension' => 'dbase',
        ],
        'dbase_get_header_info' => [
            '5.3'       => true,
            'extension' => 'dbase',
        ],
        'dbase_get_record_with_names' => [
            '5.3'       => true,
            'extension' => 'dbase',
        ],
        'dbase_get_record' => [
            '5.3'       => true,
            'extension' => 'dbase',
        ],
        'dbase_numfields' => [
            '5.3'       => true,
            'extension' => 'dbase',
        ],
        'dbase_numrecords' => [
            '5.3'       => true,
            'extension' => 'dbase',
        ],
        'dbase_open' => [
            '5.3'       => true,
            'extension' => 'dbase',
        ],
        'dbase_pack' => [
            '5.3'       => true,
            'extension' => 'dbase',
        ],
        'dbase_replace_record' => [
            '5.3'       => true,
            'extension' => 'dbase',
        ],
        'fbsql_affected_rows' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_autocommit' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_blob_size' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_change_user' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_clob_size' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_close' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_commit' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_connect' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_create_blob' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_create_clob' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_create_db' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_data_seek' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_database_password' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_database' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_db_query' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_db_status' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_drop_db' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_errno' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_error' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_fetch_array' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_fetch_assoc' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_fetch_field' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_fetch_lengths' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_fetch_object' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_fetch_row' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_field_flags' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_field_len' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_field_name' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_field_seek' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_field_table' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_field_type' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_free_result' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_get_autostart_info' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_hostname' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_insert_id' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_list_dbs' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_list_fields' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_list_tables' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_next_result' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_num_fields' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_num_rows' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_password' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_pconnect' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_query' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_read_blob' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_read_clob' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_result' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_rollback' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_rows_fetched' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_select_db' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_set_characterset' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_set_lob_mode' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_set_password' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_set_transaction' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_start_db' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_stop_db' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_table_name' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_tablename' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_username' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql_warnings' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'msql_affected_rows' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_close' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_connect' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_create_db' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_createdb' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_data_seek' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_db_query' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_dbname' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_drop_db' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_error' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_fetch_array' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_fetch_field' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_fetch_object' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_fetch_row' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_field_flags' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_field_len' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_field_name' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_field_seek' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_field_table' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_field_type' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_fieldflags' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_fieldlen' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_fieldname' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_fieldtable' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_fieldtype' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_free_result' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_list_dbs' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_list_fields' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_list_tables' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_num_fields' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_num_rows' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_numfields' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_numrows' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_pconnect' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_query' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_regcase' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_result' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_select_db' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql_tablename' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'mysqli_disable_reads_from_master' => [
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_disable_rpl_parse' => [
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_enable_reads_from_master' => [
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_enable_rpl_parse' => [
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_master_query' => [
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_rpl_parse_enabled' => [
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_rpl_probe' => [
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_slave_query' => [
            '5.3'       => true,
            'extension' => 'mysqli',
        ],

        'call_user_method' => [
            '4.1'         => false,
            '7.0'         => true,
            'alternative' => 'call_user_func()',
        ],
        'call_user_method_array' => [
            '4.1'         => false,
            '7.0'         => true,
            'alternative' => 'call_user_func_array()',
        ],
        'define_syslog_variables' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'dl' => [
            '5.3' => false,
        ],
        'ereg' => [
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'preg_match()',
            'extension'   => 'ereg',
        ],
        'ereg_replace' => [
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'preg_replace()',
            'extension'   => 'ereg',
        ],
        'eregi' => [
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'preg_match() (with the i modifier)',
            'extension'   => 'ereg',
        ],
        'eregi_replace' => [
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'preg_replace() (with the i modifier)',
            'extension'   => 'ereg',
        ],
        'imagepsbbox' => [
            '7.0'       => true,
            'extension' => 'gd',
        ],
        'imagepsencodefont' => [
            '7.0'       => true,
            'extension' => 'gd',
        ],
        'imagepsextendfont' => [
            '7.0'       => true,
            'extension' => 'gd',
        ],
        'imagepsfreefont' => [
            '7.0'       => true,
            'extension' => 'gd',
        ],
        'imagepsloadfont' => [
            '7.0'       => true,
            'extension' => 'gd',
        ],
        'imagepsslantfont' => [
            '7.0'       => true,
            'extension' => 'gd',
        ],
        'imagepstext' => [
            '7.0'       => true,
            'extension' => 'gd',
        ],
        'import_request_variables' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'ldap_sort' => [
            '7.0'       => false,
            '8.0'       => true,
            'extension' => 'ldap',
        ],
        'mcrypt_generic_end' => [
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'mcrypt_generic_deinit()',
            'extension'   => 'mcrypt',
        ],
        'mysql_db_query' => [
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'mysqli::select_db() and mysqli::query()',
            'extension'   => 'mysql',
        ],
        'mysql_escape_string' => [
            '4.3'         => false,
            '7.0'         => true,
            'alternative' => 'mysqli::real_escape_string()',
            'extension'   => 'mysql',
        ],
        'mysql_list_dbs' => [
            '5.4'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_list_fields' => [
            '5.4'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysqli_bind_param' => [
            '5.3'         => false,
            '5.4'         => true,
            'alternative' => 'mysqli_stmt::bind_param()',
            'extension'   => 'mysqli',
        ],
        'mysqli_bind_result' => [
            '5.3'         => false,
            '5.4'         => true,
            'alternative' => 'mysqli_stmt::bind_result()',
            'extension'   => 'mysqli',
        ],
        'mysqli_client_encoding' => [
            '5.3'         => false,
            '5.4'         => true,
            'alternative' => 'mysqli::character_set_name()',
            'extension'   => 'mysqli',
        ],
        'mysqli_fetch' => [
            '5.3'         => false,
            '5.4'         => true,
            'alternative' => 'mysqli_stmt::fetch()',
            'extension'   => 'mysqli',
        ],
        'mysqli_param_count' => [
            '5.3'         => false,
            '5.4'         => true,
            'alternative' => 'mysqli_stmt_param_count()',
            'extension'   => 'mysqli',
        ],
        'mysqli_get_metadata' => [
            '5.3'         => false,
            '5.4'         => true,
            'alternative' => 'mysqli_stmt::result_metadata()',
            'extension'   => 'mysqli',
        ],
        'mysqli_send_long_data' => [
            '5.3'         => false,
            '5.4'         => true,
            'alternative' => 'mysqli_stmt::send_long_data()',
            'extension'   => 'mysqli',
        ],
        'magic_quotes_runtime' => [
            '5.3' => false,
            '7.0' => true,
        ],
        'session_register' => [
            '5.3'         => false,
            '5.4'         => true,
            'alternative' => '$_SESSION',
        ],
        'session_unregister' => [
            '5.3'         => false,
            '5.4'         => true,
            'alternative' => '$_SESSION',
        ],
        'session_is_registered' => [
            '5.3'         => false,
            '5.4'         => true,
            'alternative' => '$_SESSION',
        ],
        'set_magic_quotes_runtime' => [
            '5.3' => false,
            '7.0' => true,
        ],
        'set_socket_blocking' => [
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'stream_set_blocking()',
        ],
        'split' => [
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'preg_split(), explode() or str_split()',
            'extension'   => 'ereg',
        ],
        'spliti' => [
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'preg_split() (with the i modifier)',
            'extension'   => 'ereg',
        ],
        'sql_regcase' => [
            '5.3'       => false,
            '7.0'       => true,
            'extension' => 'ereg',
        ],
        'php_logo_guid' => [
            '5.5' => true,
        ],
        'php_egg_logo_guid' => [
            '5.5' => true,
        ],
        'php_real_logo_guid' => [
            '5.5' => true,
        ],
        'zend_logo_guid' => [
            '5.5'         => true,
            'alternative' => 'text string "PHPE9568F35-D428-11d2-A769-00AA001ACF42"',
        ],
        'datefmt_set_timezone_id' => [
            '5.5'         => false,
            '7.0'         => true,
            'alternative' => 'IntlDateFormatter::setTimeZone()',
        ],
        'mcrypt_ecb' => [
            '5.5'         => false,
            '7.0'         => true,
            'alternative' => 'mcrypt_decrypt()/mcrypt_encrypt()',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_cbc' => [
            '5.5'         => false,
            '7.0'         => true,
            'alternative' => 'mcrypt_decrypt()/mcrypt_encrypt()',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_cfb' => [
            '5.5'         => false,
            '7.0'         => true,
            'alternative' => 'mcrypt_decrypt()/mcrypt_encrypt()',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_ofb' => [
            '5.5'         => false,
            '7.0'         => true,
            'alternative' => 'mcrypt_decrypt()/mcrypt_encrypt()',
            'extension'   => 'mcrypt',
        ],
        'ocibindbyname' => [
            '5.4'         => false,
            'alternative' => 'oci_bind_by_name()',
            'extension'   => 'oci8',
        ],
        'ocicancel' => [
            '5.4'         => false,
            'alternative' => 'oci_cancel()',
            'extension'   => 'oci8',
        ],
        'ocicloselob' => [
            '5.4'         => false,
            'alternative' => 'OCI-Lob::close() / OCILob::close() (PHP 8+)',
            'extension'   => 'oci8',
        ],
        'ocicollappend' => [
            '5.4'         => false,
            'alternative' => 'OCI-Collection::append() / OCICollection::append() (PHP 8+)',
            'extension'   => 'oci8',
        ],
        'ocicollassign' => [
            '5.4'         => false,
            'alternative' => 'OCI-Collection::assign() / OCICollection::assign() (PHP 8+)',
            'extension'   => 'oci8',
        ],
        'ocicollassignelem' => [
            '5.4'         => false,
            'alternative' => 'OCI-Collection::assignElem() / OCICollection::assignElem() (PHP 8+)',
            'extension'   => 'oci8',
        ],
        'ocicollgetelem' => [
            '5.4'         => false,
            'alternative' => 'OCI-Collection::getElem() / OCICollection::getElem() (PHP 8+)',
            'extension'   => 'oci8',
        ],
        'ocicollmax' => [
            '5.4'         => false,
            'alternative' => 'OCI-Collection::max() / OCICollection::max() (PHP 8+)',
            'extension'   => 'oci8',
        ],
        'ocicollsize' => [
            '5.4'         => false,
            'alternative' => 'OCI-Collection::size() / OCICollection::size() (PHP 8+)',
            'extension'   => 'oci8',
        ],
        'ocicolltrim' => [
            '5.4'         => false,
            'alternative' => 'OCI-Collection::trim() / OCICollection::trim() (PHP 8+)',
            'extension'   => 'oci8',
        ],
        'ocicolumnisnull' => [
            '5.4'         => false,
            'alternative' => 'oci_field_is_null()',
            'extension'   => 'oci8',
        ],
        'ocicolumnname' => [
            '5.4'         => false,
            'alternative' => 'oci_field_name()',
            'extension'   => 'oci8',
        ],
        'ocicolumnprecision' => [
            '5.4'         => false,
            'alternative' => 'oci_field_precision()',
            'extension'   => 'oci8',
        ],
        'ocicolumnscale' => [
            '5.4'         => false,
            'alternative' => 'oci_field_scale()',
            'extension'   => 'oci8',
        ],
        'ocicolumnsize' => [
            '5.4'         => false,
            'alternative' => 'oci_field_size()',
            'extension'   => 'oci8',
        ],
        'ocicolumntype' => [
            '5.4'         => false,
            'alternative' => 'oci_field_type()',
            'extension'   => 'oci8',
        ],
        'ocicolumntyperaw' => [
            '5.4'         => false,
            'alternative' => 'oci_field_type_raw()',
            'extension'   => 'oci8',
        ],
        'ocicommit' => [
            '5.4'         => false,
            'alternative' => 'oci_commit()',
            'extension'   => 'oci8',
        ],
        'ocidefinebyname' => [
            '5.4'         => false,
            'alternative' => 'oci_define_by_name()',
            'extension'   => 'oci8',
        ],
        'ocierror' => [
            '5.4'         => false,
            'alternative' => 'oci_error()',
            'extension'   => 'oci8',
        ],
        'ociexecute' => [
            '5.4'         => false,
            'alternative' => 'oci_execute()',
            'extension'   => 'oci8',
        ],
        'ocifetch' => [
            '5.4'         => false,
            'alternative' => 'oci_fetch()',
            'extension'   => 'oci8',
        ],
        'ocifetchinto' => [
            '5.4'       => false,
            'extension' => 'oci8',
        ],
        'ocifetchstatement' => [
            '5.4'         => false,
            'alternative' => 'oci_fetch_all()',
            'extension'   => 'oci8',
        ],
        'ocifreecollection' => [
            '5.4'         => false,
            'alternative' => 'OCI-Collection::free() / OCICollection::free() (PHP 8+)',
            'extension'   => 'oci8',
        ],
        'ocifreecursor' => [
            '5.4'         => false,
            'alternative' => 'oci_free_statement()',
            'extension'   => 'oci8',
        ],
        'ocifreedesc' => [
            '5.4'         => false,
            'alternative' => 'OCI-Lob::free() / OCILob::free() (PHP 8+)',
            'extension'   => 'oci8',
        ],
        'ocifreestatement' => [
            '5.4'         => false,
            'alternative' => 'oci_free_statement()',
            'extension'   => 'oci8',
        ],
        'ociinternaldebug' => [
            '5.4'         => false,
            '8.0'         => true,
            'alternative' => 'oci_internal_debug() (PHP < 8.0)',
            'extension'   => 'oci8',
        ],
        'ociloadlob' => [
            '5.4'         => false,
            'alternative' => 'OCI-Lob::load() / OCILob::load() (PHP 8+)',
            'extension'   => 'oci8',
        ],
        'ocilogoff' => [
            '5.4'         => false,
            'alternative' => 'oci_close()',
            'extension'   => 'oci8',
        ],
        'ocilogon' => [
            '5.4'         => false,
            'alternative' => 'oci_connect()',
            'extension'   => 'oci8',
        ],
        'ocinewcollection' => [
            '5.4'         => false,
            'alternative' => 'oci_new_collection()',
            'extension'   => 'oci8',
        ],
        'ocinewcursor' => [
            '5.4'         => false,
            'alternative' => 'oci_new_cursor()',
            'extension'   => 'oci8',
        ],
        'ocinewdescriptor' => [
            '5.4'         => false,
            'alternative' => 'oci_new_descriptor()',
            'extension'   => 'oci8',
        ],
        'ocinlogon' => [
            '5.4'         => false,
            'alternative' => 'oci_new_connect()',
            'extension'   => 'oci8',
        ],
        'ocinumcols' => [
            '5.4'         => false,
            'alternative' => 'oci_num_fields()',
            'extension'   => 'oci8',
        ],
        'ociparse' => [
            '5.4'         => false,
            'alternative' => 'oci_parse()',
            'extension'   => 'oci8',
        ],
        'ociplogon' => [
            '5.4'         => false,
            'alternative' => 'oci_pconnect()',
            'extension'   => 'oci8',
        ],
        'ociresult' => [
            '5.4'         => false,
            'alternative' => 'oci_result()',
            'extension'   => 'oci8',
        ],
        'ocirollback' => [
            '5.4'         => false,
            'alternative' => 'oci_rollback()',
            'extension'   => 'oci8',
        ],
        'ocirowcount' => [
            '5.4'         => false,
            'alternative' => 'oci_num_rows()',
            'extension'   => 'oci8',
        ],
        'ocisavelob' => [
            '5.4'         => false,
            'alternative' => 'OCI-Lob::save() / OCILob::save() (PHP 8+)',
            'extension'   => 'oci8',
        ],
        'ocisavelobfile' => [
            '5.4'         => false,
            'alternative' => 'OCI-Lob::import() / OCILob::import() (PHP 8+)',
            'extension'   => 'oci8',
        ],
        'ociserverversion' => [
            '5.4'         => false,
            'alternative' => 'oci_server_version()',
            'extension'   => 'oci8',
        ],
        'ocisetprefetch' => [
            '5.4'         => false,
            'alternative' => 'oci_set_prefetch()',
            'extension'   => 'oci8',
        ],
        'ocistatementtype' => [
            '5.4'         => false,
            'alternative' => 'oci_statement_type()',
            'extension'   => 'oci8',
        ],
        'ociwritelobtofile' => [
            '5.4'         => false,
            'alternative' => 'OCI-Lob::export() / OCILob::export() (PHP 8+)',
            'extension'   => 'oci8',
        ],
        'ociwritetemporarylob' => [
            '5.4'         => false,
            'alternative' => 'OCI-Lob::writeTemporary() / OCILob::writeTemporary() (PHP 8+)',
            'extension'   => 'oci8',
        ],
        'mysqli_get_cache_stats' => [
            '5.4'       => true,
            'extension' => 'mysqli',
        ],
        'sqlite_array_query' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_busy_timeout' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_changes' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_close' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_column' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_create_aggregate' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_create_function' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_current' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_error_string' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_escape_string' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_exec' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_factory' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_fetch_all' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_fetch_array' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_fetch_column_types' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_fetch_object' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_fetch_single' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_fetch_string' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_field_name' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_has_more' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_has_prev' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_key' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_last_error' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_last_insert_rowid' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_libencoding' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_libversion' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_next' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_num_fields' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_num_rows' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_open' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_popen' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_prev' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_query' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_rewind' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_seek' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_single_query' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_udf_decode_binary' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_udf_encode_binary' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_unbuffered_query' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'sqlite_valid' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],

        'mssql_bind' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_close' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_connect' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_data_seek' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_execute' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_fetch_array' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_fetch_assoc' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_fetch_batch' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_fetch_field' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_fetch_object' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_fetch_row' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_field_length' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_field_name' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_field_seek' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_field_type' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_free_result' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_free_statement' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_get_last_message' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_guid_string' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_init' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_min_error_severity' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_min_message_severity' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_next_result' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_num_fields' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_num_rows' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_pconnect' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_query' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_result' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_rows_affected' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql_select_db' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mysql_affected_rows' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_client_encoding' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_close' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_connect' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_create_db' => [
            '4.3'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_data_seek' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_db_name' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_drop_db' => [
            '4.3'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_errno' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_error' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_fetch_array' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_fetch_assoc' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_fetch_field' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_fetch_lengths' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_fetch_object' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_fetch_row' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_field_flags' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_field_len' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_field_name' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_field_seek' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_field_table' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_field_type' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_free_result' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_get_client_info' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_get_host_info' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_get_proto_info' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_get_server_info' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_info' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_insert_id' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_list_processes' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_list_tables' => [
            '4.3.7'     => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_num_fields' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_num_rows' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_pconnect' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_ping' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_query' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_real_escape_string' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_result' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_select_db' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_set_charset' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_stat' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_tablename' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_thread_id' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_unbuffered_query' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_fieldname' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_fieldtable' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_fieldlen' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_fieldtype' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_fieldflags' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_selectdb' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_freeresult' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_numfields' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_numrows' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_listdbs' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_listfields' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_dbname' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql_table_name' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'sybase_affected_rows' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_close' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_connect' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_data_seek' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_deadlock_retry_count' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_fetch_array' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_fetch_assoc' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_fetch_field' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_fetch_object' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_fetch_row' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_field_seek' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_free_result' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_get_last_message' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_min_client_severity' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_min_error_severity' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_min_message_severity' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_min_server_severity' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_num_fields' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_num_rows' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_pconnect' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_query' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_result' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_select_db' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_set_message_handler' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase_unbuffered_query' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],

        'mcrypt_create_iv' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'random_bytes() or OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_decrypt' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_enc_get_algorithms_name' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_enc_get_block_size' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_enc_get_iv_size' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_enc_get_key_size' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_enc_get_modes_name' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_enc_get_supported_key_sizes' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_enc_is_block_algorithm_mode' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_enc_is_block_algorithm' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_enc_is_block_mode' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_enc_self_test' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_encrypt' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_generic_deinit' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_generic_init' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_generic' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_get_block_size' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_get_cipher_name' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_get_iv_size' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_get_key_size' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_list_algorithms' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_list_modes' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_module_close' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_module_get_algo_block_size' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_module_get_algo_key_size' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_module_get_supported_key_sizes' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_module_is_block_algorithm_mode' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_module_is_block_algorithm' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_module_is_block_mode' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_module_open' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mcrypt_module_self_test' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'mdecrypt_generic' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'OpenSSL',
            'extension'   => 'mcrypt',
        ],
        'jpeg2wbmp' => [
            '7.2'         => false,
            '8.0'         => true,
            'alternative' => 'imagecreatefromjpeg() and imagewbmp()',
            'extension'   => 'gd',
        ],
        'png2wbmp' => [
            '7.2'         => false,
            '8.0'         => true,
            'alternative' => 'imagecreatefrompng() or imagewbmp()',
            'extension'   => 'gd',
        ],
        '__autoload' => [
            '7.2'         => false,
            '8.0'         => true,
            'alternative' => 'spl_autoload_register()',
        ],
        'create_function' => [
            '7.2'         => false,
            '8.0'         => true,
            'alternative' => 'an anonymous function',
        ],
        'each' => [
            '7.2'         => false,
            '8.0'         => true,
            'alternative' => 'a foreach loop or ArrayIterator',
        ],
        'gmp_random' => [
            '7.2'         => false,
            '8.0'         => true,
            'alternative' => 'gmp_random_bits() or gmp_random_range()',
            'extension'   => 'gmp',
        ],
        'read_exif_data' => [
            '7.2'         => false,
            '8.0'         => true,
            'alternative' => 'exif_read_data()',
            'extension'   => 'exif',
        ],

        'image2wbmp' => [
            '7.3'         => false,
            '8.0'         => true,
            'alternative' => 'imagewbmp()',
            'extension'   => 'gd',
        ],
        'mbregex_encoding' => [
            '7.3'         => false,
            '8.0'         => true,
            'alternative' => 'mb_regex_encoding()',
            'extension'   => 'mbstring',
        ],
        'mbereg' => [
            '7.3'         => false,
            '8.0'         => true,
            'alternative' => 'mb_ereg()',
            'extension'   => 'mbstring',
        ],
        'mberegi' => [
            '7.3'         => false,
            '8.0'         => true,
            'alternative' => 'mb_eregi()',
            'extension'   => 'mbstring',
        ],
        'mbereg_replace' => [
            '7.3'         => false,
            '8.0'         => true,
            'alternative' => 'mb_ereg_replace()',
            'extension'   => 'mbstring',
        ],
        'mberegi_replace' => [
            '7.3'         => false,
            '8.0'         => true,
            'alternative' => 'mb_eregi_replace()',
            'extension'   => 'mbstring',
        ],
        'mbsplit' => [
            '7.3'         => false,
            '8.0'         => true,
            'alternative' => 'mb_split()',
            'extension'   => 'mbstring',
        ],
        'mbereg_match' => [
            '7.3'         => false,
            '8.0'         => true,
            'alternative' => 'mb_ereg_match()',
            'extension'   => 'mbstring',
        ],
        'mbereg_search' => [
            '7.3'         => false,
            '8.0'         => true,
            'alternative' => 'mb_ereg_search()',
            'extension'   => 'mbstring',
        ],
        'mbereg_search_pos' => [
            '7.3'         => false,
            '8.0'         => true,
            'alternative' => 'mb_ereg_search_pos()',
            'extension'   => 'mbstring',
        ],
        'mbereg_search_regs' => [
            '7.3'         => false,
            '8.0'         => true,
            'alternative' => 'mb_ereg_search_regs()',
            'extension'   => 'mbstring',
        ],
        'mbereg_search_init' => [
            '7.3'         => false,
            '8.0'         => true,
            'alternative' => 'mb_ereg_search_init()',
            'extension'   => 'mbstring',
        ],
        'mbereg_search_getregs' => [
            '7.3'         => false,
            '8.0'         => true,
            'alternative' => 'mb_ereg_search_getregs()',
            'extension'   => 'mbstring',
        ],
        'mbereg_search_getpos' => [
            '7.3'         => false,
            '8.0'         => true,
            'alternative' => 'mb_ereg_search_getpos()',
            'extension'   => 'mbstring',
        ],
        'mbereg_search_setpos' => [
            '7.3'         => false,
            '8.0'         => true,
            'alternative' => 'mb_ereg_search_setpos()',
            'extension'   => 'mbstring',
        ],
        'fgetss' => [
            '7.3' => false,
            '8.0' => true,
        ],
        'gzgetss' => [
            '7.3' => false,
            '8.0' => true,
        ],

        'convert_cyr_string' => [
            '7.4'         => false,
            '8.0'         => true,
            'alternative' => 'mb_convert_encoding(), iconv() or UConverter',
        ],
        'ezmlm_hash' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'get_magic_quotes_gpc' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'get_magic_quotes_runtime' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'hebrevc' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'is_real' => [
            '7.4'         => false,
            '8.0'         => true,
            'alternative' => 'is_float()',
        ],
        'money_format' => [
            '7.4'         => false,
            '8.0'         => true,
            'alternative' => 'NumberFormatter::formatCurrency()',
        ],
        'restore_include_path' => [
            '7.4'         => false,
            '8.0'         => true,
            'alternative' => "ini_restore('include_path')",
        ],
        'ibase_add_user' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_affected_rows' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_backup' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_blob_add' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_blob_cancel' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_blob_close' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_blob_create' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_blob_echo' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_blob_get' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_blob_import' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_blob_info' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_blob_open' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_close' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_commit_ret' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_commit' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_connect' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_db_info' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_delete_user' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_drop_db' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_errcode' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_errmsg' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_execute' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_fetch_assoc' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_fetch_object' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_fetch_row' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_field_info' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_free_event_handler' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_free_query' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_free_result' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_gen_id' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_maintain_db' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_modify_user' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_name_result' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_num_fields' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_num_params' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_param_info' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_pconnect' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_prepare' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_query' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_restore' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_rollback_ret' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_rollback' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_server_info' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_service_attach' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_service_detach' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_set_event_handler' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_trans' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase_wait_event' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_add_user' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_affected_rows' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_backup' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_blob_add' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_blob_cancel' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_blob_close' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_blob_create' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_blob_echo' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_blob_get' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_blob_import' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_blob_info' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_blob_open' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_close' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_commit_ret' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_commit' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_connect' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_db_info' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_delete_user' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_drop_db' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_errcode' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_errmsg' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_execute' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_fetch_assoc' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_fetch_object' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_fetch_row' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_field_info' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_free_event_handler' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_free_query' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_free_result' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_gen_id' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_maintain_db' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_modify_user' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_name_result' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_num_fields' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_num_params' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_param_info' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_pconnect' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_prepare' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_query' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_restore' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_rollback_ret' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_rollback' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_server_info' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_service_attach' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_service_detach' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_set_event_handler' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_trans' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'fbird_wait_event' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],

        'ldap_control_paged_result_response' => [
            '7.4'         => false,
            '8.0'         => true,
            'alternative' => 'ldap_search()',
            'extension'   => 'ldap',
        ],
        'ldap_control_paged_result' => [
            '7.4'         => false,
            '8.0'         => true,
            'alternative' => 'ldap_search()',
            'extension'   => 'ldap',
        ],
        'recode_file' => [
            '7.4'         => true,
            'alternative' => 'the iconv or mbstring extension',
            'extension'   => 'recode',
        ],
        'recode_string' => [
            '7.4'         => true,
            'alternative' => 'the iconv or mbstring extension',
            'extension'   => 'recode',
        ],
        'recode' => [
            '7.4'         => true,
            'alternative' => 'the iconv or mbstring extension',
            'extension'   => 'recode',
        ],
        'wddx_add_vars' => [
            '7.4'       => true,
            'extension' => 'wddx',
        ],
        'wddx_deserialize' => [
            '7.4'       => true,
            'extension' => 'wddx',
        ],
        'wddx_packet_end' => [
            '7.4'       => true,
            'extension' => 'wddx',
        ],
        'wddx_packet_start' => [
            '7.4'       => true,
            'extension' => 'wddx',
        ],
        'wddx_serialize_value' => [
            '7.4'       => true,
            'extension' => 'wddx',
        ],
        'wddx_serialize_vars' => [
            '7.4'       => true,
            'extension' => 'wddx',
        ],
        'mysqli_embedded_server_end' => [
            '7.4'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_embedded_server_start' => [
            '7.4'       => true,
            'extension' => 'mysqli',
        ],

        'enchant_broker_get_dict_path' => [
            '8.0'       => false,
            'extension' => 'enchant',
        ],
        'enchant_broker_set_dict_path' => [
            '8.0'       => false,
            'extension' => 'enchant',
        ],
        'enchant_broker_free' => [
            '8.0'         => false,
            'alternative' => 'unset the object',
            'extension'   => 'enchant',
        ],
        'enchant_broker_free_dict' => [
            '8.0'         => false,
            'alternative' => 'unset the object',
            'extension'   => 'enchant',
        ],
        'enchant_dict_add_to_personal' => [
            '8.0'         => false,
            'alternative' => 'enchant_dict_add()',
            'extension'   => 'enchant',
        ],
        'enchant_dict_is_in_session' => [
            '8.0'         => false,
            'alternative' => 'enchant_dict_is_added()',
            'extension'   => 'enchant',
        ],
        'imap_header' => [
            '8.0'         => true,
            'alternative' => 'imap_headerinfo()',
            'extension'   => 'imap',
        ],
        'libxml_disable_entity_loader' => [
            '8.0'       => false,
            'extension' => 'libxml',
        ],
        'oci_internal_debug' => [
            '8.0'       => true,
            'extension' => 'oci8',
        ],
        'openssl_x509_free' => [
            '8.0'       => false,
            'extension' => 'openssl',
        ],
        'openssl_pkey_free' => [
            '8.0'       => false,
            'extension' => 'openssl',
        ],
        'openssl_free_key' => [
            '8.0'       => false,
            'extension' => 'openssl',
        ],
        'pg_clientencoding' => [
            '8.0'         => false,
            'alternative' => 'pg_client_encoding()',
            'extension'   => 'pgsql',
        ],
        'pg_cmdtuples' => [
            '8.0'         => false,
            'alternative' => 'pg_affected_rows()',
            'extension'   => 'pgsql',
        ],
        'pg_errormessage' => [
            '8.0'         => false,
            'alternative' => 'pg_last_error()',
            'extension'   => 'pgsql',
        ],
        'pg_fieldname' => [
            '8.0'         => false,
            'alternative' => 'pg_field_name()',
            'extension'   => 'pgsql',
        ],
        'pg_fieldnum' => [
            '8.0'         => false,
            'alternative' => 'pg_field_num()',
            'extension'   => 'pgsql',
        ],
        'pg_fieldisnull' => [
            '8.0'         => false,
            'alternative' => 'pg_field_is_null()',
            'extension'   => 'pgsql',
        ],
        'pg_fieldprtlen' => [
            '8.0'         => false,
            'alternative' => 'pg_field_prtlen()',
            'extension'   => 'pgsql',
        ],
        'pg_fieldsize' => [
            '8.0'         => false,
            'alternative' => 'pg_field_size()',
            'extension'   => 'pgsql',
        ],
        'pg_fieldtype' => [
            '8.0'         => false,
            'alternative' => 'pg_field_type()',
            'extension'   => 'pgsql',
        ],
        'pg_freeresult' => [
            '8.0'         => false,
            'alternative' => 'pg_free_result()',
            'extension'   => 'pgsql',
        ],
        'pg_getlastoid' => [
            '8.0'         => false,
            'alternative' => 'pg_last_oid()',
            'extension'   => 'pgsql',
        ],
        'pg_loclose' => [
            '8.0'         => false,
            'alternative' => 'pg_lo_close()',
            'extension'   => 'pgsql',
        ],
        'pg_locreate' => [
            '8.0'         => false,
            'alternative' => 'pg_lo_create()',
            'extension'   => 'pgsql',
        ],
        'pg_loexport' => [
            '8.0'         => false,
            'alternative' => 'pg_lo_export()',
            'extension'   => 'pgsql',
        ],
        'pg_loimport' => [
            '8.0'         => false,
            'alternative' => 'pg_lo_import()',
            'extension'   => 'pgsql',
        ],
        'pg_loopen' => [
            '8.0'         => false,
            'alternative' => 'pg_lo_open()',
            'extension'   => 'pgsql',
        ],
        'pg_loread' => [
            '8.0'         => false,
            'alternative' => 'pg_lo_read()',
            'extension'   => 'pgsql',
        ],
        'pg_loreadall' => [
            '8.0'         => false,
            'alternative' => 'pg_lo_read_all()',
            'extension'   => 'pgsql',
        ],
        'pg_lounlink' => [
            '8.0'         => false,
            'alternative' => 'pg_lo_unlink()',
            'extension'   => 'pgsql',
        ],
        'pg_lowrite' => [
            '8.0'         => false,
            'alternative' => 'pg_lo_write()',
            'extension'   => 'pgsql',
        ],
        'pg_numfields' => [
            '8.0'         => false,
            'alternative' => 'pg_num_fields()',
            'extension'   => 'pgsql',
        ],
        'pg_numrows' => [
            '8.0'         => false,
            'alternative' => 'pg_num_rows()',
            'extension'   => 'pgsql',
        ],
        'pg_result' => [
            '8.0'         => false,
            'alternative' => 'pg_fetch_result()',
            'extension'   => 'pgsql',
        ],
        'pg_setclientencoding' => [
            '8.0'         => false,
            'alternative' => 'pg_set_client_encoding()',
            'extension'   => 'pgsql',
        ],
        'shmop_close' => [
            '8.0'       => false,
            'extension' => 'shmop',
        ],
        'xmlrpc_decode_request' => [
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ],
        'xmlrpc_decode' => [
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ],
        'xmlrpc_encode_request' => [
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ],
        'xmlrpc_encode' => [
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ],
        'xmlrpc_get_type' => [
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ],
        'xmlrpc_is_fault' => [
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ],
        'xmlrpc_parse_method_descriptions' => [
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ],
        'xmlrpc_server_add_introspection_data' => [
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ],
        'xmlrpc_server_call_method' => [
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ],
        'xmlrpc_server_create' => [
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ],
        'xmlrpc_server_destroy' => [
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ],
        'xmlrpc_server_register_introspection_callback' => [
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ],
        'xmlrpc_server_register_method' => [
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ],
        'xmlrpc_set_type' => [
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ],
        'zip_close' => [
            '8.0'         => false,
            'alternative' => 'ZipArchive::close()',
            'extension'   => 'zip',
        ],
        'zip_entry_close' => [
            '8.0'         => false,
            'alternative' => 'ZipArchive',
            'extension'   => 'zip',
        ],
        'zip_entry_compressedsize' => [
            '8.0'         => false,
            'alternative' => 'ZipArchive',
            'extension'   => 'zip',
        ],
        'zip_entry_compressionmethod' => [
            '8.0'         => false,
            'alternative' => 'ZipArchive',
            'extension'   => 'zip',
        ],
        'zip_entry_filesize' => [
            '8.0'         => false,
            'alternative' => 'ZipArchive',
            'extension'   => 'zip',
        ],
        'zip_entry_name' => [
            '8.0'         => false,
            'alternative' => 'ZipArchive',
            'extension'   => 'zip',
        ],
        'zip_entry_open' => [
            '8.0'         => false,
            'alternative' => 'ZipArchive',
            'extension'   => 'zip',
        ],
        'zip_entry_read' => [
            '8.0'         => false,
            'alternative' => 'ZipArchive',
            'extension'   => 'zip',
        ],
        'zip_open' => [
            '8.0'         => false,
            'alternative' => 'ZipArchive::open()',
            'extension'   => 'zip',
        ],
        'zip_read' => [
            '8.0'         => false,
            'alternative' => 'ZipArchive',
            'extension'   => 'zip',
        ],

        'date_sunrise' => [
            '8.1'         => false,
            'alternative' => 'date_sun_info()',
        ],
        'date_sunset' => [
            '8.1'         => false,
            'alternative' => 'date_sun_info()',
        ],
        'strptime' => [
            '8.1'         => false,
            'alternative' => 'date_parse_from_format() or IntlDateFormatter::parse()',
        ],
        'strftime' => [
            '8.1'         => false,
            'alternative' => 'date() or IntlDateFormatter::format()',
        ],
        'gmstrftime' => [
            '8.1'         => false,
            'alternative' => 'date() or IntlDateFormatter::format()',
        ],
        'mhash_count' => [
            '8.1'         => false,
            'alternative' => 'the hash_*() functions',
            'extension'   => 'hash',
        ],
        'mhash_get_block_size' => [
            '8.1'         => false,
            'alternative' => 'the hash_*() functions',
            'extension'   => 'hash',
        ],
        'mhash_get_hash_name' => [
            '8.1'         => false,
            'alternative' => 'the hash_*() functions',
            'extension'   => 'hash',
        ],
        'mhash_keygen_s2k' => [
            '8.1'         => false,
            'alternative' => 'the hash_*() functions',
            'extension'   => 'hash',
        ],
        'mhash' => [
            '8.1'         => false,
            'alternative' => 'the hash_*() functions',
            'extension'   => 'hash',
        ],
        'odbc_result_all' => [
            '8.1'       => false,
            'extension' => 'odbc',
        ],

        'utf8_encode' => [
            '8.2'         => false,
            'alternative' => 'mb_convert_encoding(), UConverter::transcode() or iconv',
        ],
        'utf8_decode' => [
            '8.2'         => false,
            'alternative' => 'mb_convert_encoding(), UConverter::transcode() or iconv',
        ],

        'assert_options' => [
            '8.3' => false,
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
        $this->removedFunctions = \array_change_key_case($this->removedFunctions, \CASE_LOWER);

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
     * error/warning for a matched item.
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
        $itemArray   = $this->removedFunctions[$itemInfo['nameLc']];
        $versionInfo = $this->getVersionInfo($itemArray);
        $isError     = null;

        if (empty($versionInfo['removed']) === false
            && ScannedCode::shouldRunOnOrAbove($versionInfo['removed']) === true
        ) {
            $isError = true;
        } elseif (empty($versionInfo['deprecated']) === false
            && ScannedCode::shouldRunOnOrAbove($versionInfo['deprecated']) === true
        ) {
            $isError = false;

            // Reset the 'removed' info as it is not relevant for the current notice.
            $versionInfo['removed'] = '';
        }

        if (isset($isError) === false) {
            return;
        }

        $this->addMessage($phpcsFile, $stackPtr, $isError, $itemInfo, $versionInfo);
    }


    /**
     * Generates the error or warning for this item.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $stackPtr    The position of the relevant token in
     *                                                 the stack.
     * @param bool                        $isError     Whether this should be an error or a warning.
     * @param array                       $itemInfo    Base information about the item.
     * @param string[]                    $versionInfo Array with detail (version) information
     *                                                 relevant to the item.
     *
     * @return void
     */
    protected function addMessage(File $phpcsFile, $stackPtr, $isError, array $itemInfo, array $versionInfo)
    {
        // Overrule the default message template.
        $this->msgTemplate = 'Function %s() is ';

        $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['name'], $versionInfo);

        MessageHelper::addMessage(
            $phpcsFile,
            $msgInfo['message'],
            $stackPtr,
            $isError,
            $msgInfo['errorcode'],
            $msgInfo['data']
        );
    }
}
