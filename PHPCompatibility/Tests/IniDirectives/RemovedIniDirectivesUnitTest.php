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
        return [
            ['define_syslog_variables', '5.3', '5.4', [3, 4], '5.2'],
            ['register_globals', '5.3', '5.4', [6, 7], '5.2'],
            ['register_long_arrays', '5.3', '5.4', [9, 10], '5.2'],
            ['magic_quotes_gpc', '5.3', '5.4', [12, 13], '5.2'],
            ['magic_quotes_runtime', '5.3', '5.4', [15, 16], '5.2'],
            ['magic_quotes_sybase', '5.3', '5.4', [18, 19], '5.2'],
            ['allow_call_time_pass_reference', '5.3', '5.4', [21, 22], '5.2'],
            ['highlight.bg', '5.3', '5.4', [24, 25], '5.2'],
            ['session.bug_compat_42', '5.3', '5.4', [27, 28], '5.2'],
            ['session.bug_compat_warn', '5.3', '5.4', [30, 31], '5.2'],
            ['y2k_compliance', '5.3', '5.4', [33, 34], '5.2'],
            ['safe_mode', '5.3', '5.4', [39, 40], '5.2'],
            ['safe_mode_gid', '5.3', '5.4', [42, 43], '5.2'],
            ['safe_mode_include_dir', '5.3', '5.4', [45, 46], '5.2'],
            ['safe_mode_exec_dir', '5.3', '5.4', [48, 49], '5.2'],
            ['safe_mode_allowed_env_vars', '5.3', '5.4', [51, 52], '5.2'],
            ['safe_mode_protected_env_vars', '5.3', '5.4', [54, 55], '5.2'],

            ['always_populate_raw_post_data', '5.6', '7.0', [80, 81], '5.5'],

            ['mcrypt.algorithms_dir', '7.1', '7.2', [135, 136], '7.0'],
            ['mcrypt.modes_dir', '7.1', '7.2', [138, 139], '7.0'],

            ['mbstring.func_overload', '7.2', '8.0', [166, 167], '7.1'],

            ['opcache.inherited_hack', '5.3', '7.3', [181, 182], '5.2'],

            ['track_errors', '7.2', '8.0', [172, 173], '7.1'],
            ['pdo_odbc.db2_instance_name', '7.3', '8.0', [184, 185], '7.2'],
        ];
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
        return [
            ['safe_mode_protected_env_vars', '5.3', [54, 55], '5.2'],

            ['iconv.input_encoding', '5.6', [62, 63], '5.5'],
            ['iconv.output_encoding', '5.6', [65, 66], '5.5'],
            ['iconv.internal_encoding', '5.6', [68, 69], '5.5'],
            ['mbstring.http_input', '5.6', [71, 72], '5.5'],
            ['mbstring.http_output', '5.6', [74, 75], '5.5'],
            ['mbstring.internal_encoding', '5.6', [77, 78], '5.5'],

            ['allow_url_include', '7.4', [238, 239], '7.3'],

            ['auto_detect_line_endings', '8.1', [430, 431], '8.0'],
        ];
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
        return [
            ['fbsql.batchSize', '5.1', 'fbsql.batchsize', [89, 90], '5.0'],
            ['detect_unicode', '5.4', 'zend.detect_unicode', [125, 126], '5.3'],
            ['mbstring.script_encoding', '5.4', 'zend.script_encoding', [128, 129], '5.3'],
        ];
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
        return [
            ['crack.default_dictionary', '5.0', [256, 257], '4.4'],

            ['dbx.colnames_case', '5.1', [259, 260], '5.0'],
            ['ingres.allow_persistent', '5.1', [298, 299], '5.0'],
            ['ingres.default_database', '5.1', [301, 302], '5.0'],
            ['ingres.default_password', '5.1', [304, 305], '5.0'],
            ['ingres.default_user', '5.1', [307, 308], '5.0'],
            ['ingres.max_links', '5.1', [310, 311], '5.0'],
            ['ingres.max_persistent', '5.1', [313, 314], '5.0'],
            ['pfpro.defaulthost', '5.1', [217, 218], '5.0'],
            ['pfpro.defaultport', '5.1', [220, 221], '5.0'],
            ['pfpro.defaulttimeout', '5.1', [223, 224], '5.0'],
            ['pfpro.proxyaddress', '5.1', [226, 227], '5.0'],
            ['pfpro.proxyport', '5.1', [229, 230], '5.0'],
            ['pfpro.proxylogon', '5.1', [232, 233], '5.0'],
            ['pfpro.proxypassword', '5.1', [235, 236], '5.0'],

            ['hwapi.allow_persistent', '5.2', [253, 254], '5.1'],

            ['ifx.allow_persistent', '5.2.1', [92, 93], '5.2', '5.3'],
            ['ifx.blobinfile', '5.2.1', [95, 96], '5.2', '5.3'],
            ['ifx.byteasvarchar', '5.2.1', [98, 99], '5.2', '5.3'],
            ['ifx.charasvarchar', '5.2.1', [101, 102], '5.2', '5.3'],
            ['ifx.default_host', '5.2.1', [104, 105], '5.2', '5.3'],
            ['ifx.default_password', '5.2.1', [107, 108], '5.2', '5.3'],
            ['ifx.default_user', '5.2.1', [110, 111], '5.2', '5.3'],
            ['ifx.max_links', '5.2.1', [113, 114], '5.2', '5.3'],
            ['ifx.max_persistent', '5.2.1', [116, 117], '5.2', '5.3'],
            ['ifx.nullformat', '5.2.1', [119, 120], '5.2', '5.3'],
            ['ifx.textasvarchar', '5.2.1', [122, 123], '5.2', '5.3'],

            ['mime_magic.debug', '5.3', [247, 248], '5.2'],
            ['mime_magic.magicfile', '5.3', [250, 251], '5.2'],
            ['zend.ze1_compatibility_mode', '5.3', [36, 37], '5.2'],
            ['msql.allow_persistent', '5.3', [316, 317], '5.2'],
            ['msql.max_persistent', '5.3', [319, 320], '5.2'],
            ['msql.max_links', '5.3', [322, 323], '5.2'],

            ['fbsql.allow_persistent', '5.3', [262, 263], '5.2'],
            ['fbsql.generate_warnings', '5.3', [265, 266], '5.2'],
            ['fbsql.autocommit', '5.3', [268, 269], '5.2'],
            ['fbsql.max_persistent', '5.3', [271, 272], '5.2'],
            ['fbsql.max_links', '5.3', [274, 275], '5.2'],
            ['fbsql.max_connections', '5.3', [277, 278], '5.2'],
            ['fbsql.max_results', '5.3', [280, 281], '5.2'],
            ['fbsql.default_host', '5.3', [283, 284], '5.2'],
            ['fbsql.default_user', '5.3', [286, 287], '5.2'],
            ['fbsql.default_password', '5.3', [289, 290], '5.2'],
            ['fbsql.default_database', '5.3', [292, 293], '5.2'],
            ['fbsql.default_database_password', '5.3', [295, 296], '5.2'],

            ['phar.extract_list', '5.4', [244, 245], '5.3'],
            ['sqlite.assoc_case', '5.4', [370, 371], '5.3'],

            ['asp_tags', '7.0', [83, 84], '5.6'],
            ['xsl.security_prefs', '7.0', [86, 87], '5.6'],
            ['opcache.load_comments', '7.0', [241, 242], '5.6'],
            ['mssql.allow_persistent', '7.0', [325, 326], '5.6'],
            ['mssql.max_persistent', '7.0', [328, 329], '5.6'],
            ['mssql.max_links', '7.0', [331, 332], '5.6'],
            ['mssql.min_error_severity', '7.0', [334, 335], '5.6'],
            ['mssql.min_message_severity', '7.0', [337, 338], '5.6'],
            ['mssql.compatibility_mode', '7.0', [340, 341], '5.6'],
            ['mssql.connect_timeout', '7.0', [343, 344], '5.6'],
            ['mssql.timeout', '7.0', [346, 347], '5.6'],
            ['mssql.textsize', '7.0', [349, 350], '5.6'],
            ['mssql.textlimit', '7.0', [352, 353], '5.6'],
            ['mssql.batchsize', '7.0', [355, 356], '5.6'],
            ['mssql.datetimeconvert', '7.0', [358, 359], '5.6'],
            ['mssql.secure_connection', '7.0', [361, 362], '5.6'],
            ['mssql.max_procs', '7.0', [364, 365], '5.6'],
            ['mssql.charset', '7.0', [367, 368], '5.6'],
            ['mysql.allow_local_infile', '7.0', [373, 374], '5.6'],
            ['mysql.allow_persistent', '7.0', [376, 377], '5.6'],
            ['mysql.max_persistent', '7.0', [379, 380], '5.6'],
            ['mysql.max_links', '7.0', [382, 383], '5.6'],
            ['mysql.trace_mode', '7.0', [385, 386], '5.6'],
            ['mysql.default_port', '7.0', [388, 389], '5.6'],
            ['mysql.default_socket', '7.0', [391, 392], '5.6'],
            ['mysql.default_host', '7.0', [394, 395], '5.6'],
            ['mysql.default_user', '7.0', [397, 398], '5.6'],
            ['mysql.default_password', '7.0', [400, 401], '5.6'],
            ['mysql.connect_timeout', '7.0', [403, 404], '5.6'],
            ['sybase.allow_persistent', '7.0', [406, 407], '5.6'],
            ['sybase.max_persistent', '7.0', [409, 410], '5.6'],
            ['sybase.max_links', '7.0', [412, 413], '5.6'],
            ['sybase.interface_file', '7.0', [415, 416], '5.6'],
            ['sybase.min_error_severity', '7.0', [418, 419], '5.6'],
            ['sybase.min_message_severity', '7.0', [421, 422], '5.6'],
            ['sybase.compatability_mode', '7.0', [424, 425], '5.6'],

            ['session.entropy_file', '7.1', [141, 142], '7.0'],
            ['session.entropy_length', '7.1', [144, 145], '7.0'],
            ['session.hash_function', '7.1', [147, 148], '7.0'],
            ['session.hash_bits_per_character', '7.1', [150, 151], '7.0'],

            ['sql.safe_mode', '7.2', [169, 170], '7.1'],
            ['opcache.fast_shutdown', '7.2', [175, 176], '7.1'],

            ['birdstep.max_links', '7.3', [178, 179], '7.2'],

            ['ibase.allow_persistent', '7.4', [187, 188], '7.3'],
            ['ibase.max_persistent', '7.4', [190, 191], '7.3'],
            ['ibase.max_links', '7.4', [193, 194], '7.3'],
            ['ibase.default_db', '7.4', [196, 197], '7.3'],
            ['ibase.default_user', '7.4', [199, 200], '7.3'],
            ['ibase.default_password', '7.4', [202, 203], '7.3'],
            ['ibase.default_charset', '7.4', [205, 206], '7.3'],
            ['ibase.timestampformat', '7.4', [208, 209], '7.3'],
            ['ibase.dateformat', '7.4', [211, 212], '7.3'],
            ['ibase.timeformat', '7.4', [214, 215], '7.3'],

            ['assert.quiet_eval', '8.0', [427, 428], '7.4'],
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
            [57],
            [58],
            [133],
            [155],
            [156],
            [159],
            [160],
            [163],
            [164],
        ];
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
