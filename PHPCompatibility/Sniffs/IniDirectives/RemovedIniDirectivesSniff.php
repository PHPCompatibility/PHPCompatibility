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
use PHP_CodeSniffer_File as File;
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
    protected $deprecatedIniDirectives = array(
        'crack.default_dictionary' => array(
            '5.0'       => true,
            'extension' => 'crack',
        ),
        'dbx.colnames_case' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'fbsql.batchSize' => array(
            '5.1'         => true,
            'alternative' => 'fbsql.batchsize',
            'extension'   => 'fbsql',
        ),
        'pfpro.defaulthost' => array(
            '5.1'       => true,
            'extension' => 'pfpro',
        ),
        'pfpro.defaultport' => array(
            '5.1'       => true,
            'extension' => 'pfpro',
        ),
        'pfpro.defaulttimeout' => array(
            '5.1'       => true,
            'extension' => 'pfpro',
        ),
        'pfpro.proxyaddress' => array(
            '5.1'       => true,
            'extension' => 'pfpro',
        ),
        'pfpro.proxyport' => array(
            '5.1'       => true,
            'extension' => 'pfpro',
        ),
        'pfpro.proxylogon' => array(
            '5.1'       => true,
            'extension' => 'pfpro',
        ),
        'pfpro.proxypassword' => array(
            '5.1'       => true,
            'extension' => 'pfpro',
        ),
        'ingres.allow_persistent' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres.default_database' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres.default_password' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres.default_user' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres.max_links' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ingres.max_persistent' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),

        'hwapi.allow_persistent' => array(
            '5.2'       => true,
            'extension' => 'hwapi',
        ),

        'ifx.allow_persistent' => array(
            '5.2.1' => true,
        ),
        'ifx.blobinfile' => array(
            '5.2.1' => true,
        ),
        'ifx.byteasvarchar' => array(
            '5.2.1' => true,
        ),
        'ifx.charasvarchar' => array(
            '5.2.1' => true,
        ),
        'ifx.default_host' => array(
            '5.2.1' => true,
        ),
        'ifx.default_password' => array(
            '5.2.1' => true,
        ),
        'ifx.default_user' => array(
            '5.2.1' => true,
        ),
        'ifx.max_links' => array(
            '5.2.1' => true,
        ),
        'ifx.max_persistent' => array(
            '5.2.1' => true,
        ),
        'ifx.nullformat' => array(
            '5.2.1' => true,
        ),
        'ifx.textasvarchar' => array(
            '5.2.1' => true,
        ),

        'mime_magic.debug' => array(
            '5.3'       => true,
            'extension' => 'mimetype',
        ),
        'mime_magic.magicfile' => array(
            '5.3'       => true,
            'extension' => 'mimetype',
        ),
        'zend.ze1_compatibility_mode' => array(
            '5.3' => true,
        ),
        'fbsql.allow_persistent' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql.generate_warnings' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql.autocommit' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql.max_persistent' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql.max_links' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql.max_connections' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql.max_results' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql.default_host' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql.default_user' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql.default_password' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql.default_database' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'fbsql.default_database_password' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'msql.allow_persistent' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql.max_persistent' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'msql.max_links' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),

        'allow_call_time_pass_reference' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'define_syslog_variables' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'detect_unicode' => array(
            '5.4'         => true,
            'alternative' => 'zend.detect_unicode',
        ),
        'highlight.bg' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'magic_quotes_gpc' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'magic_quotes_runtime' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'magic_quotes_sybase' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'sybase',
        ),
        'mbstring.script_encoding' => array(
            '5.4'         => true,
            'alternative' => 'zend.script_encoding',
        ),
        'phar.extract_list' => array(
            '5.4' => true,
        ),
        'register_globals' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'register_long_arrays' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'safe_mode' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'safe_mode_allowed_env_vars' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'safe_mode_exec_dir' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'safe_mode_gid' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'safe_mode_include_dir' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'safe_mode_protected_env_vars' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'session.bug_compat_42' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'session.bug_compat_warn' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'y2k_compliance' => array(
            '5.3' => false,
            '5.4' => true,
        ),

        'sqlite.assoc_case' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),

        'always_populate_raw_post_data' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'iconv.input_encoding' => array(
            '5.6' => false,
        ),
        'iconv.output_encoding' => array(
            '5.6' => false,
        ),
        'iconv.internal_encoding' => array(
            '5.6' => false,
        ),
        'mbstring.http_input' => array(
            '5.6' => false,
        ),
        'mbstring.http_output' => array(
            '5.6' => false,
        ),
        'mbstring.internal_encoding' => array(
            '5.6' => false,
        ),

        'asp_tags' => array(
            '7.0' => true,
        ),
        'xsl.security_prefs' => array(
            '7.0' => true,
        ),
        'opcache.load_comments' => array(
            '7.0' => true,
        ),
        'mssql.allow_persistent' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql.max_persistent' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql.max_links' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql.min_error_severity' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql.min_message_severity' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql.compatibility_mode' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql.connect_timeout' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql.timeout' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql.textsize' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql.textlimit' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql.batchsize' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql.datetimeconvert' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql.secure_connection' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql.max_procs' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mssql.charset' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'mysql.allow_local_infile' => array(
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql.allow_persistent' => array(
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql.max_persistent' => array(
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql.max_links' => array(
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql.trace_mode' => array(
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql.default_port' => array(
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql.default_socket' => array(
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql.default_host' => array(
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql.default_user' => array(
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql.default_password' => array(
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'mysql.connect_timeout' => array(
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'sybase.allow_persistent' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase.max_persistent' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase.max_links' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase.interface_file' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase.min_error_severity' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase.min_message_severity' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),
        'sybase.compatability_mode' => array(
            '7.0'       => true,
            'extension' => 'sybase',
        ),

        'mcrypt.algorithms_dir' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'mcrypt.modes_dir' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'session.entropy_file' => array(
            '7.1' => true,
        ),
        'session.entropy_length' => array(
            '7.1' => true,
        ),
        'session.hash_function' => array(
            '7.1' => true,
        ),
        'session.hash_bits_per_character' => array(
            '7.1' => true,
        ),

        'mbstring.func_overload' => array(
            '7.2' => false,
        ),
        'sql.safe_mode' => array(
            '7.2' => true,
        ),
        'track_errors' => array(
            '7.2' => false,
        ),
        'opcache.fast_shutdown' => array(
            '7.2' => true,
        ),

        'birdstep.max_links' => array(
            '7.3' => true,
        ),
        'opcache.inherited_hack' => array(
            '5.3' => false, // Soft deprecated, i.e. ignored.
            '7.3' => true,
        ),
        'pdo_odbc.db2_instance_name' => array(
            '7.3' => false, // Has been marked as deprecated in the manual from before this time. Now hard-deprecated.
        ),

        'allow_url_include' => array(
            '7.4' => false,
        ),
        'ibase.allow_persistent' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase.max_persistent' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase.max_links' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase.default_db' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase.default_user' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase.default_password' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase.default_charset' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase.timestampformat' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase.dateformat' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'ibase.timeformat' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 5.5
     *
     * @return array
     */
    public function register()
    {
        return array(\T_STRING);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $ignore = array(
            \T_DOUBLE_COLON    => true,
            \T_OBJECT_OPERATOR => true,
            \T_FUNCTION        => true,
            \T_CONST           => true,
        );

        $prevToken = $phpcsFile->findPrevious(\T_WHITESPACE, ($stackPtr - 1), null, true);
        if (isset($ignore[$tokens[$prevToken]['code']]) === true) {
            // Not a call to a PHP function.
            return;
        }

        $functionLc = strtolower($tokens[$stackPtr]['content']);
        if (isset($this->iniFunctions[$functionLc]) === false) {
            return;
        }

        $iniToken = PassedParameters::getParameter($phpcsFile, $stackPtr, $this->iniFunctions[$functionLc]);
        if ($iniToken === false) {
            return;
        }

        $filteredToken = TextStrings::stripQuotes($iniToken['raw']);
        if (isset($this->deprecatedIniDirectives[$filteredToken]) === false) {
            return;
        }

        $itemInfo = array(
            'name'       => $filteredToken,
            'functionLc' => $functionLc,
        );
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
        return str_replace('%s', "'%s'", parent::getAlternativeOptionTemplate());
    }
}
