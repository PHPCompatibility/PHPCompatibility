<?php
/**
 * Deprecated functions sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Deprecated functions sniff tests
 *
 * @group deprecatedFunctions
 * @group functions
 *
 * @covers PHPCompatibility_Sniffs_PHP_DeprecatedFunctionsSniff
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
            $this->assertWarning($file, $line, "Function {$functionName}() is deprecated since PHP {$deprecatedIn}");
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
            $this->assertWarning($file, $line, "Function {$functionName}() is deprecated since PHP {$deprecatedIn}; Use {$alternative} instead");
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

            array('mcrypt_create_iv', '7.1', 'OpenSSL', array(100), '7.0'),
            array('mcrypt_decrypt', '7.1', 'OpenSSL', array(101), '7.0'),
            array('mcrypt_enc_get_algorithms_name', '7.1', 'OpenSSL', array(102), '7.0'),
            array('mcrypt_enc_get_block_size', '7.1', 'OpenSSL', array(103), '7.0'),
            array('mcrypt_enc_get_iv_size', '7.1', 'OpenSSL', array(104), '7.0'),
            array('mcrypt_enc_get_key_size', '7.1', 'OpenSSL', array(105), '7.0'),
            array('mcrypt_enc_get_modes_name', '7.1', 'OpenSSL', array(106), '7.0'),
            array('mcrypt_enc_get_supported_key_sizes', '7.1', 'OpenSSL', array(107), '7.0'),
            array('mcrypt_enc_is_block_algorithm_mode', '7.1', 'OpenSSL', array(108), '7.0'),
            array('mcrypt_enc_is_block_algorithm', '7.1', 'OpenSSL', array(109), '7.0'),
            array('mcrypt_enc_is_block_mode', '7.1', 'OpenSSL', array(110), '7.0'),
            array('mcrypt_enc_self_test', '7.1', 'OpenSSL', array(111), '7.0'),
            array('mcrypt_encrypt', '7.1', 'OpenSSL', array(112), '7.0'),
            array('mcrypt_generic_deinit', '7.1', 'OpenSSL', array(113), '7.0'),
            array('mcrypt_generic_init', '7.1', 'OpenSSL', array(114), '7.0'),
            array('mcrypt_generic', '7.1', 'OpenSSL', array(115), '7.0'),
            array('mcrypt_get_block_size', '7.1', 'OpenSSL', array(116), '7.0'),
            array('mcrypt_get_cipher_name', '7.1', 'OpenSSL', array(117), '7.0'),
            array('mcrypt_get_iv_size', '7.1', 'OpenSSL', array(118), '7.0'),
            array('mcrypt_get_key_size', '7.1', 'OpenSSL', array(119), '7.0'),
            array('mcrypt_list_algorithms', '7.1', 'OpenSSL', array(120), '7.0'),
            array('mcrypt_list_modes', '7.1', 'OpenSSL', array(121), '7.0'),
            array('mcrypt_module_close', '7.1', 'OpenSSL', array(122), '7.0'),
            array('mcrypt_module_get_algo_block_size', '7.1', 'OpenSSL', array(123), '7.0'),
            array('mcrypt_module_get_algo_key_size', '7.1', 'OpenSSL', array(124), '7.0'),
            array('mcrypt_module_get_supported_key_sizes', '7.1', 'OpenSSL', array(125), '7.0'),
            array('mcrypt_module_is_block_algorithm_mode', '7.1', 'OpenSSL', array(126), '7.0'),
            array('mcrypt_module_is_block_algorithm', '7.1', 'OpenSSL', array(127), '7.0'),
            array('mcrypt_module_is_block_mode', '7.1', 'OpenSSL', array(128), '7.0'),
            array('mcrypt_module_open', '7.1', 'OpenSSL', array(129), '7.0'),
            array('mcrypt_module_self_test', '7.1', 'OpenSSL', array(130), '7.0'),
            array('mdecrypt_generic', '7.1', 'OpenSSL', array(131), '7.0'),

        );
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
     * return void
     */
    public function testRemovedFunction($functionName, $removedIn, $lines, $okVersion, $removedVersion = null)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        if (isset($removedVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $removedVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $removedIn);
        }
        foreach($lines as $line) {
            $this->assertError($file, $line, "Function {$functionName}() is removed since PHP {$removedIn}");
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
     * return void
     */
    public function testDeprecatedRemovedFunction($functionName, $deprecatedIn, $removedIn, $lines, $okVersion, $deprecatedVersion = null, $removedVersion = null)
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
            $this->assertWarning($file, $line, "Function {$functionName}() is deprecated since PHP {$deprecatedIn}");
        }

        if (isset($removedVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $removedVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $removedIn);
        }
        foreach($lines as $line) {
            $this->assertError($file, $line, "Function {$functionName}() is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}");
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
     * return void
     */
    public function testDeprecatedRemovedFunctionWithAlternative($functionName, $deprecatedIn, $removedIn, $alternative, $lines, $okVersion, $deprecatedVersion = null, $removedVersion = null)
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
            $this->assertWarning($file, $line, "Function {$functionName}() is deprecated since PHP {$deprecatedIn}; Use {$alternative} instead");
        }

        if (isset($removedVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $removedVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $removedIn);
        }
        foreach($lines as $line) {
            $this->assertError($file, $line, "Function {$functionName}() is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}; Use {$alternative} instead");
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
