<?php
/**
 * Deprecated functions sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Deprecated functions sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class DeprecatedFunctionsSniffTest extends BaseSniffTest
{
    /**
     * Test call user method
     *
     * @return void
     */
    public function testCallUserMethod()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '5.6');
        $this->assertWarning($file, 3, 'The use of function call_user_method is discouraged from PHP version 5.3; use call_user_func instead');
        
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '7.0');
        $this->assertError($file, 3, 'The use of function call_user_method is discouraged from PHP version 5.3 and forbidden from PHP version 7.0; use call_user_func instead');
    }

    /**
     * Test call user method array
     *
     * @return void
     */
    public function testCallUserMethodArray()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '5.6');
        $this->assertWarning($file, 4, 'The use of function call_user_method_array is discouraged from PHP version 5.3; use call_user_func_array instead');

        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '7.0');
        $this->assertError($file, 4, 'The use of function call_user_method_array is discouraged from PHP version 5.3 and forbidden from PHP version 7.0; use call_user_func_array instead');
    }

    /**
     * Test define syslog variables
     *
     * @return void
     */
    public function testDefineSyslogVariables()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');
        $this->assertError($file, 5, 'The use of');
    }

    /**
     * Test dl
     *
     * @return void
     */
    public function testDl()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 6, 'The use of function dl is discouraged from PHP version 5.3');
    }

    /**
     * Test ereg
     *
     * @return void
     */
    public function testEreg()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 7, 'The use of function ereg is discouraged from PHP version 5.3');
    }

    /**
     * Test ereg_replace
     *
     * @return void
     */
    public function testEregReplace()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 8, 'The use of function ereg_replace is discouraged from PHP version 5.3');
    }

    /**
     * Test eregi
     *
     * @return void
     */
    public function testEregi()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 9, 'The use of function eregi is discouraged from PHP version 5.3');
    }

    /**
     * Test eregi_replace
     *
     * @return void
     */
    public function testEregiReplace()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 10, 'The use of function eregi_replace is discouraged from PHP version 5.3');
    }

    /**
     * Test import_request_variables
     *
     * @return void
     */
    public function testImportRequestVariables()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertError($file, 11, 'The use of function import_request_variables is forbidden from PHP version 5.4');
    }

    /**
     * Test mcrypt_generic_end
     *
     * @return void
     */
    public function testMcryptGenericEnd()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '5.6');
        $this->assertWarning($file, 12, 'The use of function mcrypt_generic_end is discouraged from PHP version 5.4; use mcrypt_generic_deinit instead');
        
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '7.0');
        $this->assertError($file, 12, 'The use of function mcrypt_generic_end is discouraged from PHP version 5.4 and forbidden from PHP version 7.0; use mcrypt_generic_deinit instead');
    }

    /**
     * Test mysql_db_query
     *
     * @return void
     */
    public function testMysqlDbQuery()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 13, 'The use of function mysql_db_query is discouraged from PHP version 5.3; use mysql_select_db and mysql_query instead');
    }

    /**
     * Test mysql_escape_string
     *
     * @return void
     */
    public function testMysqlEscapeString()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 14, 'The use of function mysql_escape_string is discouraged from PHP version 5.3; use mysql_real_escape_string instead');
    }

    /**
     * Test mysql_list_dbs
     *
     * @return void
     */
    public function testMysqlListDbs()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 15, 'The use of function mysql_list_dbs is discouraged from PHP version 5.4');
    }

    /**
     * Test mysqli_bind_param
     *
     * @return void
     */
    public function testMysqliBindParam()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertError($file, 16, 'The use of function mysqli_bind_param is forbidden from PHP version 5.4; use mysqli_stmt_bind_param instead');
    }

    /**
     * Test mysqli_bind_result
     *
     * @return void
     */
    public function testMysqliBindResult()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertError($file, 17, 'The use of function mysqli_bind_result is forbidden from PHP version 5.4; use mysqli_stmt_bind_result instead');
    }

    /**
     * Test mysqli_client_encoding
     *
     * @return void
     */
    public function testMysqliClientEncoding()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertError($file, 18, 'The use of function mysqli_client_encoding is forbidden from PHP version 5.4; use mysqli_character_set_name instead');
    }

    /**
     * Test mysqli_fetch
     *
     * @return void
     */
    public function testMysqliFetch()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertError($file, 19, 'The use of function mysqli_fetch is forbidden from PHP version 5.4; use mysqli_stmt_fetch instead');
    }

    /**
     * Test mysqli_param_count
     *
     * @return void
     */
    public function testMysqliParamCount()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertError($file, 20, 'The use of function mysqli_param_count is forbidden from PHP version 5.4; use mysqli_stmt_param_count instead');
    }

    /**
     * Test mysqli_get_metadata
     *
     * @return void
     */
    public function testMysqliGetMetadata()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertError($file, 21, 'The use of function mysqli_get_metadata is forbidden from PHP version 5.4; use mysqli_stmt_result_metadata instead');
    }

    /**
     * Test mysqli_send_long_data
     *
     * @return void
     */
    public function testMysqliSendLongData()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertError($file, 22, 'The use of function mysqli_send_long_data is forbidden from PHP version 5.4; use mysqli_stmt_send_long_data instead');
    }

    /**
     * Test magic_quotes_runtime
     *
     * @return void
     */
    public function testMagicQuotesRuntime()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '5.6');
        $this->assertWarning($file, 23, 'The use of function magic_quotes_runtime is discouraged from PHP version 5.3');
        
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '7.0');
        $this->assertError($file, 23, 'The use of function magic_quotes_runtime is discouraged from PHP version 5.3 and forbidden from PHP version 7.0');
    }

    /**
     * Test session_register
     *
     * @return void
     */
    public function testSessionRegister()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertError($file, 24, 'The use of function session_register is discouraged from PHP version 5.3 and forbidden from PHP version 5.4; use $_SESSION instead');
    }

    /**
     * Test session_unregister
     *
     * @return void
     */
    public function testSessionUnregister()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertError($file, 25, 'The use of function session_unregister is discouraged from PHP version 5.3 and forbidden from PHP version 5.4; use $_SESSION instead');
    }

    /**
     * Test session_is_registered
     *
     * @return void
     */
    public function testSessionIsRegistered()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertError($file, 26, 'The use of function session_is_registered is discouraged from PHP version 5.3 and forbidden from PHP version 5.4; use $_SESSION instead');
    }

    /**
     * Test set_magic_quotes_runtime
     *
     * @return void
     */
    public function testSetMagicQuotesRuntime()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '5.6');
        $this->assertWarning($file, 27, 'The use of function set_magic_quotes_runtime is discouraged from PHP version 5.3');
        
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '7.0');
        $this->assertError($file, 27, 'The use of function set_magic_quotes_runtime is discouraged from PHP version 5.3 and forbidden from PHP version 7.0');
    }

    /**
     * Test set_socket_blocking
     *
     * @return void
     */
    public function testSetSocketBlocking()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '5.6');
        $this->assertWarning($file, 28, 'The use of function set_socket_blocking is discouraged from PHP version 5.3; use stream_set_blocking instead');
        
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '7.0');
        $this->assertError($file, 28, 'The use of function set_socket_blocking is discouraged from PHP version 5.3 and forbidden from PHP version 7.0; use stream_set_blocking instead');
    }

    /**
     * Test split
     *
     * @return void
     */
    public function testSplit()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 29, 'The use of function split is discouraged from PHP version 5.3; use preg_split instead');
    }

    /**
     * Test spliti
     *
     * @return void
     */
    public function testSpliti()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 30, 'The use of function spliti is discouraged from PHP version 5.3; use preg_split instead');
    }

    /**
     * Test sql_regcase
     *
     * @return void
     */
    public function testSqlRegcase()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 31, 'The use of function sql_regcase is discouraged from PHP version 5.3');
    }

    /**
     * Test php_logo_guid
     *
     * @return void
     */
    public function testPhpLogoGuid()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertError($file, 32, 'The use of function php_logo_guid is forbidden from PHP version 5.5');
    }

    /**
     * Test php_egg_logo_guid
     *
     * @return void
     */
    public function testPhpEggLogoGuid()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertError($file, 33, 'The use of function php_egg_logo_guid is forbidden from PHP version 5.5');
    }

    /**
     * Test php_real_logo_guid
     *
     * @return void
     */
    public function testPhpRealLogoGuid()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertError($file, 34, 'The use of function php_real_logo_guid is forbidden from PHP version 5.5');
    }

    /**
     * Test zend_logo_guid
     *
     * @return void
     */
    public function testZendLogoGuid()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertError($file, 35, 'The use of function zend_logo_guid is forbidden from PHP version 5.5');
    }

    /**
     * Test datefmt_set_timezone_id
     *
     * @return void
     */
    public function testDateFmtSetTimezone()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '5.6');
        $this->assertWarning($file, 36, 'The use of function datefmt_set_timezone_id is discouraged from PHP version 5.5; use datefmt_set_timezone instead');
        
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '7.0');
        $this->assertError($file, 36, 'The use of function datefmt_set_timezone_id is discouraged from PHP version 5.5 and forbidden from PHP version 7.0; use datefmt_set_timezone instead');
    }

    /**
     * Test mcrypt_ecb
     *
     * @return void
     */
    public function testMcryptEcb()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '5.6');
        $this->assertWarning($file, 37, 'The use of function mcrypt_ecb is discouraged from PHP version 5.5');
        
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '7.0');
        $this->assertError($file, 37, 'The use of function mcrypt_ecb is discouraged from PHP version 5.5 and forbidden from PHP version 7.0');
    }

    /**
     * Test mcrypt_cbc
     *
     * @return void
     */
    public function testMcryptCbc()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '5.6');
        $this->assertWarning($file, 38, 'The use of function mcrypt_cbc is discouraged from PHP version 5.5');
        
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '7.0');
        $this->assertError($file, 38, 'The use of function mcrypt_cbc is discouraged from PHP version 5.5 and forbidden from PHP version 7.0');
    }

    /**
     * Test mcrypt_cfb
     *
     * @return void
     */
    public function testMcryptCfb()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '5.6');
        $this->assertWarning($file, 39, 'The use of function mcrypt_cfb is discouraged from PHP version 5.5');
        
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '7.0');
        $this->assertError($file, 39, 'The use of function mcrypt_cfb is discouraged from PHP version 5.5 and forbidden from PHP version 7.0');
    }

    /**
     * Test mcrypt_ofb
     *
     * @return void
     */
    public function testMcryptOfb()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '5.6');
        $this->assertWarning($file, 40, 'The use of function mcrypt_ofb is discouraged from PHP version 5.5');
        
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '7.0');
        $this->assertError($file, 40, 'The use of function mcrypt_ofb is discouraged from PHP version 5.5 and forbidden from PHP version 7.0');
    }

    /**
     * Test ocibindbyname
     *
     * @return void
     */
    public function testOcibindbyname()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 41, 'The use of function ocibindbyname is discouraged from PHP version 5.4; use oci_bind_by_name instead');
    }

    /**
     * Test ocicancel
     *
     * @return void
     */
    public function testOcicancel()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 42, 'The use of function ocicancel is discouraged from PHP version 5.4; use oci_cancel instead');
    }

    /**
     * Test ocicloselob
     *
     * @return void
     */
    public function testOcicloselob()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 43, 'The use of function ocicloselob is discouraged from PHP version 5.4; use OCI-Lob::close instead');
    }

    /**
     * Test ocicollappend
     *
     * @return void
     */
    public function testOcicollappend()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 44, 'The use of function ocicollappend is discouraged from PHP version 5.4; use OCI-Collection::append instead');
    }

    /**
     * Test ocicollassign
     *
     * @return void
     */
    public function testOcicollassign()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 45, 'The use of function ocicollassign is discouraged from PHP version 5.4; use OCI-Collection::assign instead');
    }

    /**
     * Test ocicollassignelem
     *
     * @return void
     */
    public function testOcicollassignelem()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 46, 'The use of function ocicollassignelem is discouraged from PHP version 5.4; use OCI-Collection::assignElem instead');
    }

    /**
     * Test ocicollgetelem
     *
     * @return void
     */
    public function testOcicollgetelem()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 47, 'The use of function ocicollgetelem is discouraged from PHP version 5.4; use OCI-Collection::getElem instead');
    }

    /**
     * Test ocicollmax
     *
     * @return void
     */
    public function testOcicollmax()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 48, 'The use of function ocicollmax is discouraged from PHP version 5.4; use OCI-Collection::max instead');
    }

    /**
     * Test ocicollsize
     *
     * @return void
     */
    public function testOcicollsize()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 49, 'The use of function ocicollsize is discouraged from PHP version 5.4; use OCI-Collection::size instead');
    }

    /**
     * Test ocicolltrim
     *
     * @return void
     */
    public function testOcicolltrim()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 50, 'The use of function ocicolltrim is discouraged from PHP version 5.4; use OCI-Collection::trim instead');
    }

    /**
     * Test ocicolumnisnull
     *
     * @return void
     */
    public function testOcicolumnisnull()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 51, 'The use of function ocicolumnisnull is discouraged from PHP version 5.4; use oci_field_is_null instead');
    }

    /**
     * Test ocicolumnname
     *
     * @return void
     */
    public function testOcicolumnname()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 52, 'The use of function ocicolumnname is discouraged from PHP version 5.4; use oci_field_name instead');
    }

    /**
     * Test ocicolumnprecision
     *
     * @return void
     */
    public function testOcicolumnprecision()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 53, 'The use of function ocicolumnprecision is discouraged from PHP version 5.4; use oci_field_precision instead');
    }

    /**
     * Test ocicolumnscale
     *
     * @return void
     */
    public function testOcicolumnscale()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 54, 'The use of function ocicolumnscale is discouraged from PHP version 5.4; use oci_field_scale instead');
    }

    /**
     * Test ocicolumnsize
     *
     * @return void
     */
    public function testOcicolumnsize()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 55, 'The use of function ocicolumnsize is discouraged from PHP version 5.4; use oci_field_size instead');
    }

    /**
     * Test ocicolumntype
     *
     * @return void
     */
    public function testOcicolumntype()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 56, 'The use of function ocicolumntype is discouraged from PHP version 5.4; use oci_field_type instead');
    }

    /**
     * Test ocicolumntyperaw
     *
     * @return void
     */
    public function testOcicolumntyperaw()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 57, 'The use of function ocicolumntyperaw is discouraged from PHP version 5.4; use oci_field_type_raw instead');
    }

    /**
     * Test ocicommit
     *
     * @return void
     */
    public function testOcicommit()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 58, 'The use of function ocicommit is discouraged from PHP version 5.4; use oci_commit instead');
    }

    /**
     * Test ocidefinebyname
     *
     * @return void
     */
    public function testOcidefinebyname()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 59, 'The use of function ocidefinebyname is discouraged from PHP version 5.4; use oci_define_by_name instead');
    }

    /**
     * Test ocierror
     *
     * @return void
     */
    public function testOcierror()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 60, 'The use of function ocierror is discouraged from PHP version 5.4; use oci_error instead');
    }

    /**
     * Test ociexecute
     *
     * @return void
     */
    public function testOciexecute()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 61, 'The use of function ociexecute is discouraged from PHP version 5.4; use oci_execute instead');
    }

    /**
     * Test ocifetch
     *
     * @return void
     */
    public function testOcifetch()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 62, 'The use of function ocifetch is discouraged from PHP version 5.4; use oci_fetch instead');
    }

    /**
     * Test ocifetchinto
     *
     * @return void
     */
    public function testOcifetchinto()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 63, 'The use of function ocifetchinto is discouraged from PHP version 5.4');
    }

    /**
     * Test ocifetchstatement
     *
     * @return void
     */
    public function testOcifetchstatement()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 64, 'The use of function ocifetchstatement is discouraged from PHP version 5.4; use oci_fetch_all instead');
    }

    /**
     * Test ocifreecollection
     *
     * @return void
     */
    public function testOcifreecollection()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 65, 'The use of function ocifreecollection is discouraged from PHP version 5.4; use OCI-Collection::free instead');
    }

    /**
     * Test ocifreecursor
     *
     * @return void
     */
    public function testOcifreecursor()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 66, 'The use of function ocifreecursor is discouraged from PHP version 5.4; use oci_free_statement instead');
    }

    /**
     * Test ocifreedesc
     *
     * @return void
     */
    public function testOcifreedesc()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 67, 'The use of function ocifreedesc is discouraged from PHP version 5.4; use OCI-Lob::free instead');
    }

    /**
     * Test ocifreestatement
     *
     * @return void
     */
    public function testOcifreestatement()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 68, 'The use of function ocifreestatement is discouraged from PHP version 5.4; use oci_free_statement instead');
    }

    /**
     * Test ociinternaldebug
     *
     * @return void
     */
    public function testOciinternaldebug()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 69, 'The use of function ociinternaldebug is discouraged from PHP version 5.4; use oci_internal_debug instead');
    }

    /**
     * Test ociloadlob
     *
     * @return void
     */
    public function testOciloadlob()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 70, 'The use of function ociloadlob is discouraged from PHP version 5.4; use OCI-Lob::load instead');
    }

    /**
     * Test ocilogoff
     *
     * @return void
     */
    public function testOcilogoff()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 71, 'The use of function ocilogoff is discouraged from PHP version 5.4; use oci_close instead');
    }

    /**
     * Test ocilogon
     *
     * @return void
     */
    public function testOcilogon()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 72, 'The use of function ocilogon is discouraged from PHP version 5.4; use oci_connect instead');
    }

    /**
     * Test ocinewcollection
     *
     * @return void
     */
    public function testOcinewcollection()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 73, 'The use of function ocinewcollection is discouraged from PHP version 5.4; use oci_new_collection instead');
    }

    /**
     * Test ocinewcursor
     *
     * @return void
     */
    public function testOcinewcursor()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 74, 'The use of function ocinewcursor is discouraged from PHP version 5.4; use oci_new_cursor instead');
    }

    /**
     * Test ocinewdescriptor
     *
     * @return void
     */
    public function testOcinewdescriptor()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 75, 'The use of function ocinewdescriptor is discouraged from PHP version 5.4; use oci_new_descriptor instead');
    }

    /**
     * Test ocinlogon
     *
     * @return void
     */
    public function testOcinlogon()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 76, 'The use of function ocinlogon is discouraged from PHP version 5.4; use oci_new_connect instead');
    }

    /**
     * Test ocinumcols
     *
     * @return void
     */
    public function testOcinumcols()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 77, 'The use of function ocinumcols is discouraged from PHP version 5.4; use oci_num_fields instead');
    }

    /**
     * Test ociparse
     *
     * @return void
     */
    public function testOciparse()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 78, 'The use of function ociparse is discouraged from PHP version 5.4; use oci_parse instead');
    }

    /**
     * Test ociplogon
     *
     * @return void
     */
    public function testOciplogon()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 79, 'The use of function ociplogon is discouraged from PHP version 5.4; use oci_pconnect instead');
    }

    /**
     * Test ociresult
     *
     * @return void
     */
    public function testOciresult()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 80, 'The use of function ociresult is discouraged from PHP version 5.4; use oci_result instead');
    }

    /**
     * Test ocirollback
     *
     * @return void
     */
    public function testOcirollback()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 81, 'The use of function ocirollback is discouraged from PHP version 5.4; use oci_rollback instead');
    }

    /**
     * Test ocirowcount
     *
     * @return void
     */
    public function testOcirowcount()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 82, 'The use of function ocirowcount is discouraged from PHP version 5.4; use oci_num_rows instead');
    }

    /**
     * Test ocisavelob
     *
     * @return void
     */
    public function testOcisavelob()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 83, 'The use of function ocisavelob is discouraged from PHP version 5.4; use OCI-Lob::save instead');
    }

    /**
     * Test ocisavelobfile
     *
     * @return void
     */
    public function testOcisavelobfile()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 84, 'The use of function ocisavelobfile is discouraged from PHP version 5.4; use OCI-Lob::import instead');
    }

    /**
     * Test ociserverversion
     *
     * @return void
     */
    public function testOciserverversion()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 85, 'The use of function ociserverversion is discouraged from PHP version 5.4; use oci_server_version instead');
    }

    /**
     * Test ocisetprefetch
     *
     * @return void
     */
    public function testOcisetprefetch()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 86, 'The use of function ocisetprefetch is discouraged from PHP version 5.4; use oci_set_prefetch instead');
    }

    /**
     * Test ocistatementtype
     *
     * @return void
     */
    public function testOcistatementtype()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 87, 'The use of function ocistatementtype is discouraged from PHP version 5.4; use oci_statement_type instead');
    }

    /**
     * Test ociwritelobtofile
     *
     * @return void
     */
    public function testOciwritelobtofile()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 88, 'The use of function ociwritelobtofile is discouraged from PHP version 5.4; use OCI-Lob::export instead');
    }

    /**
     * Test ociwritetemporarylob
     *
     * @return void
     */
    public function testOciwritetemporarylob()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 89, 'The use of function ociwritetemporarylob is discouraged from PHP version 5.4; use OCI-Lob::writeTemporary instead');
    }

    /**
     * Test ldap_sort
     *
     * @return void
     */
    public function testLdapSort()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');
    
        $this->assertWarning($file, 97, 'The use of function ldap_sort is discouraged from PHP version 7.0');
    }
    
    /**
     * GD Type 1 PostScript functions
     *
     * @return void
     */
    public function testGDType1()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '5.6');
        $this->assertNoViolation($file, 90);
        $this->assertNoViolation($file, 91);
        $this->assertNoViolation($file, 92);
        $this->assertNoViolation($file, 93);
        $this->assertNoViolation($file, 94);
        $this->assertNoViolation($file, 95);
        $this->assertNoViolation($file, 96);
        
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '7.0');
        $this->assertError($file, 90, 'The use of function imagepsbbox is forbidden from PHP version 7.0');
        $this->assertError($file, 91, 'The use of function imagepsencodefont is forbidden from PHP version 7.0');
        $this->assertError($file, 92, 'The use of function imagepsextendfont is forbidden from PHP version 7.0');
        $this->assertError($file, 93, 'The use of function imagepsfreefont is forbidden from PHP version 7.0');
        $this->assertError($file, 94, 'The use of function imagepsloadfont is forbidden from PHP version 7.0');
        $this->assertError($file, 95, 'The use of function imagepsslantfont is forbidden from PHP version 7.0');
        $this->assertError($file, 96, 'The use of function imagepstext is forbidden from PHP version 7.0');
    }

    /**
     * Test when setting the testVersion
     *
     * @return void
     */
    public function testSettingTestVersion()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php', '5.3');

        $this->assertNoViolation($file, 19);
    }
}

