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

    const TEST_FILE = 'sniff-examples/deprecated_functions.php';


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
     * return void
     */
    public function testDeprecatedFunction($functionName, $deprecatedIn, $lines, $okVersion, $deprecatedVersion = null)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        if (isset($deprecatedVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $deprecatedVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $deprecatedIn);
        }
        foreach($lines as $line) {
            $this->assertWarning($file, $line, "The use of function {$functionName} is discouraged from PHP version {$deprecatedIn}");
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
        return array(
            array('dl', '5.3', array(6), '5.2'),
            array('ocifetchinto', '5.4', array(63), '5.3'),
            array('ldap_sort', '7.0', array(97), '5.6'),
        );
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
     * return void
     */
    public function testDeprecatedFunctionWithAlternative($functionName, $deprecatedIn, $alternative, $lines, $okVersion, $deprecatedVersion = null)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        if (isset($deprecatedVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $deprecatedVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $deprecatedIn);
        }
        foreach($lines as $line) {
            $this->assertWarning($file, $line, "The use of function {$functionName} is discouraged from PHP version {$deprecatedIn}; use {$alternative} instead");
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
        return array(
            array('ocibindbyname', '5.4', 'oci_bind_by_name', array(41), '5.3'),
            array('ocicancel', '5.4', 'oci_cancel', array(42), '5.3'),
            array('ocicloselob', '5.4', 'OCI-Lob::close', array(43), '5.3'),
            array('ocicollappend', '5.4', 'OCI-Collection::append', array(44), '5.3'),
            array('ocicollassign', '5.4', 'OCI-Collection::assign', array(45), '5.3'),
            array('ocicollassignelem', '5.4', 'OCI-Collection::assignElem', array(46), '5.3'),
            array('ocicollgetelem', '5.4', 'OCI-Collection::getElem', array(47), '5.3'),
            array('ocicollmax', '5.4', 'OCI-Collection::max', array(48), '5.3'),
            array('ocicollsize', '5.4', 'OCI-Collection::size', array(49), '5.3'),
            array('ocicolltrim', '5.4', 'OCI-Collection::trim', array(50), '5.3'),
            array('ocicolumnisnull', '5.4', 'oci_field_is_null', array(51), '5.3'),
            array('ocicolumnname', '5.4', 'oci_field_name', array(52), '5.3'),
            array('ocicolumnprecision', '5.4', 'oci_field_precision', array(53), '5.3'),
            array('ocicolumnscale', '5.4', 'oci_field_scale', array(54), '5.3'),
            array('ocicolumnsize', '5.4', 'oci_field_size', array(55), '5.3'),
            array('ocicolumntype', '5.4', 'oci_field_type', array(56), '5.3'),
            array('ocicolumntyperaw', '5.4', 'oci_field_type_raw', array(57), '5.3'),
            array('ocicommit', '5.4', 'oci_commit', array(58), '5.3'),
            array('ocidefinebyname', '5.4', 'oci_define_by_name', array(59), '5.3'),
            array('ocierror', '5.4', 'oci_error', array(60), '5.3'),
            array('ociexecute', '5.4', 'oci_execute', array(61), '5.3'),
            array('ocifetch', '5.4', 'oci_fetch', array(62), '5.3'),
            array('ocifetchstatement', '5.4', 'oci_fetch_all', array(64), '5.3'),
            array('ocifreecollection', '5.4', 'OCI-Collection::free', array(65), '5.3'),
            array('ocifreecursor', '5.4', 'oci_free_statement', array(66), '5.3'),
            array('ocifreedesc', '5.4', 'OCI-Lob::free', array(67), '5.3'),
            array('ocifreestatement', '5.4', 'oci_free_statement', array(68), '5.3'),
            array('ociinternaldebug', '5.4', 'oci_internal_debug', array(69), '5.3'),
            array('ociloadlob', '5.4', 'OCI-Lob::load', array(70), '5.3'),
            array('ocilogoff', '5.4', 'oci_close', array(71), '5.3'),
            array('ocilogon', '5.4', 'oci_connect', array(72), '5.3'),
            array('ocinewcollection', '5.4', 'oci_new_collection', array(73), '5.3'),
            array('ocinewcursor', '5.4', 'oci_new_cursor', array(74), '5.3'),
            array('ocinewdescriptor', '5.4', 'oci_new_descriptor', array(75), '5.3'),
            array('ocinlogon', '5.4', 'oci_new_connect', array(76), '5.3'),
            array('ocinumcols', '5.4', 'oci_num_fields', array(77), '5.3'),
            array('ociparse', '5.4', 'oci_parse', array(78), '5.3'),
            array('ociplogon', '5.4', 'oci_pconnect', array(79), '5.3'),
            array('ociresult', '5.4', 'oci_result', array(80), '5.3'),
            array('ocirollback', '5.4', 'oci_rollback', array(81), '5.3'),
            array('ocirowcount', '5.4', 'oci_num_rows', array(82), '5.3'),
            array('ocisavelob', '5.4', 'OCI-Lob::save', array(83), '5.3'),
            array('ocisavelobfile', '5.4', 'OCI-Lob::import', array(84), '5.3'),
            array('ociserverversion', '5.4', 'oci_server_version', array(85), '5.3'),
            array('ocisetprefetch', '5.4', 'oci_set_prefetch', array(86), '5.3'),
            array('ocistatementtype', '5.4', 'oci_statement_type', array(87), '5.3'),
            array('ociwritelobtofile', '5.4', 'OCI-Lob::export', array(88), '5.3'),
            array('ociwritetemporarylob', '5.4', 'OCI-Lob::writeTemporary', array(89), '5.3'),

        );
    }


    /**
     * testForbiddenFunction
     *
     * @dataProvider dataForbiddenFunction
     *
     * @param string $functionName      Name of the function.
     * @param string $forbiddenIn       The PHP version in which the function was forbidden.
     * @param array  $lines             The line numbers in the test file which apply to this function.
     * @param string $okVersion         A PHP version in which the function was still valid.
     * @param string $forbiddenVersion  Optional PHP version to test forbidden message with -
     *                                  if different from the $forbiddenIn version.
     *
     * return void
     */
    public function testForbiddenFunction($functionName, $forbiddenIn, $lines, $okVersion, $forbiddenVersion = null)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        if (isset($forbiddenVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $forbiddenVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $forbiddenIn);
        }
        foreach($lines as $line) {
            $this->assertError($file, $line, "The use of function {$functionName} is forbidden from PHP version {$forbiddenIn}");
        }
    }

    /**
     * Data provider.
     *
     * @see testForbiddenFunction()
     *
     * @return array
     */
    public function dataForbiddenFunction()
    {
        return array(
            array('php_logo_guid', '5.5', array(32), '5.4'),
            array('php_egg_logo_guid', '5.5', array(33), '5.4'),
            array('php_real_logo_guid', '5.5', array(34), '5.4'),
            array('zend_logo_guid', '5.5', array(35), '5.4'),
            array('imagepsbbox', '7.0', array(90), '5.6'),
            array('imagepsencodefont', '7.0', array(91), '5.6'),
            array('imagepsextendfont', '7.0', array(92), '5.6'),
            array('imagepsfreefont', '7.0', array(93), '5.6'),
            array('imagepsloadfont', '7.0', array(94), '5.6'),
            array('imagepsslantfont', '7.0', array(95), '5.6'),
            array('imagepstext', '7.0', array(96), '5.6'),
            array('php_check_syntax', '5.0.5', array(98), '5.0', '5.1'),
            array('mysqli_get_cache_stats', '5.4', array(99), '5.3'),

        );
    }


    /**
     * testDeprecatedForbiddenFunction
     *
     * @dataProvider dataDeprecatedForbiddenFunction
     *
     * @param string $functionName      Name of the function.
     * @param string $deprecatedIn      The PHP version in which the function was deprecated.
     * @param string $forbiddenIn       The PHP version in which the function was forbidden.
     * @param array  $lines             The line numbers in the test file which apply to this function.
     * @param string $okVersion         A PHP version in which the function was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     * @param string $forbiddenVersion  Optional PHP version to test forbidden message with -
     *                                  if different from the $forbiddenIn version.
     *
     * return void
     */
    public function testDeprecatedForbiddenFunction($functionName, $deprecatedIn, $forbiddenIn, $lines, $okVersion, $deprecatedVersion = null, $forbiddenVersion = null)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        if (isset($deprecatedVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $deprecatedVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $deprecatedIn);
        }
        foreach($lines as $line) {
            $this->assertWarning($file, $line, "The use of function {$functionName} is discouraged from PHP version {$deprecatedIn}");
        }

        if (isset($forbiddenVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $forbiddenVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $forbiddenIn);
        }
        foreach($lines as $line) {
            $this->assertError($file, $line, "The use of function {$functionName} is discouraged from PHP version {$deprecatedIn} and forbidden from PHP version {$forbiddenIn}");
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedForbiddenFunction()
     *
     * @return array
     */
    public function dataDeprecatedForbiddenFunction()
    {
        return array(
            array('define_syslog_variables', '5.3', '5.4', array(5), '5.2'),
            array('import_request_variables', '5.3', '5.4', array(11), '5.2'),
            array('mysql_list_dbs', '5.4', '7.0', array(15), '5.3'),
            array('magic_quotes_runtime', '5.3', '7.0', array(23), '5.2'),
            array('set_magic_quotes_runtime', '5.3', '7.0', array(27), '5.2'),
            array('sql_regcase', '5.3', '7.0', array(31), '5.2'),
            array('mcrypt_ecb', '5.5', '7.0', array(37), '5.4'),
            array('mcrypt_cbc', '5.5', '7.0', array(38), '5.4'),
            array('mcrypt_cfb', '5.5', '7.0', array(39), '5.4'),
            array('mcrypt_ofb', '5.5', '7.0', array(40), '5.4'),

        );
    }


    /**
     * testDeprecatedForbiddenFunctionWithAlternative
     *
     * @dataProvider dataDeprecatedForbiddenFunctionWithAlternative
     *
     * @param string $functionName      Name of the function.
     * @param string $deprecatedIn      The PHP version in which the function was deprecated.
     * @param string $forbiddenIn       The PHP version in which the function was forbidden.
     * @param string $alternative       An alternative function.
     * @param array  $lines             The line numbers in the test file which apply to this function.
     * @param string $okVersion         A PHP version in which the function was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     * @param string $forbiddenVersion  Optional PHP version to test forbidden message with -
     *                                  if different from the $forbiddenIn version.
     *
     * return void
     */
    public function testDeprecatedForbiddenFunctionWithAlternative($functionName, $deprecatedIn, $forbiddenIn, $alternative, $lines, $okVersion, $deprecatedVersion = null, $forbiddenVersion = null)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        if (isset($deprecatedVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $deprecatedVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $deprecatedIn);
        }
        foreach($lines as $line) {
            $this->assertWarning($file, $line, "The use of function {$functionName} is discouraged from PHP version {$deprecatedIn}; use {$alternative} instead");
        }

        if (isset($forbiddenVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $forbiddenVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $forbiddenIn);
        }
        foreach($lines as $line) {
            $this->assertError($file, $line, "The use of function {$functionName} is discouraged from PHP version {$deprecatedIn} and forbidden from PHP version {$forbiddenIn}; use {$alternative} instead");
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedForbiddenFunctionWithAlternative()
     *
     * @return array
     */
    public function dataDeprecatedForbiddenFunctionWithAlternative()
    {
        return array(
            array('call_user_method', '5.3', '7.0', 'call_user_func', array(3), '5.2'),
            array('call_user_method_array', '5.3', '7.0', 'call_user_func_array', array(4), '5.2'),
            array('ereg', '5.3', '7.0', 'preg_match', array(7), '5.2'),
            array('ereg_replace', '5.3', '7.0', 'preg_replace', array(8), '5.2'),
            array('eregi', '5.3', '7.0', 'preg_match', array(9), '5.2'),
            array('eregi_replace', '5.3', '7.0', 'preg_replace', array(10), '5.2'),
            array('mcrypt_generic_end', '5.4', '7.0', 'mcrypt_generic_deinit', array(12), '5.3'),
            array('mysql_db_query', '5.3', '7.0', 'mysqli_select_db and mysqli_query', array(13), '5.2'),
            array('mysql_escape_string', '5.3', '7.0', 'mysqli_real_escape_string', array(14), '5.2'),
            array('mysqli_bind_param', '5.3', '5.4', 'mysqli_stmt_bind_param', array(16), '5.2'),
            array('mysqli_bind_result', '5.3', '5.4', 'mysqli_stmt_bind_result', array(17), '5.2'),
            array('mysqli_client_encoding', '5.3', '5.4', 'mysqli_character_set_name', array(18), '5.2'),
            array('mysqli_fetch', '5.3', '5.4', 'mysqli_stmt_fetch', array(19), '5.2'),
            array('mysqli_param_count', '5.3', '5.4', 'mysqli_stmt_param_count', array(20), '5.2'),
            array('mysqli_get_metadata', '5.3', '5.4', 'mysqli_stmt_result_metadata', array(21), '5.2'),
            array('mysqli_send_long_data', '5.3', '5.4', 'mysqli_stmt_send_long_data', array(22), '5.2'),
            array('session_register', '5.3', '5.4', '$_SESSION', array(24), '5.2'),
            array('session_unregister', '5.3', '5.4', '$_SESSION', array(25), '5.2'),
            array('session_is_registered', '5.3', '5.4', '$_SESSION', array(26), '5.2'),
            array('set_socket_blocking', '5.3', '7.0', 'stream_set_blocking', array(28), '5.2'),
            array('split', '5.3', '7.0', 'preg_split', array(29), '5.2'),
            array('spliti', '5.3', '7.0', 'preg_split', array(30), '5.2'),
            array('datefmt_set_timezone_id', '5.5', '7.0', 'datefmt_set_timezone', array(36), '5.4'),

        );
    }

}
