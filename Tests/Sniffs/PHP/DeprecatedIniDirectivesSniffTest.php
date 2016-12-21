<?php
/**
 * Deprecated ini directives sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Deprecated ini directives sniff tests
 *
 * @group deprecatedIniDirectives
 * @group iniDirectives
 *
 * @covers PHPCompatibility_Sniffs_PHP_DeprecatedIniDirectivesSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class DeprecatedIniDirectivesSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/deprecated_ini_directives.php';

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
        $file = $this->sniffFile(self::TEST_FILE);
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
        return array(
            array(57),
            array(58),
            array(133),
            array(155),
            array(156),
            array(159),
            array(160),
            array(163),
            array(164),
        );
    }


    /**
     * testDeprecatedRemovedDirectives
     *
     * @dataProvider dataDeprecatedRemovedDirectives
     *
     * @param string $iniName           Name of the ini directive.
     * @param string $deprecatedIn      The PHP version in which the ini directive was deprecated.
     * @param string $removedIn         The PHP version in which the ini directive was removed.
     * @param array  $lines             The line numbers in the test file which apply to this ini directive.
     * @param string $okVersion         A PHP version in which the ini directive was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     * @param string $removedVersion    Optional PHP version to test removed message with -
     *                                  if different from the $removedIn version.
     *
     * @return void
     */
    public function testDeprecatedRemovedDirectives($iniName, $deprecatedIn, $removedIn, $lines, $okVersion, $deprecatedVersion = null, $removedVersion = null)
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
            $this->assertWarning($file, $line, "INI directive '{$iniName}' is deprecated since PHP {$deprecatedIn}");
        }

        if (isset($removedVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $removedVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $removedIn);
        }
        $this->assertError($file, $lines[0], "INI directive '{$iniName}' is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}");
        $this->assertWarning($file, $lines[1], "INI directive '{$iniName}' is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}");
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedRemovedDirectives()
     *
     * @return array
     */
    public function dataDeprecatedRemovedDirectives()
    {
        return array(
            array('define_syslog_variables', '5.3', '5.4', array(3, 4), '5.2'),
            array('register_globals', '5.3', '5.4', array(6, 7), '5.2'),
            array('register_long_arrays', '5.3', '5.4', array(9, 10), '5.2'),
            array('magic_quotes_gpc', '5.3', '5.4', array(12, 13), '5.2'),
            array('magic_quotes_runtime', '5.3', '5.4', array(15, 16), '5.2'),
            array('magic_quotes_sybase', '5.3', '5.4', array(18, 19), '5.2'),
            array('allow_call_time_pass_reference', '5.3', '5.4', array(21, 22), '5.2'),
            array('highlight.bg', '5.3', '5.4', array(24, 25), '5.2'),
            array('session.bug_compat_42', '5.3', '5.4', array(27, 28), '5.2'),
            array('session.bug_compat_warn', '5.3', '5.4', array(30, 31), '5.2'),
            array('y2k_compliance', '5.3', '5.4', array(33, 34), '5.2'),
            array('safe_mode', '5.3', '5.4', array(39, 40), '5.2'),
            array('safe_mode_gid', '5.3', '5.4', array(42, 43), '5.2'),
            array('safe_mode_include_dir', '5.3', '5.4', array(45, 46), '5.2'),
            array('safe_mode_exec_dir', '5.3', '5.4', array(48, 49), '5.2'),
            array('safe_mode_allowed_env_vars', '5.3', '5.4', array(51, 52), '5.2'),
            array('safe_mode_protected_env_vars', '5.3', '5.4', array(54, 55), '5.2'),

            array('always_populate_raw_post_data', '5.6', '7.0', array(80, 81), '5.5'),
        );
    }


    /**
     * testDeprecatedDirectives
     *
     * @dataProvider dataDeprecatedDirectives
     *
     * @param string $iniName           Name of the ini directive.
     * @param string $deprecatedIn      The PHP version in which the ini directive was deprecated.
     * @param array  $lines             The line numbers in the test file which apply to this ini directive.
     * @param string $okVersion         A PHP version in which the ini directive was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     *
     * @return void
     */
    public function testDeprecatedDirectives($iniName, $deprecatedIn, $lines, $okVersion, $deprecatedVersion = null)
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
            $this->assertWarning($file, $line, "INI directive '{$iniName}' is deprecated since PHP {$deprecatedIn}");
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedDirectives()
     *
     * @return array
     */
    public function dataDeprecatedDirectives()
    {
        return array(
            array('safe_mode_protected_env_vars', '5.3', array(54, 55), '5.2'),

            array('iconv.input_encoding', '5.6', array(62, 63), '5.5'),
            array('iconv.output_encoding', '5.6', array(65, 66), '5.5'),
            array('iconv.internal_encoding', '5.6', array(68, 69), '5.5'),
            array('mbstring.http_input', '5.6', array(71, 72), '5.5'),
            array('mbstring.http_output', '5.6', array(74, 75), '5.5'),
            array('mbstring.internal_encoding', '5.6', array(77, 78), '5.5'),

            array('mcrypt.algorithms_dir', '7.1', array(135, 136), '7.0'),
            array('mcrypt.modes_dir', '7.1', array(138, 139), '7.0'),
        );
    }



    /**
     * testRemovedWithAlternative
     *
     * @dataProvider dataRemovedWithAlternative
     *
     * @param string $iniName        Name of the ini directive.
     * @param string $removedIn      The PHP version in which the ini directive was removed.
     * @param string $alternative    An alternative ini directive for the removed directive.
     * @param array  $lines          The line numbers in the test file which apply to this ini directive.
     * @param string $okVersion      A PHP version in which the ini directive was still valid.
     * @param string $removedVersion Optional PHP version to test removed message with -
     *                               if different from the $removedIn version.
     *
     * @return void
     */
    public function testRemovedWithAlternative($iniName, $removedIn, $alternative, $lines, $okVersion, $removedVersion = null)
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
        $this->assertError($file, $lines[0], "INI directive '{$iniName}' is removed since PHP {$removedIn}; Use '{$alternative}' instead");
        $this->assertWarning($file, $lines[1], "INI directive '{$iniName}' is removed since PHP {$removedIn}; Use '{$alternative}' instead");
    }

    /**
     * Data provider.
     *
     * @see testRemovedWithAlternative()
     *
     * @return array
     */
    public function dataRemovedWithAlternative()
    {
        return array(
            array('fbsql.batchSize', '5.1', 'fbsql.batchsize', array(89, 90), '5.0'),
            array('detect_unicode', '5.4', 'zend.detect_unicode', array(125, 126), '5.3'),
            array('mbstring.script_encoding', '5.4', 'zend.script_encoding', array(128, 129), '5.3'),
        );
    }

    /**
     * testRemovedDirectives
     *
     * @dataProvider dataRemovedDirectives
     *
     * @param string $iniName        Name of the ini directive.
     * @param string $removedIn      The PHP version in which the ini directive was removed.
     * @param array  $lines          The line numbers in the test file which apply to this ini directive.
     * @param string $okVersion      A PHP version in which the ini directive was still valid.
     * @param string $removedVersion Optional PHP version to test removed message with -
     *                               if different from the $removedIn version.
     *
     * @return void
     */
    public function testRemovedDirectives($iniName, $removedIn, $lines, $okVersion, $removedVersion = null)
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
        $this->assertError($file, $lines[0], "INI directive '{$iniName}' is removed since PHP {$removedIn}");
        $this->assertWarning($file, $lines[1], "INI directive '{$iniName}' is removed since PHP {$removedIn}");
    }

    /**
     * Data provider.
     *
     * @see testRemovedDirectives()
     *
     * @return array
     */
    public function dataRemovedDirectives()
    {
        return array(
            array('ifx.allow_persistent', '5.2.1', array(92, 93), '5.1', '5.3'),
            array('ifx.blobinfile', '5.2.1', array(95, 96), '5.1', '5.3'),
            array('ifx.byteasvarchar', '5.2.1', array(98, 99), '5.1', '5.3'),
            array('ifx.charasvarchar', '5.2.1', array(101, 102), '5.1', '5.3'),
            array('ifx.default_host', '5.2.1', array(104, 105), '5.1', '5.3'),
            array('ifx.default_password', '5.2.1', array(107, 108), '5.1', '5.3'),
            array('ifx.default_user', '5.2.1', array(110, 111), '5.1', '5.3'),
            array('ifx.max_links', '5.2.1', array(113, 114), '5.1', '5.3'),
            array('ifx.max_persistent', '5.2.1', array(116, 117), '5.1', '5.3'),
            array('ifx.nullformat', '5.2.1', array(119, 120), '5.1', '5.3'),
            array('ifx.textasvarchar', '5.2.1', array(122, 123), '5.1', '5.3'),

            array('zend.ze1_compatibility_mode', '5.3', array(36, 37), '5.2'),

            array('asp_tags', '7.0', array(83, 84), '5.6'),
            array('xsl.security_prefs', '7.0', array(86, 87), '5.6'),

            array('session.entropy_file', '7.1', array(141, 142), '7.0'),
            array('session.entropy_length', '7.1', array(144, 145), '7.0'),
            array('session.hash_function', '7.1', array(147, 148), '7.0'),
            array('session.hash_bits_per_character', '7.1', array(150, 151), '7.0'),
        );
    }

}
