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
 * Test the RemovedFunctions sniff.
 *
 * @group removedFunctions
 * @group functionUse
 *
 * @covers \PHPCompatibility\Sniffs\FunctionUse\RemovedFunctionsSniff
 *
 * @since 5.5
 */
class RemovedFunctionsUnitTest extends BaseSniffTest
{

    /**
     * testDeprecatedFunction
     *
     * @dataProvider dataDeprecatedFunction
     *
     * @param string $functionName      Name of the function.
     * @param string $deprecatedIn      The PHP version in which the function was deprecated.
     * @param array  $lines             The line numbers in the test file which apply to this function.
     * @param string $okVersion         A PHP version in which the function was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     *
     * @return void
     */
    public function testDeprecatedFunction($functionName, $deprecatedIn, $lines, $okVersion, $deprecatedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "Function {$functionName}() is deprecated since PHP {$deprecatedIn}";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedFunction()
     *
     * @return array
     */
    public function dataDeprecatedFunction()
    {
        return [
            ['dl', '5.3', [6], '5.2'],
            ['ocifetchinto', '5.4', [63], '5.3'],
            ['enchant_broker_get_dict_path', '8.0', [1172], '7.4'],
            ['enchant_broker_set_dict_path', '8.0', [1173], '7.4'],
            ['libxml_disable_entity_loader', '8.0', [1191], '7.4'],
            ['openssl_x509_free', '8.0', [1189], '7.4'],
            ['openssl_pkey_free', '8.0', [1190], '7.4'],
            ['shmop_close', '8.0', [1217], '7.4'],
            ['openssl_free_key', '8.0', [1218], '7.4'],
        ];
    }


    /**
     * testDeprecatedFunctionWithAlternative
     *
     * @dataProvider dataDeprecatedFunctionWithAlternative
     *
     * @param string $functionName      Name of the function.
     * @param string $deprecatedIn      The PHP version in which the function was deprecated.
     * @param string $alternative       An alternative function.
     * @param array  $lines             The line numbers in the test file which apply to this function.
     * @param string $okVersion         A PHP version in which the function was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     *
     * @return void
     */
    public function testDeprecatedFunctionWithAlternative($functionName, $deprecatedIn, $alternative, $lines, $okVersion, $deprecatedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "Function {$functionName}() is deprecated since PHP {$deprecatedIn}; Use {$alternative} instead";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedFunctionWithAlternative()
     *
     * @return array
     */
    public function dataDeprecatedFunctionWithAlternative()
    {
        return [
            ['ocibindbyname', '5.4', 'oci_bind_by_name()', [41], '5.3'],
            ['ocicancel', '5.4', 'oci_cancel()', [42], '5.3'],
            ['ocicloselob', '5.4', 'OCI-Lob::close()', [43], '5.3'],
            ['ocicollappend', '5.4', 'OCI-Collection::append()', [44], '5.3'],
            ['ocicollassign', '5.4', 'OCI-Collection::assign()', [45], '5.3'],
            ['ocicollassignelem', '5.4', 'OCI-Collection::assignElem()', [46], '5.3'],
            ['ocicollgetelem', '5.4', 'OCI-Collection::getElem()', [47], '5.3'],
            ['ocicollmax', '5.4', 'OCI-Collection::max()', [48], '5.3'],
            ['ocicollsize', '5.4', 'OCI-Collection::size()', [49], '5.3'],
            ['ocicolltrim', '5.4', 'OCI-Collection::trim()', [50], '5.3'],
            ['ocicolumnisnull', '5.4', 'oci_field_is_null()', [51], '5.3'],
            ['ocicolumnname', '5.4', 'oci_field_name()', [52], '5.3'],
            ['ocicolumnprecision', '5.4', 'oci_field_precision()', [53], '5.3'],
            ['ocicolumnscale', '5.4', 'oci_field_scale()', [54], '5.3'],
            ['ocicolumnsize', '5.4', 'oci_field_size()', [55], '5.3'],
            ['ocicolumntype', '5.4', 'oci_field_type()', [56], '5.3'],
            ['ocicolumntyperaw', '5.4', 'oci_field_type_raw()', [57], '5.3'],
            ['ocicommit', '5.4', 'oci_commit()', [58], '5.3'],
            ['ocidefinebyname', '5.4', 'oci_define_by_name()', [59], '5.3'],
            ['ocierror', '5.4', 'oci_error()', [60], '5.3'],
            ['ociexecute', '5.4', 'oci_execute()', [61], '5.3'],
            ['ocifetch', '5.4', 'oci_fetch()', [62], '5.3'],
            ['ocifetchstatement', '5.4', 'oci_fetch_all()', [64], '5.3'],
            ['ocifreecollection', '5.4', 'OCI-Collection::free()', [65], '5.3'],
            ['ocifreecursor', '5.4', 'oci_free_statement()', [66], '5.3'],
            ['ocifreedesc', '5.4', 'OCI-Lob::free()', [67], '5.3'],
            ['ocifreestatement', '5.4', 'oci_free_statement()', [68], '5.3'],
            ['ociloadlob', '5.4', 'OCI-Lob::load()', [70], '5.3'],
            ['ocilogoff', '5.4', 'oci_close()', [71], '5.3'],
            ['ocilogon', '5.4', 'oci_connect()', [72], '5.3'],
            ['ocinewcollection', '5.4', 'oci_new_collection()', [73], '5.3'],
            ['ocinewcursor', '5.4', 'oci_new_cursor()', [74], '5.3'],
            ['ocinewdescriptor', '5.4', 'oci_new_descriptor()', [75], '5.3'],
            ['ocinlogon', '5.4', 'oci_new_connect()', [76], '5.3'],
            ['ocinumcols', '5.4', 'oci_num_fields()', [77], '5.3'],
            ['ociparse', '5.4', 'oci_parse()', [78], '5.3'],
            ['ociplogon', '5.4', 'oci_pconnect()', [79], '5.3'],
            ['ociresult', '5.4', 'oci_result()', [80], '5.3'],
            ['ocirollback', '5.4', 'oci_rollback()', [81], '5.3'],
            ['ocirowcount', '5.4', 'oci_num_rows()', [82], '5.3'],
            ['ocisavelob', '5.4', 'OCI-Lob::save()', [83], '5.3'],
            ['ocisavelobfile', '5.4', 'OCI-Lob::import()', [84], '5.3'],
            ['ociserverversion', '5.4', 'oci_server_version()', [85], '5.3'],
            ['ocisetprefetch', '5.4', 'oci_set_prefetch()', [86], '5.3'],
            ['ocistatementtype', '5.4', 'oci_statement_type()', [87], '5.3'],
            ['ociwritelobtofile', '5.4', 'OCI-Lob::export()', [88], '5.3'],
            ['ociwritetemporarylob', '5.4', 'OCI-Lob::writeTemporary()', [89], '5.3'],

            ['__autoload', '7.2', 'SPL autoload', [589], '7.1'],
            ['is_real', '7.4', 'is_float()', [239], '7.3'],

            ['enchant_broker_free', '8.0', 'unset the object', [1174], '7.4'],
            ['enchant_broker_free_dict', '8.0', 'unset the object', [1175], '7.4'],
            ['enchant_dict_add_to_personal', '8.0', 'enchant_dict_add()', [1176], '7.4'],
            ['enchant_dict_is_in_session', '8.0', 'enchant_dict_is_added()', [1177], '7.4'],
            ['zip_close', '8.0', 'ZipArchive::close()', [1179], '7.4'],
            ['zip_entry_close', '8.0', 'ZipArchive', [1180], '7.4'],
            ['zip_entry_compressedsize', '8.0', 'ZipArchive', [1181], '7.4'],
            ['zip_entry_compressionmethod', '8.0', 'ZipArchive', [1182], '7.4'],
            ['zip_entry_filesize', '8.0', 'ZipArchive', [1183], '7.4'],
            ['zip_entry_name', '8.0', 'ZipArchive', [1184], '7.4'],
            ['zip_entry_open', '8.0', 'ZipArchive', [1185], '7.4'],
            ['zip_entry_read', '8.0', 'ZipArchive', [1186], '7.4'],
            ['zip_open', '8.0', 'ZipArchive::open()', [1187], '7.4'],
            ['zip_read', '8.0', 'ZipArchive', [1188], '7.4'],
            ['pg_clientencoding', '8.0', 'pg_client_encoding()', [1192], '7.4'],
            ['pg_cmdtuples', '8.0', 'pg_affected_rows()', [1193], '7.4'],
            ['pg_errormessage', '8.0', 'pg_last_error()', [1194], '7.4'],
            ['pg_fieldname', '8.0', 'pg_field_name()', [1195], '7.4'],
            ['pg_fieldnum', '8.0', 'pg_field_num()', [1196], '7.4'],
            ['pg_fieldisnull', '8.0', 'pg_field_is_null()', [1197], '7.4'],
            ['pg_fieldprtlen', '8.0', 'pg_field_prtlen()', [1198], '7.4'],
            ['pg_fieldsize', '8.0', 'pg_field_size()', [1199], '7.4'],
            ['pg_fieldtype', '8.0', 'pg_field_type()', [1200], '7.4'],
            ['pg_freeresult', '8.0', 'pg_free_result()', [1201], '7.4'],
            ['pg_getlastoid', '8.0', 'pg_last_oid()', [1202], '7.4'],
            ['pg_loclose', '8.0', 'pg_lo_close()', [1203], '7.4'],
            ['pg_locreate', '8.0', 'pg_lo_create()', [1204], '7.4'],
            ['pg_loexport', '8.0', 'pg_lo_export()', [1205], '7.4'],
            ['pg_loimport', '8.0', 'pg_lo_import()', [1206], '7.4'],
            ['pg_loopen', '8.0', 'pg_lo_open()', [1207], '7.4'],
            ['pg_loread', '8.0', 'pg_lo_read()', [1208], '7.4'],
            ['pg_loreadall', '8.0', 'pg_lo_read_all()', [1209], '7.4'],
            ['pg_lounlink', '8.0', 'pg_lo_unlink()', [1210], '7.4'],
            ['pg_lowrite', '8.0', 'pg_lo_write()', [1211], '7.4'],
            ['pg_numfields', '8.0', 'pg_num_fields()', [1212], '7.4'],
            ['pg_numrows', '8.0', 'pg_num_rows()', [1213], '7.4'],
            ['pg_result', '8.0', 'pg_fetch_result()', [1214], '7.4'],
            ['pg_setclientencoding', '8.0', 'pg_set_client_encoding()', [1215], '7.4'],
        ];
    }


    /**
     * testRemovedFunction
     *
     * @dataProvider dataRemovedFunction
     *
     * @param string $functionName   Name of the function.
     * @param string $removedIn      The PHP version in which the function was removed.
     * @param array  $lines          The line numbers in the test file which apply to this function.
     * @param string $okVersion      A PHP version in which the function was still valid.
     * @param string $removedVersion Optional PHP version to test removed message with -
     *                               if different from the $removedIn version.
     *
     * @return void
     */
    public function testRemovedFunction($functionName, $removedIn, $lines, $okVersion, $removedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "Function {$functionName}() is removed since PHP {$removedIn}";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testRemovedFunction()
     *
     * @return array
     */
    public function dataRemovedFunction()
    {
        return [
            ['crack_check', '5.0', [592], '4.4'],
            ['crack_closedict', '5.0', [593], '4.4'],
            ['crack_getlastmessage', '5.0', [594], '4.4'],
            ['crack_opendict', '5.0', [595], '4.4'],

            ['pfpro_cleanup', '5.1', [221], '5.0'],
            ['pfpro_init', '5.1', [222], '5.0'],
            ['pfpro_process_raw', '5.1', [223], '5.0'],
            ['pfpro_process', '5.1', [224], '5.0'],
            ['pfpro_version', '5.1', [225], '5.0'],
            ['m_checkstatus', '5.1', [417], '5.0'],
            ['m_completeauthorizations', '5.1', [418], '5.0'],
            ['m_connect', '5.1', [419], '5.0'],
            ['m_connectionerror', '5.1', [420], '5.0'],
            ['m_deletetrans', '5.1', [421], '5.0'],
            ['m_destroyconn', '5.1', [422], '5.0'],
            ['m_destroyengine', '5.1', [423], '5.0'],
            ['m_getcell', '5.1', [424], '5.0'],
            ['m_getcellbynum', '5.1', [425], '5.0'],
            ['m_getcommadelimited', '5.1', [426], '5.0'],
            ['m_getheader', '5.1', [427], '5.0'],
            ['m_initconn', '5.1', [428], '5.0'],
            ['m_initengine', '5.1', [429], '5.0'],
            ['m_iscommadelimited', '5.1', [430], '5.0'],
            ['m_maxconntimeout', '5.1', [431], '5.0'],
            ['m_monitor', '5.1', [432], '5.0'],
            ['m_numcolumns', '5.1', [433], '5.0'],
            ['m_numrows', '5.1', [434], '5.0'],
            ['m_parsecommadelimited', '5.1', [435], '5.0'],
            ['m_responsekeys', '5.1', [436], '5.0'],
            ['m_responseparam', '5.1', [437], '5.0'],
            ['m_returnstatus', '5.1', [438], '5.0'],
            ['m_setblocking', '5.1', [439], '5.0'],
            ['m_setdropfile', '5.1', [440], '5.0'],
            ['m_setip', '5.1', [441], '5.0'],
            ['m_setssl_cafile', '5.1', [442], '5.0'],
            ['m_setssl_files', '5.1', [443], '5.0'],
            ['m_setssl', '5.1', [444], '5.0'],
            ['m_settimeout', '5.1', [445], '5.0'],
            ['m_sslcert_gen_hash', '5.1', [446], '5.0'],
            ['m_transactionssent', '5.1', [447], '5.0'],
            ['m_transinqueue', '5.1', [448], '5.0'],
            ['m_transkeyval', '5.1', [449], '5.0'],
            ['m_transnew', '5.1', [450], '5.0'],
            ['m_transsend', '5.1', [451], '5.0'],
            ['m_uwait', '5.1', [452], '5.0'],
            ['m_validateidentifier', '5.1', [453], '5.0'],
            ['m_verifyconnection', '5.1', [454], '5.0'],
            ['m_verifysslcert', '5.1', [455], '5.0'],
            ['dio_close', '5.1', [458], '5.0'],
            ['dio_fcntl', '5.1', [459], '5.0'],
            ['dio_open', '5.1', [460], '5.0'],
            ['dio_read', '5.1', [461], '5.0'],
            ['dio_seek', '5.1', [462], '5.0'],
            ['dio_stat', '5.1', [463], '5.0'],
            ['dio_tcsetattr', '5.1', [464], '5.0'],
            ['dio_truncate', '5.1', [465], '5.0'],
            ['dio_write', '5.1', [466], '5.0'],
            ['fam_cancel_monitor', '5.1', [514], '5.0'],
            ['fam_close', '5.1', [515], '5.0'],
            ['fam_monitor_collection', '5.1', [516], '5.0'],
            ['fam_monitor_directory', '5.1', [517], '5.0'],
            ['fam_monitor_file', '5.1', [518], '5.0'],
            ['fam_next_event', '5.1', [519], '5.0'],
            ['fam_open', '5.1', [520], '5.0'],
            ['fam_pending', '5.1', [521], '5.0'],
            ['fam_resume_monitor', '5.1', [522], '5.0'],
            ['fam_suspend_monitor', '5.1', [523], '5.0'],
            ['yp_all', '5.1', [532], '5.0'],
            ['yp_cat', '5.1', [533], '5.0'],
            ['yp_err_string', '5.1', [534], '5.0'],
            ['yp_errno', '5.1', [535], '5.0'],
            ['yp_first', '5.1', [536], '5.0'],
            ['yp_get_default_domain', '5.1', [537], '5.0'],
            ['yp_master', '5.1', [538], '5.0'],
            ['yp_match', '5.1', [539], '5.0'],
            ['yp_next', '5.1', [540], '5.0'],
            ['yp_order', '5.1', [541], '5.0'],
            ['udm_add_search_limit', '5.1', [544], '5.0'],
            ['udm_alloc_agent_array', '5.1', [545], '5.0'],
            ['udm_alloc_agent', '5.1', [546], '5.0'],
            ['udm_api_version', '5.1', [547], '5.0'],
            ['udm_cat_list', '5.1', [548], '5.0'],
            ['udm_cat_path', '5.1', [549], '5.0'],
            ['udm_check_charset', '5.1', [550], '5.0'],
            ['udm_clear_search_limits', '5.1', [551], '5.0'],
            ['udm_crc32', '5.1', [552], '5.0'],
            ['udm_errno', '5.1', [553], '5.0'],
            ['udm_error', '5.1', [554], '5.0'],
            ['udm_find', '5.1', [555], '5.0'],
            ['udm_free_agent', '5.1', [556], '5.0'],
            ['udm_free_ispell_data', '5.1', [557], '5.0'],
            ['udm_free_res', '5.1', [558], '5.0'],
            ['udm_get_doc_count', '5.1', [559], '5.0'],
            ['udm_get_res_field', '5.1', [560], '5.0'],
            ['udm_get_res_param', '5.1', [561], '5.0'],
            ['udm_hash32', '5.1', [562], '5.0'],
            ['udm_load_ispell_data', '5.1', [563], '5.0'],
            ['udm_set_agent_param', '5.1', [564], '5.0'],
            ['w32api_deftype', '5.1', [598], '5.0'],
            ['w32api_init_dtype', '5.1', [599], '5.0'],
            ['w32api_invoke_function', '5.1', [600], '5.0'],
            ['w32api_register_function', '5.1', [601], '5.0'],
            ['w32api_set_call_method', '5.1', [602], '5.0'],
            ['cpdf_add_annotation', '5.1', [605], '5.0'],
            ['cpdf_add_outline', '5.1', [606], '5.0'],
            ['cpdf_arc', '5.1', [607], '5.0'],
            ['cpdf_begin_text', '5.1', [608], '5.0'],
            ['cpdf_circle', '5.1', [609], '5.0'],
            ['cpdf_clip', '5.1', [610], '5.0'],
            ['cpdf_close', '5.1', [611], '5.0'],
            ['cpdf_closepath_fill_stroke', '5.1', [612], '5.0'],
            ['cpdf_closepath_stroke', '5.1', [613], '5.0'],
            ['cpdf_closepath', '5.1', [614], '5.0'],
            ['cpdf_continue_text', '5.1', [615], '5.0'],
            ['cpdf_curveto', '5.1', [616], '5.0'],
            ['cpdf_end_text', '5.1', [617], '5.0'],
            ['cpdf_fill_stroke', '5.1', [618], '5.0'],
            ['cpdf_fill', '5.1', [619], '5.0'],
            ['cpdf_finalize_page', '5.1', [620], '5.0'],
            ['cpdf_finalize', '5.1', [621], '5.0'],
            ['cpdf_global_set_document_limits', '5.1', [622], '5.0'],
            ['cpdf_import_jpeg', '5.1', [623], '5.0'],
            ['cpdf_lineto', '5.1', [624], '5.0'],
            ['cpdf_moveto', '5.1', [625], '5.0'],
            ['cpdf_newpath', '5.1', [626], '5.0'],
            ['cpdf_open', '5.1', [627], '5.0'],
            ['cpdf_output_buffer', '5.1', [628], '5.0'],
            ['cpdf_page_init', '5.1', [629], '5.0'],
            ['cpdf_place_inline_image', '5.1', [630], '5.0'],
            ['cpdf_rect', '5.1', [631], '5.0'],
            ['cpdf_restore', '5.1', [632], '5.0'],
            ['cpdf_rlineto', '5.1', [633], '5.0'],
            ['cpdf_rmoveto', '5.1', [634], '5.0'],
            ['cpdf_rotate_text', '5.1', [635], '5.0'],
            ['cpdf_rotate', '5.1', [636], '5.0'],
            ['cpdf_save_to_file', '5.1', [637], '5.0'],
            ['cpdf_save', '5.1', [638], '5.0'],
            ['cpdf_scale', '5.1', [639], '5.0'],
            ['cpdf_set_action_url', '5.1', [640], '5.0'],
            ['cpdf_set_char_spacing', '5.1', [641], '5.0'],
            ['cpdf_set_creator', '5.1', [642], '5.0'],
            ['cpdf_set_current_page', '5.1', [643], '5.0'],
            ['cpdf_set_font_directories', '5.1', [644], '5.0'],
            ['cpdf_set_font_map_file', '5.1', [645], '5.0'],
            ['cpdf_set_font', '5.1', [646], '5.0'],
            ['cpdf_set_horiz_scaling', '5.1', [647], '5.0'],
            ['cpdf_set_keywords', '5.1', [648], '5.0'],
            ['cpdf_set_leading', '5.1', [649], '5.0'],
            ['cpdf_set_page_animation', '5.1', [650], '5.0'],
            ['cpdf_set_subject', '5.1', [651], '5.0'],
            ['cpdf_set_text_matrix', '5.1', [652], '5.0'],
            ['cpdf_set_text_pos', '5.1', [653], '5.0'],
            ['cpdf_set_text_rendering', '5.1', [654], '5.0'],
            ['cpdf_set_text_rise', '5.1', [655], '5.0'],
            ['cpdf_set_title', '5.1', [656], '5.0'],
            ['cpdf_set_viewer_preferences', '5.1', [657], '5.0'],
            ['cpdf_set_word_spacing', '5.1', [658], '5.0'],
            ['cpdf_setdash', '5.1', [659], '5.0'],
            ['cpdf_setflat', '5.1', [660], '5.0'],
            ['cpdf_setgray_fill', '5.1', [661], '5.0'],
            ['cpdf_setgray_stroke', '5.1', [662], '5.0'],
            ['cpdf_setgray', '5.1', [663], '5.0'],
            ['cpdf_setlinecap', '5.1', [664], '5.0'],
            ['cpdf_setlinejoin', '5.1', [665], '5.0'],
            ['cpdf_setlinewidth', '5.1', [666], '5.0'],
            ['cpdf_setmiterlimit', '5.1', [667], '5.0'],
            ['cpdf_setrgbcolor_fill', '5.1', [668], '5.0'],
            ['cpdf_setrgbcolor_stroke', '5.1', [669], '5.0'],
            ['cpdf_setrgbcolor', '5.1', [670], '5.0'],
            ['cpdf_show_xy', '5.1', [671], '5.0'],
            ['cpdf_show', '5.1', [672], '5.0'],
            ['cpdf_stringwidth', '5.1', [673], '5.0'],
            ['cpdf_stroke', '5.1', [674], '5.0'],
            ['cpdf_text', '5.1', [675], '5.0'],
            ['cpdf_translate', '5.1', [676], '5.0'],
            ['ircg_channel_mode', '5.1', [677], '5.0'],
            ['ircg_disconnect', '5.1', [678], '5.0'],
            ['ircg_eval_ecmascript_params', '5.1', [679], '5.0'],
            ['ircg_fetch_error_msg', '5.1', [680], '5.0'],
            ['ircg_get_username', '5.1', [681], '5.0'],
            ['ircg_html_encode', '5.1', [682], '5.0'],
            ['ircg_ignore_add', '5.1', [683], '5.0'],
            ['ircg_ignore_del', '5.1', [684], '5.0'],
            ['ircg_invite', '5.1', [685], '5.0'],
            ['ircg_is_conn_alive', '5.1', [686], '5.0'],
            ['ircg_join', '5.1', [687], '5.0'],
            ['ircg_kick', '5.1', [688], '5.0'],
            ['ircg_list', '5.1', [689], '5.0'],
            ['ircg_lookup_format_messages', '5.1', [690], '5.0'],
            ['ircg_lusers', '5.1', [691], '5.0'],
            ['ircg_msg', '5.1', [692], '5.0'],
            ['ircg_names', '5.1', [693], '5.0'],
            ['ircg_nick', '5.1', [694], '5.0'],
            ['ircg_nickname_escape', '5.1', [695], '5.0'],
            ['ircg_nickname_unescape', '5.1', [696], '5.0'],
            ['ircg_notice', '5.1', [697], '5.0'],
            ['ircg_oper', '5.1', [698], '5.0'],
            ['ircg_part', '5.1', [699], '5.0'],
            ['ircg_pconnect', '5.1', [700], '5.0'],
            ['ircg_register_format_messages', '5.1', [701], '5.0'],
            ['ircg_set_current', '5.1', [702], '5.0'],
            ['ircg_set_file', '5.1', [703], '5.0'],
            ['ircg_set_on_die', '5.1', [704], '5.0'],
            ['ircg_topic', '5.1', [705], '5.0'],
            ['ircg_who', '5.1', [706], '5.0'],
            ['ircg_whois', '5.1', [707], '5.0'],
            ['dbx_close', '5.1', [720], '5.0'],
            ['dbx_compare', '5.1', [721], '5.0'],
            ['dbx_connect', '5.1', [722], '5.0'],
            ['dbx_error', '5.1', [723], '5.0'],
            ['dbx_escape_string', '5.1', [724], '5.0'],
            ['dbx_fetch_row', '5.1', [725], '5.0'],
            ['dbx_query', '5.1', [726], '5.0'],
            ['dbx_sort', '5.1', [727], '5.0'],
            ['ingres_autocommit', '5.1', [795], '5.0'],
            ['ingres_close', '5.1', [796], '5.0'],
            ['ingres_commit', '5.1', [797], '5.0'],
            ['ingres_connect', '5.1', [798], '5.0'],
            ['ingres_fetch_array', '5.1', [799], '5.0'],
            ['ingres_fetch_object', '5.1', [800], '5.0'],
            ['ingres_fetch_row', '5.1', [801], '5.0'],
            ['ingres_field_length', '5.1', [802], '5.0'],
            ['ingres_field_name', '5.1', [803], '5.0'],
            ['ingres_field_nullable', '5.1', [804], '5.0'],
            ['ingres_field_precision', '5.1', [805], '5.0'],
            ['ingres_field_scale', '5.1', [806], '5.0'],
            ['ingres_field_type', '5.1', [807], '5.0'],
            ['ingres_num_fields', '5.1', [808], '5.0'],
            ['ingres_num_rows', '5.1', [809], '5.0'],
            ['ingres_pconnect', '5.1', [810], '5.0'],
            ['ingres_query', '5.1', [811], '5.0'],
            ['ingres_rollback', '5.1', [812], '5.0'],
            ['ovrimos_close', '5.1', [1036], '5.0'],
            ['ovrimos_commit', '5.1', [1037], '5.0'],
            ['ovrimos_connect', '5.1', [1038], '5.0'],
            ['ovrimos_cursor', '5.1', [1039], '5.0'],
            ['ovrimos_exec', '5.1', [1040], '5.0'],
            ['ovrimos_execute', '5.1', [1041], '5.0'],
            ['ovrimos_fetch_into', '5.1', [1042], '5.0'],
            ['ovrimos_fetch_row', '5.1', [1043], '5.0'],
            ['ovrimos_field_len', '5.1', [1044], '5.0'],
            ['ovrimos_field_name', '5.1', [1045], '5.0'],
            ['ovrimos_field_num', '5.1', [1046], '5.0'],
            ['ovrimos_field_type', '5.1', [1047], '5.0'],
            ['ovrimos_free_result', '5.1', [1048], '5.0'],
            ['ovrimos_longreadlen', '5.1', [1049], '5.0'],
            ['ovrimos_num_fields', '5.1', [1050], '5.0'],
            ['ovrimos_num_rows', '5.1', [1051], '5.0'],
            ['ovrimos_prepare', '5.1', [1052], '5.0'],
            ['ovrimos_result_all', '5.1', [1053], '5.0'],
            ['ovrimos_result', '5.1', [1054], '5.0'],
            ['ovrimos_rollback', '5.1', [1055], '5.0'],
            ['ovrimos_close_all', '5.1', [1056], '5.0'],
            ['ora_bind', '5.1', [1058], '5.0'],
            ['ora_close', '5.1', [1059], '5.0'],
            ['ora_columnname', '5.1', [1060], '5.0'],
            ['ora_columnsize', '5.1', [1061], '5.0'],
            ['ora_columntype', '5.1', [1062], '5.0'],
            ['ora_commit', '5.1', [1063], '5.0'],
            ['ora_commitoff', '5.1', [1064], '5.0'],
            ['ora_commiton', '5.1', [1065], '5.0'],
            ['ora_do', '5.1', [1066], '5.0'],
            ['ora_error', '5.1', [1067], '5.0'],
            ['ora_errorcode', '5.1', [1068], '5.0'],
            ['ora_exec', '5.1', [1069], '5.0'],
            ['ora_fetch_into', '5.1', [1070], '5.0'],
            ['ora_fetch', '5.1', [1071], '5.0'],
            ['ora_getcolumn', '5.1', [1072], '5.0'],
            ['ora_logoff', '5.1', [1073], '5.0'],
            ['ora_logon', '5.1', [1074], '5.0'],
            ['ora_numcols', '5.1', [1075], '5.0'],
            ['ora_numrows', '5.1', [1076], '5.0'],
            ['ora_open', '5.1', [1077], '5.0'],
            ['ora_parse', '5.1', [1078], '5.0'],
            ['ora_plogon', '5.1', [1079], '5.0'],
            ['ora_rollback', '5.1', [1080], '5.0'],
            ['mysqli_embedded_connect', '5.1', [1146], '5.0'],
            ['mysqli_server_end', '5.1', [1155], '5.0'],
            ['mysqli_server_init', '5.1', [1156], '5.0'],

            ['msession_connect', '5.1.3', [567], '5.1', '5.2'],
            ['msession_count', '5.1.3', [568], '5.1', '5.2'],
            ['msession_create', '5.1.3', [569], '5.1', '5.2'],
            ['msession_destroy', '5.1.3', [570], '5.1', '5.2'],
            ['msession_disconnect', '5.1.3', [571], '5.1', '5.2'],
            ['msession_find', '5.1.3', [572], '5.1', '5.2'],
            ['msession_get_array', '5.1.3', [573], '5.1', '5.2'],
            ['msession_get_data', '5.1.3', [574], '5.1', '5.2'],
            ['msession_get', '5.1.3', [575], '5.1', '5.2'],
            ['msession_inc', '5.1.3', [576], '5.1', '5.2'],
            ['msession_list', '5.1.3', [577], '5.1', '5.2'],
            ['msession_listvar', '5.1.3', [578], '5.1', '5.2'],
            ['msession_lock', '5.1.3', [579], '5.1', '5.2'],
            ['msession_plugin', '5.1.3', [580], '5.1', '5.2'],
            ['msession_randstr', '5.1.3', [581], '5.1', '5.2'],
            ['msession_set_array', '5.1.3', [582], '5.1', '5.2'],
            ['msession_set_data', '5.1.3', [583], '5.1', '5.2'],
            ['msession_set', '5.1.3', [584], '5.1', '5.2'],
            ['msession_timeout', '5.1.3', [585], '5.1', '5.2'],
            ['msession_uniq', '5.1.3', [586], '5.1', '5.2'],
            ['msession_unlock', '5.1.3', [587], '5.1', '5.2'],

            ['mysqli_resource', '5.1.4', [1152], '5.1', '5.2'],

            ['mysql_createdb', '5.1.7', [975], '5.1', '5.2'],
            ['mysql_dropdb', '5.1.7', [976], '5.1', '5.2'],
            ['mysql_listtables', '5.1.7', [981], '5.1', '5.2'],

            ['hwapi_attribute_new', '5.2', [526], '5.1'],
            ['hwapi_content_new', '5.2', [527], '5.1'],
            ['hwapi_hgcsp', '5.2', [528], '5.1'],
            ['hwapi_object_new', '5.2', [529], '5.1'],
            ['filepro_fieldcount', '5.2', [788], '5.1'],
            ['filepro_fieldname', '5.2', [789], '5.1'],
            ['filepro_fieldtype', '5.2', [790], '5.1'],
            ['filepro_fieldwidth', '5.2', [791], '5.1'],
            ['filepro_retrieve', '5.2', [792], '5.1'],
            ['filepro_rowcount', '5.2', [793], '5.1'],
            ['filepro', '5.2', [794], '5.1'],

            ['ifx_affected_rows', '5.2.1', [1106], '5.2', '5.3'],
            ['ifx_blobinfile_mode', '5.2.1', [1107], '5.2', '5.3'],
            ['ifx_byteasvarchar', '5.2.1', [1108], '5.2', '5.3'],
            ['ifx_close', '5.2.1', [1109], '5.2', '5.3'],
            ['ifx_connect', '5.2.1', [1110], '5.2', '5.3'],
            ['ifx_copy_blob', '5.2.1', [1111], '5.2', '5.3'],
            ['ifx_create_blob', '5.2.1', [1112], '5.2', '5.3'],
            ['ifx_create_char', '5.2.1', [1113], '5.2', '5.3'],
            ['ifx_do', '5.2.1', [1114], '5.2', '5.3'],
            ['ifx_error', '5.2.1', [1115], '5.2', '5.3'],
            ['ifx_errormsg', '5.2.1', [1116], '5.2', '5.3'],
            ['ifx_fetch_row', '5.2.1', [1117], '5.2', '5.3'],
            ['ifx_fieldproperties', '5.2.1', [1118], '5.2', '5.3'],
            ['ifx_fieldtypes', '5.2.1', [1119], '5.2', '5.3'],
            ['ifx_free_blob', '5.2.1', [1120], '5.2', '5.3'],
            ['ifx_free_char', '5.2.1', [1121], '5.2', '5.3'],
            ['ifx_free_result', '5.2.1', [1122], '5.2', '5.3'],
            ['ifx_get_blob', '5.2.1', [1123], '5.2', '5.3'],
            ['ifx_get_char', '5.2.1', [1124], '5.2', '5.3'],
            ['ifx_getsqlca', '5.2.1', [1125], '5.2', '5.3'],
            ['ifx_htmltbl_result', '5.2.1', [1126], '5.2', '5.3'],
            ['ifx_nullformat', '5.2.1', [1127], '5.2', '5.3'],
            ['ifx_num_fields', '5.2.1', [1128], '5.2', '5.3'],
            ['ifx_num_rows', '5.2.1', [1129], '5.2', '5.3'],
            ['ifx_pconnect', '5.2.1', [1130], '5.2', '5.3'],
            ['ifx_prepare', '5.2.1', [1131], '5.2', '5.3'],
            ['ifx_query', '5.2.1', [1132], '5.2', '5.3'],
            ['ifx_textasvarchar', '5.2.1', [1133], '5.2', '5.3'],
            ['ifx_update_blob', '5.2.1', [1134], '5.2', '5.3'],
            ['ifx_update_char', '5.2.1', [1135], '5.2', '5.3'],
            ['ifxus_close_slob', '5.2.1', [1136], '5.2', '5.3'],
            ['ifxus_create_slob', '5.2.1', [1137], '5.2', '5.3'],
            ['ifxus_free_slob', '5.2.1', [1138], '5.2', '5.3'],
            ['ifxus_open_slob', '5.2.1', [1139], '5.2', '5.3'],
            ['ifxus_read_slob', '5.2.1', [1140], '5.2', '5.3'],
            ['ifxus_seek_slob', '5.2.1', [1141], '5.2', '5.3'],
            ['ifxus_tell_slob', '5.2.1', [1142], '5.2', '5.3'],
            ['ifxus_write_slob', '5.2.1', [1143], '5.2', '5.3'],

            ['ncurses_addch', '5.3', [255], '5.2'],
            ['ncurses_addchnstr', '5.3', [256], '5.2'],
            ['ncurses_addchstr', '5.3', [257], '5.2'],
            ['ncurses_addnstr', '5.3', [258], '5.2'],
            ['ncurses_addstr', '5.3', [259], '5.2'],
            ['ncurses_assume_default_colors', '5.3', [260], '5.2'],
            ['ncurses_attroff', '5.3', [261], '5.2'],
            ['ncurses_attron', '5.3', [262], '5.2'],
            ['ncurses_attrset', '5.3', [263], '5.2'],
            ['ncurses_baudrate', '5.3', [264], '5.2'],
            ['ncurses_beep', '5.3', [265], '5.2'],
            ['ncurses_bkgd', '5.3', [266], '5.2'],
            ['ncurses_bkgdset', '5.3', [267], '5.2'],
            ['ncurses_border', '5.3', [268], '5.2'],
            ['ncurses_bottom_panel', '5.3', [269], '5.2'],
            ['ncurses_can_change_color', '5.3', [270], '5.2'],
            ['ncurses_cbreak', '5.3', [271], '5.2'],
            ['ncurses_clear', '5.3', [272], '5.2'],
            ['ncurses_clrtobot', '5.3', [273], '5.2'],
            ['ncurses_clrtoeol', '5.3', [274], '5.2'],
            ['ncurses_color_content', '5.3', [275], '5.2'],
            ['ncurses_color_set', '5.3', [276], '5.2'],
            ['ncurses_curs_set', '5.3', [277], '5.2'],
            ['ncurses_def_prog_mode', '5.3', [278], '5.2'],
            ['ncurses_def_shell_mode', '5.3', [279], '5.2'],
            ['ncurses_define_key', '5.3', [280], '5.2'],
            ['ncurses_del_panel', '5.3', [281], '5.2'],
            ['ncurses_delay_output', '5.3', [282], '5.2'],
            ['ncurses_delch', '5.3', [283], '5.2'],
            ['ncurses_deleteln', '5.3', [284], '5.2'],
            ['ncurses_delwin', '5.3', [285], '5.2'],
            ['ncurses_doupdate', '5.3', [286], '5.2'],
            ['ncurses_echo', '5.3', [287], '5.2'],
            ['ncurses_echochar', '5.3', [288], '5.2'],
            ['ncurses_end', '5.3', [289], '5.2'],
            ['ncurses_erase', '5.3', [290], '5.2'],
            ['ncurses_erasechar', '5.3', [291], '5.2'],
            ['ncurses_filter', '5.3', [292], '5.2'],
            ['ncurses_flash', '5.3', [293], '5.2'],
            ['ncurses_flushinp', '5.3', [294], '5.2'],
            ['ncurses_getch', '5.3', [295], '5.2'],
            ['ncurses_getmaxyx', '5.3', [296], '5.2'],
            ['ncurses_getmouse', '5.3', [297], '5.2'],
            ['ncurses_getyx', '5.3', [298], '5.2'],
            ['ncurses_halfdelay', '5.3', [299], '5.2'],
            ['ncurses_has_colors', '5.3', [300], '5.2'],
            ['ncurses_has_ic', '5.3', [301], '5.2'],
            ['ncurses_has_il', '5.3', [302], '5.2'],
            ['ncurses_has_key', '5.3', [303], '5.2'],
            ['ncurses_hide_panel', '5.3', [304], '5.2'],
            ['ncurses_hline', '5.3', [305], '5.2'],
            ['ncurses_inch', '5.3', [306], '5.2'],
            ['ncurses_init_color', '5.3', [307], '5.2'],
            ['ncurses_init_pair', '5.3', [308], '5.2'],
            ['ncurses_init', '5.3', [309], '5.2'],
            ['ncurses_insch', '5.3', [310], '5.2'],
            ['ncurses_insdelln', '5.3', [311], '5.2'],
            ['ncurses_insertln', '5.3', [312], '5.2'],
            ['ncurses_insstr', '5.3', [313], '5.2'],
            ['ncurses_instr', '5.3', [314], '5.2'],
            ['ncurses_isendwin', '5.3', [315], '5.2'],
            ['ncurses_keyok', '5.3', [316], '5.2'],
            ['ncurses_keypad', '5.3', [317], '5.2'],
            ['ncurses_killchar', '5.3', [318], '5.2'],
            ['ncurses_longname', '5.3', [319], '5.2'],
            ['ncurses_meta', '5.3', [320], '5.2'],
            ['ncurses_mouse_trafo', '5.3', [321], '5.2'],
            ['ncurses_mouseinterval', '5.3', [322], '5.2'],
            ['ncurses_mousemask', '5.3', [323], '5.2'],
            ['ncurses_move_panel', '5.3', [324], '5.2'],
            ['ncurses_move', '5.3', [325], '5.2'],
            ['ncurses_mvaddch', '5.3', [326], '5.2'],
            ['ncurses_mvaddchnstr', '5.3', [327], '5.2'],
            ['ncurses_mvaddchstr', '5.3', [328], '5.2'],
            ['ncurses_mvaddnstr', '5.3', [329], '5.2'],
            ['ncurses_mvaddstr', '5.3', [330], '5.2'],
            ['ncurses_mvcur', '5.3', [331], '5.2'],
            ['ncurses_mvdelch', '5.3', [332], '5.2'],
            ['ncurses_mvgetch', '5.3', [333], '5.2'],
            ['ncurses_mvhline', '5.3', [334], '5.2'],
            ['ncurses_mvinch', '5.3', [335], '5.2'],
            ['ncurses_mvvline', '5.3', [336], '5.2'],
            ['ncurses_mvwaddstr', '5.3', [337], '5.2'],
            ['ncurses_napms', '5.3', [338], '5.2'],
            ['ncurses_new_panel', '5.3', [339], '5.2'],
            ['ncurses_newpad', '5.3', [340], '5.2'],
            ['ncurses_newwin', '5.3', [341], '5.2'],
            ['ncurses_nl', '5.3', [342], '5.2'],
            ['ncurses_nocbreak', '5.3', [343], '5.2'],
            ['ncurses_noecho', '5.3', [344], '5.2'],
            ['ncurses_nonl', '5.3', [345], '5.2'],
            ['ncurses_noqiflush', '5.3', [346], '5.2'],
            ['ncurses_noraw', '5.3', [347], '5.2'],
            ['ncurses_pair_content', '5.3', [348], '5.2'],
            ['ncurses_panel_above', '5.3', [349], '5.2'],
            ['ncurses_panel_below', '5.3', [350], '5.2'],
            ['ncurses_panel_window', '5.3', [351], '5.2'],
            ['ncurses_pnoutrefresh', '5.3', [352], '5.2'],
            ['ncurses_prefresh', '5.3', [353], '5.2'],
            ['ncurses_putp', '5.3', [354], '5.2'],
            ['ncurses_qiflush', '5.3', [355], '5.2'],
            ['ncurses_raw', '5.3', [356], '5.2'],
            ['ncurses_refresh', '5.3', [357], '5.2'],
            ['ncurses_replace_panel', '5.3', [358], '5.2'],
            ['ncurses_reset_prog_mode', '5.3', [359], '5.2'],
            ['ncurses_reset_shell_mode', '5.3', [360], '5.2'],
            ['ncurses_resetty', '5.3', [361], '5.2'],
            ['ncurses_savetty', '5.3', [362], '5.2'],
            ['ncurses_scr_dump', '5.3', [363], '5.2'],
            ['ncurses_scr_init', '5.3', [364], '5.2'],
            ['ncurses_scr_restore', '5.3', [365], '5.2'],
            ['ncurses_scr_set', '5.3', [366], '5.2'],
            ['ncurses_scrl', '5.3', [367], '5.2'],
            ['ncurses_show_panel', '5.3', [368], '5.2'],
            ['ncurses_slk_attr', '5.3', [369], '5.2'],
            ['ncurses_slk_attroff', '5.3', [370], '5.2'],
            ['ncurses_slk_attron', '5.3', [371], '5.2'],
            ['ncurses_slk_attrset', '5.3', [372], '5.2'],
            ['ncurses_slk_clear', '5.3', [373], '5.2'],
            ['ncurses_slk_color', '5.3', [374], '5.2'],
            ['ncurses_slk_init', '5.3', [375], '5.2'],
            ['ncurses_slk_noutrefresh', '5.3', [376], '5.2'],
            ['ncurses_slk_refresh', '5.3', [377], '5.2'],
            ['ncurses_slk_restore', '5.3', [378], '5.2'],
            ['ncurses_slk_set', '5.3', [379], '5.2'],
            ['ncurses_slk_touch', '5.3', [380], '5.2'],
            ['ncurses_standend', '5.3', [381], '5.2'],
            ['ncurses_standout', '5.3', [382], '5.2'],
            ['ncurses_start_color', '5.3', [383], '5.2'],
            ['ncurses_termattrs', '5.3', [384], '5.2'],
            ['ncurses_termname', '5.3', [385], '5.2'],
            ['ncurses_timeout', '5.3', [386], '5.2'],
            ['ncurses_top_panel', '5.3', [387], '5.2'],
            ['ncurses_typeahead', '5.3', [388], '5.2'],
            ['ncurses_ungetch', '5.3', [389], '5.2'],
            ['ncurses_ungetmouse', '5.3', [390], '5.2'],
            ['ncurses_update_panels', '5.3', [391], '5.2'],
            ['ncurses_use_default_colors', '5.3', [392], '5.2'],
            ['ncurses_use_env', '5.3', [393], '5.2'],
            ['ncurses_use_extended_names', '5.3', [394], '5.2'],
            ['ncurses_vidattr', '5.3', [395], '5.2'],
            ['ncurses_vline', '5.3', [396], '5.2'],
            ['ncurses_waddch', '5.3', [397], '5.2'],
            ['ncurses_waddstr', '5.3', [398], '5.2'],
            ['ncurses_wattroff', '5.3', [399], '5.2'],
            ['ncurses_wattron', '5.3', [400], '5.2'],
            ['ncurses_wattrset', '5.3', [401], '5.2'],
            ['ncurses_wborder', '5.3', [402], '5.2'],
            ['ncurses_wclear', '5.3', [403], '5.2'],
            ['ncurses_wcolor_set', '5.3', [404], '5.2'],
            ['ncurses_werase', '5.3', [405], '5.2'],
            ['ncurses_wgetch', '5.3', [406], '5.2'],
            ['ncurses_whline', '5.3', [407], '5.2'],
            ['ncurses_wmouse_trafo', '5.3', [408], '5.2'],
            ['ncurses_wmove', '5.3', [409], '5.2'],
            ['ncurses_wnoutrefresh', '5.3', [410], '5.2'],
            ['ncurses_wrefresh', '5.3', [411], '5.2'],
            ['ncurses_wstandend', '5.3', [412], '5.2'],
            ['ncurses_wstandout', '5.3', [413], '5.2'],
            ['ncurses_wvline', '5.3', [414], '5.2'],
            ['fdf_add_doc_javascript', '5.3', [469], '5.2'],
            ['fdf_add_template', '5.3', [470], '5.2'],
            ['fdf_close', '5.3', [471], '5.2'],
            ['fdf_create', '5.3', [472], '5.2'],
            ['fdf_enum_values', '5.3', [473], '5.2'],
            ['fdf_errno', '5.3', [474], '5.2'],
            ['fdf_error', '5.3', [475], '5.2'],
            ['fdf_get_ap', '5.3', [476], '5.2'],
            ['fdf_get_attachment', '5.3', [477], '5.2'],
            ['fdf_get_encoding', '5.3', [478], '5.2'],
            ['fdf_get_file', '5.3', [479], '5.2'],
            ['fdf_get_flags', '5.3', [480], '5.2'],
            ['fdf_get_opt', '5.3', [481], '5.2'],
            ['fdf_get_status', '5.3', [482], '5.2'],
            ['fdf_get_value', '5.3', [483], '5.2'],
            ['fdf_get_version', '5.3', [484], '5.2'],
            ['fdf_header', '5.3', [485], '5.2'],
            ['fdf_next_field_name', '5.3', [486], '5.2'],
            ['fdf_open_string', '5.3', [487], '5.2'],
            ['fdf_open', '5.3', [488], '5.2'],
            ['fdf_remove_item', '5.3', [489], '5.2'],
            ['fdf_save_string', '5.3', [490], '5.2'],
            ['fdf_save', '5.3', [491], '5.2'],
            ['fdf_set_ap', '5.3', [492], '5.2'],
            ['fdf_set_encoding', '5.3', [493], '5.2'],
            ['fdf_set_file', '5.3', [494], '5.2'],
            ['fdf_set_flags', '5.3', [495], '5.2'],
            ['fdf_set_javascript_action', '5.3', [496], '5.2'],
            ['fdf_set_on_import_javascript', '5.3', [497], '5.2'],
            ['fdf_set_opt', '5.3', [498], '5.2'],
            ['fdf_set_status', '5.3', [499], '5.2'],
            ['fdf_set_submit_form_action', '5.3', [500], '5.2'],
            ['fdf_set_target_frame', '5.3', [501], '5.2'],
            ['fdf_set_value', '5.3', [502], '5.2'],
            ['fdf_set_version', '5.3', [503], '5.2'],
            ['ming_keypress', '5.3', [506], '5.2'],
            ['ming_setcubicthreshold', '5.3', [507], '5.2'],
            ['ming_setscale', '5.3', [508], '5.2'],
            ['ming_setswfcompression', '5.3', [509], '5.2'],
            ['ming_useconstants', '5.3', [510], '5.2'],
            ['ming_useswfversion', '5.3', [511], '5.2'],
            ['dbase_add_record', '5.3', [708], '5.2'],
            ['dbase_close', '5.3', [709], '5.2'],
            ['dbase_create', '5.3', [710], '5.2'],
            ['dbase_delete_record', '5.3', [711], '5.2'],
            ['dbase_get_header_info', '5.3', [712], '5.2'],
            ['dbase_get_record_with_names', '5.3', [713], '5.2'],
            ['dbase_get_record', '5.3', [714], '5.2'],
            ['dbase_numfields', '5.3', [715], '5.2'],
            ['dbase_numrecords', '5.3', [716], '5.2'],
            ['dbase_open', '5.3', [717], '5.2'],
            ['dbase_pack', '5.3', [718], '5.2'],
            ['dbase_replace_record', '5.3', [719], '5.2'],
            ['fbsql_affected_rows', '5.3', [728], '5.2'],
            ['fbsql_autocommit', '5.3', [729], '5.2'],
            ['fbsql_blob_size', '5.3', [730], '5.2'],
            ['fbsql_change_user', '5.3', [731], '5.2'],
            ['fbsql_clob_size', '5.3', [732], '5.2'],
            ['fbsql_close', '5.3', [733], '5.2'],
            ['fbsql_commit', '5.3', [734], '5.2'],
            ['fbsql_connect', '5.3', [735], '5.2'],
            ['fbsql_create_blob', '5.3', [736], '5.2'],
            ['fbsql_create_clob', '5.3', [737], '5.2'],
            ['fbsql_create_db', '5.3', [738], '5.2'],
            ['fbsql_data_seek', '5.3', [739], '5.2'],
            ['fbsql_database_password', '5.3', [740], '5.2'],
            ['fbsql_database', '5.3', [741], '5.2'],
            ['fbsql_db_query', '5.3', [742], '5.2'],
            ['fbsql_db_status', '5.3', [743], '5.2'],
            ['fbsql_drop_db', '5.3', [744], '5.2'],
            ['fbsql_errno', '5.3', [745], '5.2'],
            ['fbsql_error', '5.3', [746], '5.2'],
            ['fbsql_fetch_array', '5.3', [747], '5.2'],
            ['fbsql_fetch_assoc', '5.3', [748], '5.2'],
            ['fbsql_fetch_field', '5.3', [749], '5.2'],
            ['fbsql_fetch_lengths', '5.3', [750], '5.2'],
            ['fbsql_fetch_object', '5.3', [751], '5.2'],
            ['fbsql_fetch_row', '5.3', [752], '5.2'],
            ['fbsql_field_flags', '5.3', [753], '5.2'],
            ['fbsql_field_len', '5.3', [754], '5.2'],
            ['fbsql_field_name', '5.3', [755], '5.2'],
            ['fbsql_field_seek', '5.3', [756], '5.2'],
            ['fbsql_field_table', '5.3', [757], '5.2'],
            ['fbsql_field_type', '5.3', [758], '5.2'],
            ['fbsql_free_result', '5.3', [759], '5.2'],
            ['fbsql_get_autostart_info', '5.3', [760], '5.2'],
            ['fbsql_hostname', '5.3', [761], '5.2'],
            ['fbsql_insert_id', '5.3', [762], '5.2'],
            ['fbsql_list_dbs', '5.3', [763], '5.2'],
            ['fbsql_list_fields', '5.3', [764], '5.2'],
            ['fbsql_list_tables', '5.3', [765], '5.2'],
            ['fbsql_next_result', '5.3', [766], '5.2'],
            ['fbsql_num_fields', '5.3', [767], '5.2'],
            ['fbsql_num_rows', '5.3', [768], '5.2'],
            ['fbsql_password', '5.3', [769], '5.2'],
            ['fbsql_pconnect', '5.3', [770], '5.2'],
            ['fbsql_query', '5.3', [771], '5.2'],
            ['fbsql_read_blob', '5.3', [772], '5.2'],
            ['fbsql_read_clob', '5.3', [773], '5.2'],
            ['fbsql_result', '5.3', [774], '5.2'],
            ['fbsql_rollback', '5.3', [775], '5.2'],
            ['fbsql_rows_fetched', '5.3', [776], '5.2'],
            ['fbsql_select_db', '5.3', [777], '5.2'],
            ['fbsql_set_characterset', '5.3', [778], '5.2'],
            ['fbsql_set_lob_mode', '5.3', [779], '5.2'],
            ['fbsql_set_password', '5.3', [780], '5.2'],
            ['fbsql_set_transaction', '5.3', [781], '5.2'],
            ['fbsql_start_db', '5.3', [782], '5.2'],
            ['fbsql_stop_db', '5.3', [783], '5.2'],
            ['fbsql_table_name', '5.3', [784], '5.2'],
            ['fbsql_tablename', '5.3', [785], '5.2'],
            ['fbsql_username', '5.3', [786], '5.2'],
            ['fbsql_warnings', '5.3', [787], '5.2'],
            ['msql_affected_rows', '5.3', [813], '5.2'],
            ['msql_close', '5.3', [814], '5.2'],
            ['msql_connect', '5.3', [815], '5.2'],
            ['msql_create_db', '5.3', [816], '5.2'],
            ['msql_createdb', '5.3', [817], '5.2'],
            ['msql_data_seek', '5.3', [818], '5.2'],
            ['msql_db_query', '5.3', [819], '5.2'],
            ['msql_dbname', '5.3', [820], '5.2'],
            ['msql_drop_db', '5.3', [821], '5.2'],
            ['msql_error', '5.3', [822], '5.2'],
            ['msql_fetch_array', '5.3', [823], '5.2'],
            ['msql_fetch_field', '5.3', [824], '5.2'],
            ['msql_fetch_object', '5.3', [825], '5.2'],
            ['msql_fetch_row', '5.3', [826], '5.2'],
            ['msql_field_flags', '5.3', [827], '5.2'],
            ['msql_field_len', '5.3', [828], '5.2'],
            ['msql_field_name', '5.3', [829], '5.2'],
            ['msql_field_seek', '5.3', [830], '5.2'],
            ['msql_field_table', '5.3', [831], '5.2'],
            ['msql_field_type', '5.3', [832], '5.2'],
            ['msql_fieldflags', '5.3', [833], '5.2'],
            ['msql_fieldlen', '5.3', [834], '5.2'],
            ['msql_fieldname', '5.3', [835], '5.2'],
            ['msql_fieldtable', '5.3', [836], '5.2'],
            ['msql_fieldtype', '5.3', [837], '5.2'],
            ['msql_free_result', '5.3', [838], '5.2'],
            ['msql_list_dbs', '5.3', [839], '5.2'],
            ['msql_list_fields', '5.3', [840], '5.2'],
            ['msql_list_tables', '5.3', [841], '5.2'],
            ['msql_num_fields', '5.3', [842], '5.2'],
            ['msql_num_rows', '5.3', [843], '5.2'],
            ['msql_numfields', '5.3', [844], '5.2'],
            ['msql_numrows', '5.3', [845], '5.2'],
            ['msql_pconnect', '5.3', [846], '5.2'],
            ['msql_query', '5.3', [847], '5.2'],
            ['msql_regcase', '5.3', [848], '5.2'],
            ['msql_result', '5.3', [849], '5.2'],
            ['msql_select_db', '5.3', [850], '5.2'],
            ['msql_tablename', '5.3', [851], '5.2'],
            ['msql', '5.3', [852], '5.2'],
            ['mysqli_disable_reads_from_master', '5.3', [1144], '5.2'],
            ['mysqli_disable_rpl_parse', '5.3', [1145], '5.2'],
            ['mysqli_enable_reads_from_master', '5.3', [1149], '5.2'],
            ['mysqli_enable_rpl_parse', '5.3', [1150], '5.2'],
            ['mysqli_master_query', '5.3', [1151], '5.2'],
            ['mysqli_rpl_parse_enabled', '5.3', [1153], '5.2'],
            ['mysqli_rpl_probe', '5.3', [1154], '5.2'],
            ['mysqli_slave_query', '5.3', [1157], '5.2'],

            ['sqlite_array_query', '5.4', [883], '5.3'],
            ['sqlite_busy_timeout', '5.4', [884], '5.3'],
            ['sqlite_changes', '5.4', [885], '5.3'],
            ['sqlite_close', '5.4', [886], '5.3'],
            ['sqlite_column', '5.4', [887], '5.3'],
            ['sqlite_create_aggregate', '5.4', [888], '5.3'],
            ['sqlite_create_function', '5.4', [889], '5.3'],
            ['sqlite_current', '5.4', [890], '5.3'],
            ['sqlite_error_string', '5.4', [891], '5.3'],
            ['sqlite_escape_string', '5.4', [892], '5.3'],
            ['sqlite_exec', '5.4', [893], '5.3'],
            ['sqlite_factory', '5.4', [894], '5.3'],
            ['sqlite_fetch_all', '5.4', [895], '5.3'],
            ['sqlite_fetch_array', '5.4', [896], '5.3'],
            ['sqlite_fetch_column_types', '5.4', [897], '5.3'],
            ['sqlite_fetch_object', '5.4', [898], '5.3'],
            ['sqlite_fetch_single', '5.4', [899], '5.3'],
            ['sqlite_fetch_string', '5.4', [900], '5.3'],
            ['sqlite_field_name', '5.4', [901], '5.3'],
            ['sqlite_has_more', '5.4', [902], '5.3'],
            ['sqlite_has_prev', '5.4', [903], '5.3'],
            ['sqlite_key', '5.4', [904], '5.3'],
            ['sqlite_last_error', '5.4', [905], '5.3'],
            ['sqlite_last_insert_rowid', '5.4', [906], '5.3'],
            ['sqlite_libencoding', '5.4', [907], '5.3'],
            ['sqlite_libversion', '5.4', [908], '5.3'],
            ['sqlite_next', '5.4', [909], '5.3'],
            ['sqlite_num_fields', '5.4', [910], '5.3'],
            ['sqlite_num_rows', '5.4', [911], '5.3'],
            ['sqlite_open', '5.4', [912], '5.3'],
            ['sqlite_popen', '5.4', [913], '5.3'],
            ['sqlite_prev', '5.4', [914], '5.3'],
            ['sqlite_query', '5.4', [915], '5.3'],
            ['sqlite_rewind', '5.4', [916], '5.3'],
            ['sqlite_seek', '5.4', [917], '5.3'],
            ['sqlite_single_query', '5.4', [918], '5.3'],
            ['sqlite_udf_decode_binary', '5.4', [919], '5.3'],
            ['sqlite_udf_encode_binary', '5.4', [920], '5.3'],
            ['sqlite_unbuffered_query', '5.4', [921], '5.3'],
            ['sqlite_valid', '5.4', [922], '5.3'],

            ['php_logo_guid', '5.5', [32], '5.4'],
            ['php_egg_logo_guid', '5.5', [33], '5.4'],
            ['php_real_logo_guid', '5.5', [34], '5.4'],
            ['imagepsbbox', '7.0', [90], '5.6'],
            ['imagepsencodefont', '7.0', [91], '5.6'],
            ['imagepsextendfont', '7.0', [92], '5.6'],
            ['imagepsfreefont', '7.0', [93], '5.6'],
            ['imagepsloadfont', '7.0', [94], '5.6'],
            ['imagepsslantfont', '7.0', [95], '5.6'],
            ['imagepstext', '7.0', [96], '5.6'],
            ['mssql_bind', '7.0', [853], '5.6'],
            ['mssql_close', '7.0', [854], '5.6'],
            ['mssql_connect', '7.0', [855], '5.6'],
            ['mssql_data_seek', '7.0', [856], '5.6'],
            ['mssql_execute', '7.0', [857], '5.6'],
            ['mssql_fetch_array', '7.0', [858], '5.6'],
            ['mssql_fetch_assoc', '7.0', [859], '5.6'],
            ['mssql_fetch_batch', '7.0', [860], '5.6'],
            ['mssql_fetch_field', '7.0', [861], '5.6'],
            ['mssql_fetch_object', '7.0', [862], '5.6'],
            ['mssql_fetch_row', '7.0', [863], '5.6'],
            ['mssql_field_length', '7.0', [864], '5.6'],
            ['mssql_field_name', '7.0', [865], '5.6'],
            ['mssql_field_seek', '7.0', [866], '5.6'],
            ['mssql_field_type', '7.0', [867], '5.6'],
            ['mssql_free_result', '7.0', [868], '5.6'],
            ['mssql_free_statement', '7.0', [869], '5.6'],
            ['mssql_get_last_message', '7.0', [870], '5.6'],
            ['mssql_guid_string', '7.0', [871], '5.6'],
            ['mssql_init', '7.0', [872], '5.6'],
            ['mssql_min_error_severity', '7.0', [873], '5.6'],
            ['mssql_min_message_severity', '7.0', [874], '5.6'],
            ['mssql_next_result', '7.0', [875], '5.6'],
            ['mssql_num_fields', '7.0', [876], '5.6'],
            ['mssql_num_rows', '7.0', [877], '5.6'],
            ['mssql_pconnect', '7.0', [878], '5.6'],
            ['mssql_query', '7.0', [879], '5.6'],
            ['mssql_result', '7.0', [880], '5.6'],
            ['mssql_rows_affected', '7.0', [881], '5.6'],
            ['mssql_select_db', '7.0', [882], '5.6'],
            ['sybase_affected_rows', '7.0', [1081], '5.6'],
            ['sybase_close', '7.0', [1082], '5.6'],
            ['sybase_connect', '7.0', [1083], '5.6'],
            ['sybase_data_seek', '7.0', [1084], '5.6'],
            ['sybase_deadlock_retry_count', '7.0', [1085], '5.6'],
            ['sybase_fetch_array', '7.0', [1086], '5.6'],
            ['sybase_fetch_assoc', '7.0', [1087], '5.6'],
            ['sybase_fetch_field', '7.0', [1088], '5.6'],
            ['sybase_fetch_object', '7.0', [1089], '5.6'],
            ['sybase_fetch_row', '7.0', [1090], '5.6'],
            ['sybase_field_seek', '7.0', [1091], '5.6'],
            ['sybase_free_result', '7.0', [1092], '5.6'],
            ['sybase_get_last_message', '7.0', [1093], '5.6'],
            ['sybase_min_client_severity', '7.0', [1094], '5.6'],
            ['sybase_min_error_severity', '7.0', [1095], '5.6'],
            ['sybase_min_message_severity', '7.0', [1096], '5.6'],
            ['sybase_min_server_severity', '7.0', [1097], '5.6'],
            ['sybase_num_fields', '7.0', [1098], '5.6'],
            ['sybase_num_rows', '7.0', [1099], '5.6'],
            ['sybase_pconnect', '7.0', [1100], '5.6'],
            ['sybase_query', '7.0', [1101], '5.6'],
            ['sybase_result', '7.0', [1102], '5.6'],
            ['sybase_select_db', '7.0', [1103], '5.6'],
            ['sybase_set_message_handler', '7.0', [1104], '5.6'],
            ['sybase_unbuffered_query', '7.0', [1105], '5.6'],

            ['php_check_syntax', '5.0.5', [98], '5.0', '5.1'],
            ['mysqli_get_cache_stats', '5.4', [99], '5.3'],
            ['ibase_add_user', '7.4', [171], '7.3'],
            ['ibase_affected_rows', '7.4', [172], '7.3'],
            ['ibase_backup', '7.4', [173], '7.3'],
            ['ibase_blob_add', '7.4', [174], '7.3'],
            ['ibase_blob_cancel', '7.4', [175], '7.3'],
            ['ibase_blob_close', '7.4', [176], '7.3'],
            ['ibase_blob_create', '7.4', [177], '7.3'],
            ['ibase_blob_echo', '7.4', [178], '7.3'],
            ['ibase_blob_get', '7.4', [179], '7.3'],
            ['ibase_blob_import', '7.4', [180], '7.3'],
            ['ibase_blob_info', '7.4', [181], '7.3'],
            ['ibase_blob_open', '7.4', [182], '7.3'],
            ['ibase_close', '7.4', [183], '7.3'],
            ['ibase_commit_ret', '7.4', [184], '7.3'],
            ['ibase_commit', '7.4', [185], '7.3'],
            ['ibase_connect', '7.4', [186], '7.3'],
            ['ibase_db_info', '7.4', [187], '7.3'],
            ['ibase_delete_user', '7.4', [188], '7.3'],
            ['ibase_drop_db', '7.4', [189], '7.3'],
            ['ibase_errcode', '7.4', [190], '7.3'],
            ['ibase_errmsg', '7.4', [191], '7.3'],
            ['ibase_execute', '7.4', [192], '7.3'],
            ['ibase_fetch_assoc', '7.4', [193], '7.3'],
            ['ibase_fetch_object', '7.4', [194], '7.3'],
            ['ibase_fetch_row', '7.4', [195], '7.3'],
            ['ibase_field_info', '7.4', [196], '7.3'],
            ['ibase_free_event_handler', '7.4', [197], '7.3'],
            ['ibase_free_query', '7.4', [198], '7.3'],
            ['ibase_free_result', '7.4', [199], '7.3'],
            ['ibase_gen_id', '7.4', [200], '7.3'],
            ['ibase_maintain_db', '7.4', [201], '7.3'],
            ['ibase_modify_user', '7.4', [202], '7.3'],
            ['ibase_name_result', '7.4', [203], '7.3'],
            ['ibase_num_fields', '7.4', [204], '7.3'],
            ['ibase_num_params', '7.4', [205], '7.3'],
            ['ibase_param_info', '7.4', [206], '7.3'],
            ['ibase_pconnect', '7.4', [207], '7.3'],
            ['ibase_prepare', '7.4', [208], '7.3'],
            ['ibase_query', '7.4', [209], '7.3'],
            ['ibase_restore', '7.4', [210], '7.3'],
            ['ibase_rollback_ret', '7.4', [211], '7.3'],
            ['ibase_rollback', '7.4', [212], '7.3'],
            ['ibase_server_info', '7.4', [213], '7.3'],
            ['ibase_service_attach', '7.4', [214], '7.3'],
            ['ibase_service_detach', '7.4', [215], '7.3'],
            ['ibase_set_event_handler', '7.4', [216], '7.3'],
            ['ibase_trans', '7.4', [217], '7.3'],
            ['ibase_wait_event', '7.4', [218], '7.3'],
            ['fbird_add_user', '7.4', [987], '7.3'],
            ['fbird_affected_rows', '7.4', [988], '7.3'],
            ['fbird_backup', '7.4', [989], '7.3'],
            ['fbird_blob_add', '7.4', [990], '7.3'],
            ['fbird_blob_cancel', '7.4', [991], '7.3'],
            ['fbird_blob_close', '7.4', [992], '7.3'],
            ['fbird_blob_create', '7.4', [993], '7.3'],
            ['fbird_blob_echo', '7.4', [994], '7.3'],
            ['fbird_blob_get', '7.4', [995], '7.3'],
            ['fbird_blob_import', '7.4', [996], '7.3'],
            ['fbird_blob_info', '7.4', [997], '7.3'],
            ['fbird_blob_open', '7.4', [998], '7.3'],
            ['fbird_close', '7.4', [999], '7.3'],
            ['fbird_commit_ret', '7.4', [1000], '7.3'],
            ['fbird_commit', '7.4', [1001], '7.3'],
            ['fbird_connect', '7.4', [1002], '7.3'],
            ['fbird_db_info', '7.4', [1003], '7.3'],
            ['fbird_delete_user', '7.4', [1004], '7.3'],
            ['fbird_drop_db', '7.4', [1005], '7.3'],
            ['fbird_errcode', '7.4', [1006], '7.3'],
            ['fbird_errmsg', '7.4', [1007], '7.3'],
            ['fbird_execute', '7.4', [1008], '7.3'],
            ['fbird_fetch_assoc', '7.4', [1009], '7.3'],
            ['fbird_fetch_object', '7.4', [1010], '7.3'],
            ['fbird_fetch_row', '7.4', [1011], '7.3'],
            ['fbird_field_info', '7.4', [1012], '7.3'],
            ['fbird_free_event_handler', '7.4', [1013], '7.3'],
            ['fbird_free_query', '7.4', [1014], '7.3'],
            ['fbird_free_result', '7.4', [1015], '7.3'],
            ['fbird_gen_id', '7.4', [1016], '7.3'],
            ['fbird_maintain_db', '7.4', [1017], '7.3'],
            ['fbird_modify_user', '7.4', [1018], '7.3'],
            ['fbird_name_result', '7.4', [1019], '7.3'],
            ['fbird_num_fields', '7.4', [1020], '7.3'],
            ['fbird_num_params', '7.4', [1021], '7.3'],
            ['fbird_param_info', '7.4', [1022], '7.3'],
            ['fbird_pconnect', '7.4', [1023], '7.3'],
            ['fbird_prepare', '7.4', [1024], '7.3'],
            ['fbird_query', '7.4', [1025], '7.3'],
            ['fbird_restore', '7.4', [1026], '7.3'],
            ['fbird_rollback_ret', '7.4', [1027], '7.3'],
            ['fbird_rollback', '7.4', [1028], '7.3'],
            ['fbird_server_info', '7.4', [1029], '7.3'],
            ['fbird_service_attach', '7.4', [1030], '7.3'],
            ['fbird_service_detach', '7.4', [1031], '7.3'],
            ['fbird_set_event_handler', '7.4', [1032], '7.3'],
            ['fbird_trans', '7.4', [1033], '7.3'],
            ['fbird_wait_event', '7.4', [1034], '7.3'],

            ['wddx_add_vars', '7.4', [228], '7.3'],
            ['wddx_deserialize', '7.4', [229], '7.3'],
            ['wddx_packet_end', '7.4', [230], '7.3'],
            ['wddx_packet_start', '7.4', [231], '7.3'],
            ['wddx_serialize_value', '7.4', [232], '7.3'],
            ['wddx_serialize_vars', '7.4', [233], '7.3'],
            ['mysqli_embedded_server_end', '7.4', [1147], '7.3'],
            ['mysqli_embedded_server_start', '7.4', [1148], '7.3'],

            ['oci_internal_debug', '8.0', [1178], '7.4'],
            ['xmlrpc_decode_request', '8.0', [1158], '7.4'],
            ['xmlrpc_decode', '8.0', [1159], '7.4'],
            ['xmlrpc_encode_request', '8.0', [1160], '7.4'],
            ['xmlrpc_encode', '8.0', [1161], '7.4'],
            ['xmlrpc_get_type', '8.0', [1162], '7.4'],
            ['xmlrpc_is_fault', '8.0', [1163], '7.4'],
            ['xmlrpc_parse_method_descriptions', '8.0', [1164], '7.4'],
            ['xmlrpc_server_add_introspection_data', '8.0', [1165], '7.4'],
            ['xmlrpc_server_call_method', '8.0', [1166], '7.4'],
            ['xmlrpc_server_create', '8.0', [1167], '7.4'],
            ['xmlrpc_server_destroy', '8.0', [1168], '7.4'],
            ['xmlrpc_server_register_introspection_callback', '8.0', [1169], '7.4'],
            ['xmlrpc_server_register_method', '8.0', [1170], '7.4'],
            ['xmlrpc_set_type', '8.0', [1171], '7.4'],
        ];
    }


    /**
     * testRemovedFunctionWithAlternative
     *
     * @dataProvider dataRemovedFunctionWithAlternative
     *
     * @param string $functionName   Name of the function.
     * @param string $removedIn      The PHP version in which the function was removed.
     * @param string $alternative    An alternative function.
     * @param array  $lines          The line numbers in the test file which apply to this function.
     * @param string $okVersion      A PHP version in which the function was still valid.
     * @param string $removedVersion Optional PHP version to test removed message with -
     *                               if different from the $removedIn version.
     *
     * @return void
     */
    public function testRemovedFunctionWithAlternative($functionName, $removedIn, $alternative, $lines, $okVersion, $removedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "Function {$functionName}() is removed since PHP {$removedIn}; Use {$alternative} instead";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testRemovedFunctionWithAlternative()
     *
     * @return array
     */
    public function dataRemovedFunctionWithAlternative()
    {
        return [
            ['zend_logo_guid', '5.5', 'text string "PHPE9568F35-D428-11d2-A769-00AA001ACF42"', [35], '5.4'],

            ['recode_file', '7.4', 'the iconv or mbstring extension', [236], '7.3'],
            ['recode_string', '7.4', 'the iconv or mbstring extension', [237], '7.3'],
            ['recode', '7.4', 'the iconv or mbstring extension', [238], '7.3'],

            ['imap_header', '8.0', 'imap_headerinfo()', [1216], '7.4'],
        ];
    }


    /**
     * testDeprecatedRemovedFunction
     *
     * @dataProvider dataDeprecatedRemovedFunction
     *
     * @param string $functionName      Name of the function.
     * @param string $deprecatedIn      The PHP version in which the function was deprecated.
     * @param string $removedIn         The PHP version in which the function was removed.
     * @param array  $lines             The line numbers in the test file which apply to this function.
     * @param string $okVersion         A PHP version in which the function was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     * @param string $removedVersion    Optional PHP version to test removed message with -
     *                                  if different from the $removedIn version.
     *
     * @return void
     */
    public function testDeprecatedRemovedFunction($functionName, $deprecatedIn, $removedIn, $lines, $okVersion, $deprecatedVersion = null, $removedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "Function {$functionName}() is deprecated since PHP {$deprecatedIn}";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "Function {$functionName}() is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedRemovedFunction()
     *
     * @return array
     */
    public function dataDeprecatedRemovedFunction()
    {
        return [
            ['define_syslog_variables', '5.3', '5.4', [5], '5.2'],
            ['import_request_variables', '5.3', '5.4', [11], '5.2'],
            ['mysql_list_dbs', '5.4', '7.0', [15], '5.3'],
            ['magic_quotes_runtime', '5.3', '7.0', [23], '5.2'],
            ['set_magic_quotes_runtime', '5.3', '7.0', [27], '5.2'],
            ['sql_regcase', '5.3', '7.0', [31], '5.2'],
            ['mysql_affected_rows', '5.5', '7.0', [923], '5.4'],
            ['mysql_client_encoding', '5.5', '7.0', [924], '5.4'],
            ['mysql_close', '5.5', '7.0', [925], '5.4'],
            ['mysql_connect', '5.5', '7.0', [926], '5.4'],
            ['mysql_create_db', '4.3', '7.0', [927], '4.2'],
            ['mysql_data_seek', '5.5', '7.0', [928], '5.4'],
            ['mysql_db_name', '5.5', '7.0', [929], '5.4'],
            ['mysql_drop_db', '4.3', '7.0', [930], '4.2'],
            ['mysql_errno', '5.5', '7.0', [931], '5.4'],
            ['mysql_error', '5.5', '7.0', [932], '5.4'],
            ['mysql_fetch_array', '5.5', '7.0', [933], '5.4'],
            ['mysql_fetch_assoc', '5.5', '7.0', [934], '5.4'],
            ['mysql_fetch_field', '5.5', '7.0', [935], '5.4'],
            ['mysql_fetch_lengths', '5.5', '7.0', [936], '5.4'],
            ['mysql_fetch_object', '5.5', '7.0', [937], '5.4'],
            ['mysql_fetch_row', '5.5', '7.0', [938], '5.4'],
            ['mysql_field_flags', '5.5', '7.0', [939], '5.4'],
            ['mysql_field_len', '5.5', '7.0', [940], '5.4'],
            ['mysql_field_name', '5.5', '7.0', [941], '5.4'],
            ['mysql_field_seek', '5.5', '7.0', [942], '5.4'],
            ['mysql_field_table', '5.5', '7.0', [943], '5.4'],
            ['mysql_field_type', '5.5', '7.0', [944], '5.4'],
            ['mysql_free_result', '5.5', '7.0', [945], '5.4'],
            ['mysql_get_client_info', '5.5', '7.0', [946], '5.4'],
            ['mysql_get_host_info', '5.5', '7.0', [947], '5.4'],
            ['mysql_get_proto_info', '5.5', '7.0', [948], '5.4'],
            ['mysql_get_server_info', '5.5', '7.0', [949], '5.4'],
            ['mysql_info', '5.5', '7.0', [950], '5.4'],
            ['mysql_insert_id', '5.5', '7.0', [951], '5.4'],
            ['mysql_list_fields', '5.4', '7.0', [952], '5.3'],
            ['mysql_list_processes', '5.5', '7.0', [953], '5.4'],
            ['mysql_list_tables', '4.3.7', '7.0', [954], '4.3', '4.4'],
            ['mysql_num_fields', '5.5', '7.0', [955], '5.4'],
            ['mysql_num_rows', '5.5', '7.0', [956], '5.4'],
            ['mysql_pconnect', '5.5', '7.0', [957], '5.4'],
            ['mysql_ping', '5.5', '7.0', [958], '5.4'],
            ['mysql_query', '5.5', '7.0', [959], '5.4'],
            ['mysql_real_escape_string', '5.5', '7.0', [960], '5.4'],
            ['mysql_result', '5.5', '7.0', [961], '5.4'],
            ['mysql_select_db', '5.5', '7.0', [962], '5.4'],
            ['mysql_set_charset', '5.5', '7.0', [963], '5.4'],
            ['mysql_stat', '5.5', '7.0', [964], '5.4'],
            ['mysql_tablename', '5.5', '7.0', [965], '5.4'],
            ['mysql_thread_id', '5.5', '7.0', [966], '5.4'],
            ['mysql_unbuffered_query', '5.5', '7.0', [967], '5.4'],
            ['mysql', '5.5', '7.0', [968], '5.4'],
            ['mysql_fieldname', '5.5', '7.0', [969], '5.4'],
            ['mysql_fieldtable', '5.5', '7.0', [970], '5.4'],
            ['mysql_fieldlen', '5.5', '7.0', [971], '5.4'],
            ['mysql_fieldtype', '5.5', '7.0', [972], '5.4'],
            ['mysql_fieldflags', '5.5', '7.0', [973], '5.4'],
            ['mysql_selectdb', '5.5', '7.0', [974], '5.4'],
            ['mysql_freeresult', '5.5', '7.0', [977], '5.4'],
            ['mysql_numfields', '5.5', '7.0', [978], '5.4'],
            ['mysql_numrows', '5.5', '7.0', [979], '5.4'],
            ['mysql_listdbs', '5.5', '7.0', [980], '5.4'],
            ['mysql_listfields', '5.5', '7.0', [982], '5.4'],
            ['mysql_dbname', '5.5', '7.0', [983], '5.4'],
            ['mysql_table_name', '5.5', '7.0', [984], '5.4'],

            ['ldap_sort', '7.0', '8.0', [97], '5.6'],
            ['fgetss', '7.3', '8.0', [167], '7.2'],
            ['gzgetss', '7.3', '8.0', [168], '7.2'],
            ['ezmlm_hash', '7.4', '8.0', [245], '7.3'],
            ['get_magic_quotes_gpc', '7.4', '8.0', [240], '7.3'],
            ['get_magic_quotes_runtime', '7.4', '8.0', [241], '7.3'],
            ['hebrevc', '7.4', '8.0', [242], '7.3'],
        ];
    }


    /**
     * testDeprecatedRemovedFunctionWithAlternative
     *
     * @dataProvider dataDeprecatedRemovedFunctionWithAlternative
     *
     * @param string $functionName      Name of the function.
     * @param string $deprecatedIn      The PHP version in which the function was deprecated.
     * @param string $removedIn         The PHP version in which the function was removed.
     * @param string $alternative       An alternative function.
     * @param array  $lines             The line numbers in the test file which apply to this function.
     * @param string $okVersion         A PHP version in which the function was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     * @param string $removedVersion    Optional PHP version to test removed message with -
     *                                  if different from the $removedIn version.
     *
     * @return void
     */
    public function testDeprecatedRemovedFunctionWithAlternative($functionName, $deprecatedIn, $removedIn, $alternative, $lines, $okVersion, $deprecatedVersion = null, $removedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "Function {$functionName}() is deprecated since PHP {$deprecatedIn}; Use {$alternative} instead";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "Function {$functionName}() is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}; Use {$alternative} instead";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedRemovedFunctionWithAlternative()
     *
     * @return array
     */
    public function dataDeprecatedRemovedFunctionWithAlternative()
    {
        return [
            ['call_user_method', '4.1', '7.0', 'call_user_func()', [3], '4.0'],
            ['call_user_method_array', '4.1', '7.0', 'call_user_func_array()', [4], '4.0'],
            ['ereg', '5.3', '7.0', 'preg_match()', [7], '5.2'],
            ['ereg_replace', '5.3', '7.0', 'preg_replace()', [8], '5.2'],
            ['eregi', '5.3', '7.0', 'preg_match() (with the i modifier)', [9], '5.2'],
            ['eregi_replace', '5.3', '7.0', 'preg_replace() (with the i modifier)', [10], '5.2'],
            ['mcrypt_generic_end', '5.3', '7.0', 'mcrypt_generic_deinit()', [12], '5.2'],
            ['mcrypt_ecb', '5.5', '7.0', 'mcrypt_decrypt()/mcrypt_encrypt()', [37], '5.4'],
            ['mcrypt_cbc', '5.5', '7.0', 'mcrypt_decrypt()/mcrypt_encrypt()', [38], '5.4'],
            ['mcrypt_cfb', '5.5', '7.0', 'mcrypt_decrypt()/mcrypt_encrypt()', [39], '5.4'],
            ['mcrypt_ofb', '5.5', '7.0', 'mcrypt_decrypt()/mcrypt_encrypt()', [40], '5.4'],
            ['mysql_db_query', '5.3', '7.0', 'mysqli::select_db() and mysqli::query()', [13], '5.2'],
            ['mysql_escape_string', '4.3', '7.0', 'mysqli::real_escape_string()', [14], '4.2'],
            ['mysqli_bind_param', '5.3', '5.4', 'mysqli_stmt::bind_param()', [16], '5.2'],
            ['mysqli_bind_result', '5.3', '5.4', 'mysqli_stmt::bind_result()', [17], '5.2'],
            ['mysqli_client_encoding', '5.3', '5.4', 'mysqli::character_set_name()', [18], '5.2'],
            ['mysqli_fetch', '5.3', '5.4', 'mysqli_stmt::fetch()', [19], '5.2'],
            ['mysqli_param_count', '5.3', '5.4', 'mysqli_stmt_param_count()', [20], '5.2'],
            ['mysqli_get_metadata', '5.3', '5.4', 'mysqli_stmt::result_metadata()', [21], '5.2'],
            ['mysqli_send_long_data', '5.3', '5.4', 'mysqli_stmt::send_long_data()', [22], '5.2'],
            ['session_register', '5.3', '5.4', '$_SESSION', [24], '5.2'],
            ['session_unregister', '5.3', '5.4', '$_SESSION', [25], '5.2'],
            ['session_is_registered', '5.3', '5.4', '$_SESSION', [26], '5.2'],
            ['set_socket_blocking', '5.3', '7.0', 'stream_set_blocking()', [28], '5.2'],
            ['split', '5.3', '7.0', 'preg_split(), explode() or str_split()', [29], '5.2'],
            ['spliti', '5.3', '7.0', 'preg_split() (with the i modifier)', [30], '5.2'],
            ['datefmt_set_timezone_id', '5.5', '7.0', 'IntlDateFormatter::setTimeZone()', [36], '5.4'],

            ['mcrypt_create_iv', '7.1', '7.2', 'random_bytes() or OpenSSL', [100], '7.0'],
            ['mcrypt_decrypt', '7.1', '7.2', 'OpenSSL', [101], '7.0'],
            ['mcrypt_enc_get_algorithms_name', '7.1', '7.2', 'OpenSSL', [102], '7.0'],
            ['mcrypt_enc_get_block_size', '7.1', '7.2', 'OpenSSL', [103], '7.0'],
            ['mcrypt_enc_get_iv_size', '7.1', '7.2', 'OpenSSL', [104], '7.0'],
            ['mcrypt_enc_get_key_size', '7.1', '7.2', 'OpenSSL', [105], '7.0'],
            ['mcrypt_enc_get_modes_name', '7.1', '7.2', 'OpenSSL', [106], '7.0'],
            ['mcrypt_enc_get_supported_key_sizes', '7.1', '7.2', 'OpenSSL', [107], '7.0'],
            ['mcrypt_enc_is_block_algorithm_mode', '7.1', '7.2', 'OpenSSL', [108], '7.0'],
            ['mcrypt_enc_is_block_algorithm', '7.1', '7.2', 'OpenSSL', [109], '7.0'],
            ['mcrypt_enc_is_block_mode', '7.1', '7.2', 'OpenSSL', [110], '7.0'],
            ['mcrypt_enc_self_test', '7.1', '7.2', 'OpenSSL', [111], '7.0'],
            ['mcrypt_encrypt', '7.1', '7.2', 'OpenSSL', [112], '7.0'],
            ['mcrypt_generic_deinit', '7.1', '7.2', 'OpenSSL', [113], '7.0'],
            ['mcrypt_generic_init', '7.1', '7.2', 'OpenSSL', [114], '7.0'],
            ['mcrypt_generic', '7.1', '7.2', 'OpenSSL', [115], '7.0'],
            ['mcrypt_get_block_size', '7.1', '7.2', 'OpenSSL', [116], '7.0'],
            ['mcrypt_get_cipher_name', '7.1', '7.2', 'OpenSSL', [117], '7.0'],
            ['mcrypt_get_iv_size', '7.1', '7.2', 'OpenSSL', [118], '7.0'],
            ['mcrypt_get_key_size', '7.1', '7.2', 'OpenSSL', [119], '7.0'],
            ['mcrypt_list_algorithms', '7.1', '7.2', 'OpenSSL', [120], '7.0'],
            ['mcrypt_list_modes', '7.1', '7.2', 'OpenSSL', [121], '7.0'],
            ['mcrypt_module_close', '7.1', '7.2', 'OpenSSL', [122], '7.0'],
            ['mcrypt_module_get_algo_block_size', '7.1', '7.2', 'OpenSSL', [123], '7.0'],
            ['mcrypt_module_get_algo_key_size', '7.1', '7.2', 'OpenSSL', [124], '7.0'],
            ['mcrypt_module_get_supported_key_sizes', '7.1', '7.2', 'OpenSSL', [125], '7.0'],
            ['mcrypt_module_is_block_algorithm_mode', '7.1', '7.2', 'OpenSSL', [126], '7.0'],
            ['mcrypt_module_is_block_algorithm', '7.1', '7.2', 'OpenSSL', [127], '7.0'],
            ['mcrypt_module_is_block_mode', '7.1', '7.2', 'OpenSSL', [128], '7.0'],
            ['mcrypt_module_open', '7.1', '7.2', 'OpenSSL', [129], '7.0'],
            ['mcrypt_module_self_test', '7.1', '7.2', 'OpenSSL', [130], '7.0'],
            ['mdecrypt_generic', '7.1', '7.2', 'OpenSSL', [131], '7.0'],

            ['create_function', '7.2', '8.0', 'an anonymous function', [146], '7.1'],
            ['each', '7.2', '8.0', 'a foreach loop or ArrayIterator', [147], '7.1'],
            ['read_exif_data', '7.2', '8.0', 'exif_read_data()', [149], '7.1'],
            ['jpeg2wbmp', '7.2', '8.0', 'imagecreatefromjpeg() and imagewbmp()', [144], '7.1'],
            ['png2wbmp', '7.2', '8.0', 'imagecreatefrompng() or imagewbmp()', [145], '7.1'],
            ['gmp_random', '7.2', '8.0', 'gmp_random_bits() or gmp_random_range()', [148], '7.1'],

            ['image2wbmp', '7.3', '8.0', 'imagewbmp()', [152], '7.2'],
            ['mbregex_encoding', '7.3', '8.0', 'mb_regex_encoding()', [153], '7.2'],
            ['mbereg', '7.3', '8.0', 'mb_ereg()', [154], '7.2'],
            ['mberegi', '7.3', '8.0', 'mb_eregi()', [155], '7.2'],
            ['mbereg_replace', '7.3', '8.0', 'mb_ereg_replace()', [156], '7.2'],
            ['mberegi_replace', '7.3', '8.0', 'mb_eregi_replace()', [157], '7.2'],
            ['mbsplit', '7.3', '8.0', 'mb_split()', [158], '7.2'],
            ['mbereg_match', '7.3', '8.0', 'mb_ereg_match()', [159], '7.2'],
            ['mbereg_search', '7.3', '8.0', 'mb_ereg_search()', [160], '7.2'],
            ['mbereg_search_pos', '7.3', '8.0', 'mb_ereg_search_pos()', [161], '7.2'],
            ['mbereg_search_regs', '7.3', '8.0', 'mb_ereg_search_regs()', [162], '7.2'],
            ['mbereg_search_init', '7.3', '8.0', 'mb_ereg_search_init()', [163], '7.2'],
            ['mbereg_search_getregs', '7.3', '8.0', 'mb_ereg_search_getregs()', [164], '7.2'],
            ['mbereg_search_getpos', '7.3', '8.0', 'mb_ereg_search_getpos()', [165], '7.2'],
            ['mbereg_search_setpos', '7.3', '8.0', 'mb_ereg_search_setpos()', [166], '7.2'],

            ['convert_cyr_string', '7.4', '8.0', 'mb_convert_encoding(), iconv() or UConverter', [243], '7.3'],
            ['money_format', '7.4', '8.0', 'NumberFormatter::formatCurrency()', [244], '7.3'],
            ['restore_include_path', '7.4', '8.0', "ini_restore('include_path')", [246], '7.3'],
            ['ociinternaldebug', '5.4', '8.0', 'oci_internal_debug() (PHP < 8.0)', [69], '5.3'],
            ['ldap_control_paged_result_response', '7.4', '8.0', 'ldap_search()', [234], '7.3'],
            ['ldap_control_paged_result', '7.4', '8.0', 'ldap_search()', [235], '7.3'],
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
        $file = $this->sniffFile(__FILE__, '99.0'); // High version beyond latest deprecation.
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
            [134],
            [135],
            [136],
            [137],
            [138],
            [139],
            [140],
            [141],
            [249],
            [250],
            [251],
            [252],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '4.0'); // Low version below the first deprecation.
        $this->assertNoViolation($file);
    }
}
