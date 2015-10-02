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
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 3, 'The use of function call_user_method is discouraged from PHP version 5.3; use call_user_func instead');
    }

    /**
     * Test call user method array
     *
     * @return void
     */
    public function testCallUserMethodArray()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 4, 'The use of function call_user_method_array is discouraged from PHP version 5.3; use call_user_func_array instead');
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
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 12, 'The use of function mcrypt_generic_end is discouraged from PHP version 5.4; use mcrypt_generic_deinit instead');
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
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 23, 'The use of function magic_quotes_runtime is discouraged from PHP version 5.3');
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
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertError($file, 27, 'The use of function set_magic_quotes_runtime is discouraged from PHP version 5.3 and forbidden from PHP version 5.4');
    }

    /**
     * Test set_socket_blocking
     *
     * @return void
     */
    public function testSetSocketBlocking()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 28, 'The use of function set_socket_blocking is discouraged from PHP version 5.3; use stream_set_blocking instead');
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
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 36, 'The use of function datefmt_set_timezone_id is discouraged from PHP version 5.5; use datefmt_set_timezone instead');
    }

    /**
     * Test mcrypt_ecb
     *
     * @return void
     */
    public function testMcryptEcb()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 37, 'The use of function mcrypt_ecb is discouraged from PHP version 5.5');
    }

    /**
     * Test mcrypt_cbc
     *
     * @return void
     */
    public function testMcryptCbc()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 38, 'The use of function mcrypt_cbc is discouraged from PHP version 5.5');
    }

    /**
     * Test mcrypt_cfb
     *
     * @return void
     */
    public function testMcryptCfb()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 39, 'The use of function mcrypt_cfb is discouraged from PHP version 5.5');
    }

    /**
     * Test mcrypt_ofb
     *
     * @return void
     */
    public function testMcryptOfb()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 40, 'The use of function mcrypt_ofb is discouraged from PHP version 5.5');
    }

    /**
     * Test ocibindbyname
     *
     * @return void
     */
    public function testOcibindbyname()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 41, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicancel
     *
     * @return void
     */
    public function testOcicancel()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 42, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicloselob
     *
     * @return void
     */
    public function testOcicloselob()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 43, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicollappend
     *
     * @return void
     */
    public function testOcicollappend()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 44, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicollassign
     *
     * @return void
     */
    public function testOcicollassign()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 45, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicollassignelem
     *
     * @return void
     */
    public function testOcicollassignelem()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 46, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicollgetelem
     *
     * @return void
     */
    public function testOcicollgetelem()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 47, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicollmax
     *
     * @return void
     */
    public function testOcicollmax()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 48, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicollsize
     *
     * @return void
     */
    public function testOcicollsize()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 49, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicolltrim
     *
     * @return void
     */
    public function testOcicolltrim()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 50, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicolumnisnull
     *
     * @return void
     */
    public function testOcicolumnisnull()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 51, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicolumnname
     *
     * @return void
     */
    public function testOcicolumnname()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 52, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicolumnprecision
     *
     * @return void
     */
    public function testOcicolumnprecision()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 53, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicolumnscale
     *
     * @return void
     */
    public function testOcicolumnscale()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 54, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicolumnsize
     *
     * @return void
     */
    public function testOcicolumnsize()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 55, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicolumntype
     *
     * @return void
     */
    public function testOcicolumntype()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 56, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicolumntyperaw
     *
     * @return void
     */
    public function testOcicolumntyperaw()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 57, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocicommit
     *
     * @return void
     */
    public function testOcicommit()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 58, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocidefinebyname
     *
     * @return void
     */
    public function testOcidefinebyname()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 59, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocierror
     *
     * @return void
     */
    public function testOcierror()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 60, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ociexecute
     *
     * @return void
     */
    public function testOciexecute()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 61, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocifetch
     *
     * @return void
     */
    public function testOcifetch()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 62, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocifetchinto
     *
     * @return void
     */
    public function testOcifetchinto()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 63, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocifetchstatement
     *
     * @return void
     */
    public function testOcifetchstatement()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 64, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocifreecollection
     *
     * @return void
     */
    public function testOcifreecollection()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 65, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocifreecursor
     *
     * @return void
     */
    public function testOcifreecursor()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 66, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocifreedesc
     *
     * @return void
     */
    public function testOcifreedesc()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 67, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocifreestatement
     *
     * @return void
     */
    public function testOcifreestatement()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 68, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ociinternaldebug
     *
     * @return void
     */
    public function testOciinternaldebug()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 69, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ociloadlob
     *
     * @return void
     */
    public function testOciloadlob()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 70, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocilogoff
     *
     * @return void
     */
    public function testOcilogoff()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 71, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocilogon
     *
     * @return void
     */
    public function testOcilogon()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 72, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocinewcollection
     *
     * @return void
     */
    public function testOcinewcollection()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 73, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocinewcursor
     *
     * @return void
     */
    public function testOcinewcursor()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 74, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocinewdescriptor
     *
     * @return void
     */
    public function testOcinewdescriptor()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 75, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocinlogon
     *
     * @return void
     */
    public function testOcinlogon()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 76, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocinumcols
     *
     * @return void
     */
    public function testOcinumcols()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 77, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ociparse
     *
     * @return void
     */
    public function testOciparse()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 78, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ociplogon
     *
     * @return void
     */
    public function testOciplogon()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 79, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ociresult
     *
     * @return void
     */
    public function testOciresult()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 80, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocirollback
     *
     * @return void
     */
    public function testOcirollback()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 81, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocirowcount
     *
     * @return void
     */
    public function testOcirowcount()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 82, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocisavelob
     *
     * @return void
     */
    public function testOcisavelob()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 83, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocisavelobfile
     *
     * @return void
     */
    public function testOcisavelobfile()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 84, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ociserverversion
     *
     * @return void
     */
    public function testOciserverversion()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 85, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocisetprefetch
     *
     * @return void
     */
    public function testOcisetprefetch()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 86, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ocistatementtype
     *
     * @return void
     */
    public function testOcistatementtype()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 87, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ociwritelobtofile
     *
     * @return void
     */
    public function testOciwritelobtofile()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 88, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
    }

    /**
     * Test ociwritetemporarylob
     *
     * @return void
     */
    public function testOciwritetemporarylob()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_functions.php');

        $this->assertWarning($file, 89, 'The use of function mcrypt_ofb is discouraged from PHP version 5.4');
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

