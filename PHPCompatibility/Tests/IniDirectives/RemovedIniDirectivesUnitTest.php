<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\IniDirectives;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the RemovedIniDirectives sniff.
 *
 * @group removedIniDirectives
 * @group iniDirectives
 *
 * @covers \PHPCompatibility\Sniffs\IniDirectives\RemovedIniDirectivesSniff
 *
 * @since 5.5
 */
class RemovedIniDirectivesUnitTest extends BaseSniffTest
{

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
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "INI directive '{$iniName}' is deprecated since PHP {$deprecatedIn}";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "INI directive '{$iniName}' is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}";
        $this->assertError($file, $lines[0], $error);
        $this->assertWarning($file, $lines[1], $error);
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

            array('mcrypt.algorithms_dir', '7.1', '7.2', array(135, 136), '7.0'),
            array('mcrypt.modes_dir', '7.1', '7.2', array(138, 139), '7.0'),

            array('mbstring.func_overload', '7.2', '8.0', array(166, 167), '7.1'),

            array('opcache.inherited_hack', '5.3', '7.3', array(181, 182), '5.2'),

            array('track_errors', '7.2', '8.0', array(172, 173), '7.1'),
            array('pdo_odbc.db2_instance_name', '7.3', '8.0', array(184, 185), '7.2'),
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
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "INI directive '{$iniName}' is deprecated since PHP {$deprecatedIn}";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
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

            array('allow_url_include', '7.4', array(238, 239), '7.3'),
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
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "INI directive '{$iniName}' is removed since PHP {$removedIn}; Use '{$alternative}' instead";
        $this->assertError($file, $lines[0], $error);
        $this->assertWarning($file, $lines[1], $error);
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
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "INI directive '{$iniName}' is removed since PHP {$removedIn}";
        $this->assertError($file, $lines[0], $error);
        $this->assertWarning($file, $lines[1], $error);
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
            array('crack.default_dictionary', '5.0', array(256, 257), '4.4'),

            array('dbx.colnames_case', '5.1', array(259, 260), '5.0'),
            array('ingres.allow_persistent', '5.1', array(298, 299), '5.0'),
            array('ingres.default_database', '5.1', array(301, 302), '5.0'),
            array('ingres.default_password', '5.1', array(304, 305), '5.0'),
            array('ingres.default_user', '5.1', array(307, 308), '5.0'),
            array('ingres.max_links', '5.1', array(310, 311), '5.0'),
            array('ingres.max_persistent', '5.1', array(313, 314), '5.0'),

            array('hwapi.allow_persistent', '5.2', array(253, 254), '5.1'),

            array('ifx.allow_persistent', '5.2.1', array(92, 93), '5.2', '5.3'),
            array('ifx.blobinfile', '5.2.1', array(95, 96), '5.2', '5.3'),
            array('ifx.byteasvarchar', '5.2.1', array(98, 99), '5.2', '5.3'),
            array('ifx.charasvarchar', '5.2.1', array(101, 102), '5.2', '5.3'),
            array('ifx.default_host', '5.2.1', array(104, 105), '5.2', '5.3'),
            array('ifx.default_password', '5.2.1', array(107, 108), '5.2', '5.3'),
            array('ifx.default_user', '5.2.1', array(110, 111), '5.2', '5.3'),
            array('ifx.max_links', '5.2.1', array(113, 114), '5.2', '5.3'),
            array('ifx.max_persistent', '5.2.1', array(116, 117), '5.2', '5.3'),
            array('ifx.nullformat', '5.2.1', array(119, 120), '5.2', '5.3'),
            array('ifx.textasvarchar', '5.2.1', array(122, 123), '5.2', '5.3'),

            array('mime_magic.debug', '5.3', array(247, 248), '5.2'),
            array('mime_magic.magicfile', '5.3', array(250, 251), '5.2'),
            array('zend.ze1_compatibility_mode', '5.3', array(36, 37), '5.2'),
            array('msql.allow_persistent', '5.3', array(316, 317), '5.2'),
            array('msql.max_persistent', '5.3', array(319, 320), '5.2'),
            array('msql.max_links', '5.3', array(322, 323), '5.2'),

            array('fbsql.allow_persistent', '5.3', array(262, 263), '5.2'),
            array('fbsql.generate_warnings', '5.3', array(265, 266), '5.2'),
            array('fbsql.autocommit', '5.3', array(268, 269), '5.2'),
            array('fbsql.max_persistent', '5.3', array(271, 272), '5.2'),
            array('fbsql.max_links', '5.3', array(274, 275), '5.2'),
            array('fbsql.max_connections', '5.3', array(277, 278), '5.2'),
            array('fbsql.max_results', '5.3', array(280, 281), '5.2'),
            array('fbsql.default_host', '5.3', array(283, 284), '5.2'),
            array('fbsql.default_user', '5.3', array(286, 287), '5.2'),
            array('fbsql.default_password', '5.3', array(289, 290), '5.2'),
            array('fbsql.default_database', '5.3', array(292, 293), '5.2'),
            array('fbsql.default_database_password', '5.3', array(295, 296), '5.2'),

            array('phar.extract_list', '5.4', array(244, 245), '5.3'),
            array('sqlite.assoc_case', '5.4', array(370, 371), '5.3'),

            array('asp_tags', '7.0', array(83, 84), '5.6'),
            array('xsl.security_prefs', '7.0', array(86, 87), '5.6'),
            array('opcache.load_comments', '7.0', array(241, 242), '5.6'),
            array('mssql.allow_persistent', '7.0', array(325, 326), '5.6'),
            array('mssql.max_persistent', '7.0', array(328, 329), '5.6'),
            array('mssql.max_links', '7.0', array(331, 332), '5.6'),
            array('mssql.min_error_severity', '7.0', array(334, 335), '5.6'),
            array('mssql.min_message_severity', '7.0', array(337, 338), '5.6'),
            array('mssql.compatibility_mode', '7.0', array(340, 341), '5.6'),
            array('mssql.connect_timeout', '7.0', array(343, 344), '5.6'),
            array('mssql.timeout', '7.0', array(346, 347), '5.6'),
            array('mssql.textsize', '7.0', array(349, 350), '5.6'),
            array('mssql.textlimit', '7.0', array(352, 353), '5.6'),
            array('mssql.batchsize', '7.0', array(355, 356), '5.6'),
            array('mssql.datetimeconvert', '7.0', array(358, 359), '5.6'),
            array('mssql.secure_connection', '7.0', array(361, 362), '5.6'),
            array('mssql.max_procs', '7.0', array(364, 365), '5.6'),
            array('mssql.charset', '7.0', array(367, 368), '5.6'),
            array('mysql.allow_local_infile', '7.0', array(373, 374), '5.6'),
            array('mysql.allow_persistent', '7.0', array(376, 377), '5.6'),
            array('mysql.max_persistent', '7.0', array(379, 380), '5.6'),
            array('mysql.max_links', '7.0', array(382, 383), '5.6'),
            array('mysql.trace_mode', '7.0', array(385, 386), '5.6'),
            array('mysql.default_port', '7.0', array(388, 389), '5.6'),
            array('mysql.default_socket', '7.0', array(391, 392), '5.6'),
            array('mysql.default_host', '7.0', array(394, 395), '5.6'),
            array('mysql.default_user', '7.0', array(397, 398), '5.6'),
            array('mysql.default_password', '7.0', array(400, 401), '5.6'),
            array('mysql.connect_timeout', '7.0', array(403, 404), '5.6'),
            array('sybase.allow_persistent', '7.0', array(406, 407), '5.6'),
            array('sybase.max_persistent', '7.0', array(409, 410), '5.6'),
            array('sybase.max_links', '7.0', array(412, 413), '5.6'),
            array('sybase.interface_file', '7.0', array(415, 416), '5.6'),
            array('sybase.min_error_severity', '7.0', array(418, 419), '5.6'),
            array('sybase.min_message_severity', '7.0', array(421, 422), '5.6'),
            array('sybase.compatability_mode', '7.0', array(424, 425), '5.6'),

            array('session.entropy_file', '7.1', array(141, 142), '7.0'),
            array('session.entropy_length', '7.1', array(144, 145), '7.0'),
            array('session.hash_function', '7.1', array(147, 148), '7.0'),
            array('session.hash_bits_per_character', '7.1', array(150, 151), '7.0'),

            array('sql.safe_mode', '7.2', array(169, 170), '7.1'),
            array('opcache.fast_shutdown', '7.2', array(175, 176), '7.1'),

            array('birdstep.max_links', '7.3', array(178, 179), '7.2'),

            array('ibase.allow_persistent', '7.4', array(187, 188), '7.3'),
            array('ibase.max_persistent', '7.4', array(190, 191), '7.3'),
            array('ibase.max_links', '7.4', array(193, 194), '7.3'),
            array('ibase.default_db', '7.4', array(196, 197), '7.3'),
            array('ibase.default_user', '7.4', array(199, 200), '7.3'),
            array('ibase.default_password', '7.4', array(202, 203), '7.3'),
            array('ibase.default_charset', '7.4', array(205, 206), '7.3'),
            array('ibase.timestampformat', '7.4', array(208, 209), '7.3'),
            array('ibase.dateformat', '7.4', array(211, 212), '7.3'),
            array('ibase.timeformat', '7.4', array(214, 215), '7.3'),

            array('pfpro.defaulthost', '5.1', array(217, 218), '5.0'),
            array('pfpro.defaultport', '5.1', array(220, 221), '5.0'),
            array('pfpro.defaulttimeout', '5.1', array(223, 224), '5.0'),
            array('pfpro.proxyaddress', '5.1', array(226, 227), '5.0'),
            array('pfpro.proxyport', '5.1', array(229, 230), '5.0'),
            array('pfpro.proxylogon', '5.1', array(232, 233), '5.0'),
            array('pfpro.proxypassword', '5.1', array(235, 236), '5.0'),
        );
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
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '4.4'); // Low version below the first deprecation.
        $this->assertNoViolation($file);
    }
}
