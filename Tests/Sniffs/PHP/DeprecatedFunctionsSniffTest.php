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

