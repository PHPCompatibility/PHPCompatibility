<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\IniDirectives;

use PHPCompatibility\AbstractRemovedFeatureSniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\TextStrings;

/**
 * Detect the use of deprecated and removed INI directives through `ini_set()` or `ini_get()`.
 *
 * PHP version All
 *
 * @link https://www.php.net/manual/en/ini.list.php
 * @link https://www.php.net/manual/en/ini.core.php
 *
 * @since 5.5
 * @since 7.0.0 This sniff now throws a warning (deprecated) or an error (removed) depending
 *              on the `testVersion` set. Previously it would always throw a warning.
 * @since 7.0.1 The sniff will now only throw warnings for `ini_get()`.
 * @since 7.1.0 Now extends the `AbstractRemovedFeatureSniff` instead of the base `Sniff` class.
 * @since 9.0.0 Renamed from `DeprecatedIniDirectivesSniff` to `RemovedIniDirectivesSniff`.
 */
class RemovedIniDirectivesSniff extends AbstractRemovedFeatureSniff
{

    /**
     * List of functions which take an ini directive as parameter (always the first parameter).
     *
     * Key is the function name, value an array containing the 1-based parameter position
     * and the official name of the parameter.
     *
     * @since 7.1.0
     * @since 10.0.0 Moved from the base `Sniff` class to this sniff.
     *
     * @var array
     */
    protected $iniFunctions = [
        'ini_get' => [
            'position' => 1,
            'name'     => 'option',
        ],
        'ini_set' => [
            'position' => 1,
            'name'     => 'option',
        ],
    ];

    /**
     * A list of deprecated/removed INI directives.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * If's sufficient to list the first version where the ini directive was deprecated/removed.
     *
     * @since 5.5
     * @since 7.0.3 Support for 'alternative' has been added.
     *
     * @var array(string)
     */
    protected $deprecatedIniDirectives = [
        'crack.default_dictionary' => [
            '5.0'       => true,
            'extension' => 'crack',
        ],
        'dbx.colnames_case' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'fbsql.batchSize' => [
            '5.1'         => true,
            'alternative' => 'fbsql.batchsize',
            'extension'   => 'fbsql',
        ],
        'pfpro.defaulthost' => [
            '5.1'       => true,
            'extension' => 'pfpro',
        ],
        'pfpro.defaultport' => [
            '5.1'       => true,
            'extension' => 'pfpro',
        ],
        'pfpro.defaulttimeout' => [
            '5.1'       => true,
            'extension' => 'pfpro',
        ],
        'pfpro.proxyaddress' => [
            '5.1'       => true,
            'extension' => 'pfpro',
        ],
        'pfpro.proxyport' => [
            '5.1'       => true,
            'extension' => 'pfpro',
        ],
        'pfpro.proxylogon' => [
            '5.1'       => true,
            'extension' => 'pfpro',
        ],
        'pfpro.proxypassword' => [
            '5.1'       => true,
            'extension' => 'pfpro',
        ],
        'ingres.allow_persistent' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres.default_database' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres.default_password' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres.default_user' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres.max_links' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ingres.max_persistent' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],

        'hwapi.allow_persistent' => [
            '5.2'       => true,
            'extension' => 'hwapi',
        ],

        'ifx.allow_persistent' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx.blobinfile' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx.byteasvarchar' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx.charasvarchar' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx.default_host' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx.default_password' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx.default_user' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx.max_links' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx.max_persistent' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx.nullformat' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'ifx.textasvarchar' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],

        'mime_magic.debug' => [
            '5.3'       => true,
            'extension' => 'mimetype',
        ],
        'mime_magic.magicfile' => [
            '5.3'       => true,
            'extension' => 'mimetype',
        ],
        'zend.ze1_compatibility_mode' => [
            '5.3' => true,
        ],
        'fbsql.allow_persistent' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql.generate_warnings' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql.autocommit' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql.max_persistent' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql.max_links' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql.max_connections' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql.max_results' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql.default_host' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql.default_user' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql.default_password' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql.default_database' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'fbsql.default_database_password' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'msql.allow_persistent' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql.max_persistent' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'msql.max_links' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],

        'allow_call_time_pass_reference' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'define_syslog_variables' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'detect_unicode' => [
            '5.4'         => true,
            'alternative' => 'zend.detect_unicode',
        ],
        'highlight.bg' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'magic_quotes_gpc' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'magic_quotes_runtime' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'magic_quotes_sybase' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'sybase',
        ],
        'mbstring.script_encoding' => [
            '5.4'         => true,
            'alternative' => 'zend.script_encoding',
        ],
        'phar.extract_list' => [
            '5.4' => true,
        ],
        'register_globals' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'register_long_arrays' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'safe_mode' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'safe_mode_allowed_env_vars' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'safe_mode_exec_dir' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'safe_mode_gid' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'safe_mode_include_dir' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'safe_mode_protected_env_vars' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'session.bug_compat_42' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'session.bug_compat_warn' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'y2k_compliance' => [
            '5.3' => false,
            '5.4' => true,
        ],

        'sqlite.assoc_case' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],

        'always_populate_raw_post_data' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'iconv.input_encoding' => [
            '5.6' => false,
        ],
        'iconv.output_encoding' => [
            '5.6' => false,
        ],
        'iconv.internal_encoding' => [
            '5.6' => false,
        ],
        'mbstring.http_input' => [
            '5.6' => false,
        ],
        'mbstring.http_output' => [
            '5.6' => false,
        ],
        'mbstring.internal_encoding' => [
            '5.6' => false,
        ],

        'asp_tags' => [
            '7.0' => true,
        ],
        'xsl.security_prefs' => [
            '7.0' => true,
        ],
        'opcache.load_comments' => [
            '7.0' => true,
        ],
        'mssql.allow_persistent' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql.max_persistent' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql.max_links' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql.min_error_severity' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql.min_message_severity' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql.compatibility_mode' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql.connect_timeout' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql.timeout' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql.textsize' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql.textlimit' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql.batchsize' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql.datetimeconvert' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql.secure_connection' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql.max_procs' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mssql.charset' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'mysql.allow_local_infile' => [
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql.allow_persistent' => [
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql.max_persistent' => [
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql.max_links' => [
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql.trace_mode' => [
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql.default_port' => [
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql.default_socket' => [
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql.default_host' => [
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql.default_user' => [
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql.default_password' => [
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'mysql.connect_timeout' => [
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'sybase.allow_persistent' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase.max_persistent' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase.max_links' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase.interface_file' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase.min_error_severity' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase.min_message_severity' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],
        'sybase.compatability_mode' => [
            '7.0'       => true,
            'extension' => 'sybase',
        ],

        'mcrypt.algorithms_dir' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'mcrypt.modes_dir' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'session.entropy_file' => [
            '7.1' => true,
        ],
        'session.entropy_length' => [
            '7.1' => true,
        ],
        'session.hash_function' => [
            '7.1' => true,
        ],
        'session.hash_bits_per_character' => [
            '7.1' => true,
        ],

        'mbstring.func_overload' => [
            '7.2' => false,
            '8.0' => true,
        ],
        'sql.safe_mode' => [
            '7.2' => true,
        ],
        'track_errors' => [
            '7.2' => false,
            '8.0' => true,
        ],
        'opcache.fast_shutdown' => [
            '7.2' => true,
        ],

        'birdstep.max_links' => [
            '7.3' => true,
        ],
        'opcache.inherited_hack' => [
            '5.3' => false, // Soft deprecated, i.e. ignored.
            '7.3' => true,
        ],
        'pdo_odbc.db2_instance_name' => [
            '7.3' => false, // Has been marked as deprecated in the manual from before this time. Now hard-deprecated.
            '8.0' => true,
        ],

        'allow_url_include' => [
            '7.4' => false,
        ],
        'ibase.allow_persistent' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase.max_persistent' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase.max_links' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase.default_db' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase.default_user' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase.default_password' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase.default_charset' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase.timestampformat' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase.dateformat' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'ibase.timeformat' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],

        'assert.quiet_eval' => [
            '8.0' => true,
        ],

        'auto_detect_line_endings' => [
            '8.1' => false,
        ],
        'log_errors_max_len' => [
            '8.1' => true,
        ],
        'date.default_latitude' => [
            '8.1'       => false,
            'extension' => 'date',
        ],
        'date.default_longitude' => [
            '8.1'       => false,
            'extension' => 'date',
        ],
        'date.sunset_zenith' => [
            '8.1'       => false,
            'extension' => 'date',
        ],
        'filter.default' => [
            '8.1'       => false,
            'extension' => 'filter',
        ],
        'filter.default_options' => [
            '8.1'       => false,
            'extension' => 'filter',
        ],
        'oci8.old_oci_close_semantics' => [
            '8.1'       => false,
            'extension' => 'oci8',
        ],
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 5.5
     *
     * @return array
     */
    public function register()
    {
        return [\T_STRING];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $ignore = [
            \T_DOUBLE_COLON    => true,
            \T_OBJECT_OPERATOR => true,
            \T_FUNCTION        => true,
            \T_CONST           => true,
        ];

        $prevToken = $phpcsFile->findPrevious(\T_WHITESPACE, ($stackPtr - 1), null, true);
        if (isset($ignore[$tokens[$prevToken]['code']]) === true) {
            // Not a call to a PHP function.
            return;
        }

        $functionLc = \strtolower($tokens[$stackPtr]['content']);
        if (isset($this->iniFunctions[$functionLc]) === false) {
            return;
        }

        $paramInfo = $this->iniFunctions[$functionLc];
        $iniToken  = PassedParameters::getParameter($phpcsFile, $stackPtr, $paramInfo['position'], $paramInfo['name']);
        if ($iniToken === false) {
            return;
        }

        $filteredToken = TextStrings::stripQuotes($iniToken['raw']);
        if (isset($this->deprecatedIniDirectives[$filteredToken]) === false) {
            return;
        }

        $itemInfo = [
            'name'       => $filteredToken,
            'functionLc' => $functionLc,
        ];
        $this->handleFeature($phpcsFile, $iniToken['end'], $itemInfo);
    }


    /**
     * Get the relevant sub-array for a specific item from a multi-dimensional array.
     *
     * @since 7.1.0
     *
     * @param array $itemInfo Base information about the item.
     *
     * @return array Version and other information about the item.
     */
    public function getItemArray(array $itemInfo)
    {
        return $this->deprecatedIniDirectives[$itemInfo['name']];
    }


    /**
     * Retrieve the relevant detail (version) information for use in an error message.
     *
     * @since 7.1.0
     *
     * @param array $itemArray Version and other information about the item.
     * @param array $itemInfo  Base information about the item.
     *
     * @return array
     */
    public function getErrorInfo(array $itemArray, array $itemInfo)
    {
        $errorInfo = parent::getErrorInfo($itemArray, $itemInfo);

        // Lower error level to warning if the function used was ini_get.
        if ($errorInfo['error'] === true && $itemInfo['functionLc'] === 'ini_get') {
            $errorInfo['error'] = false;
        }

        return $errorInfo;
    }


    /**
     * Get the error message template for this sniff.
     *
     * @since 7.1.0
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return "INI directive '%s' is ";
    }


    /**
     * Get the error message template for suggesting an alternative for a specific sniff.
     *
     * @since 7.1.0
     *
     * @return string
     */
    protected function getAlternativeOptionTemplate()
    {
        return \str_replace('%s', "'%s'", parent::getAlternativeOptionTemplate());
    }
}
