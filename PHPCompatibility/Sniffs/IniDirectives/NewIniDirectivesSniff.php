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

use PHPCompatibility\AbstractNewFeatureSniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\TextStrings;

/**
 * Detect the use of new INI directives through `ini_set()` or `ini_get()`.
 *
 * PHP version All
 *
 * @link https://www.php.net/manual/en/ini.list.php
 * @link https://www.php.net/manual/en/ini.core.php
 *
 * @since 5.5
 * @since 7.0.7 When a new directive is used with `ini_set()`, the sniff will now throw an error
 *              instead of a warning.
 * @since 7.1.0 Now extends the `AbstractNewFeatureSniff` instead of the base `Sniff` class..
 */
class NewIniDirectivesSniff extends AbstractNewFeatureSniff
{

    /**
     * List of functions which take an ini directive as parameter (always the first parameter).
     *
     * Key is the function name, value is the 1-based parameter position in the function call.
     *
     * @since 7.1.0
     * @since 10.0.0 Moved from the base `Sniff` class to this sniff.
     *
     * @var array
     */
    protected $iniFunctions = [
        'ini_get' => 1,
        'ini_set' => 1,
    ];

    /**
     * A list of new INI directives
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the ini directive appears.
     *
     * @since 5.5
     * @since 7.0.3 Support for 'alternative' has been added.
     *
     * @var array(string)
     */
    protected $newIniDirectives = [
        'auto_globals_jit' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'com.code_page' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'date.default_latitude' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'date.default_longitude' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'date.sunrise_zenith' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'date.sunset_zenith' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'ibase.default_charset' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'ibase.default_db' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'mail.force_extra_parameters' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'mime_magic.debug' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'mysqli.max_links' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli.default_port' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli.default_socket' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli.default_host' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli.default_user' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli.default_pw' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli.reconnect' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'report_zend_debug' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'session.hash_bits_per_character' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'session.hash_function' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'soap.wsdl_cache_dir' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'soap.wsdl_cache_enabled' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'soap.wsdl_cache_ttl' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'sqlite.assoc_case' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'tidy.clean_output' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'tidy.default_config' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'zend.ze1_compatibility_mode' => [
            '4.4' => false,
            '5.0' => true,
        ],

        'date.timezone' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'detect_unicode' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'fbsql.batchsize' => [
            '5.0'         => false,
            '5.1'         => true,
            'alternative' => 'fbsql.batchSize',
        ],
        'realpath_cache_size' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'realpath_cache_ttl' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'pdo_odbc.connection_pooling' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pdo',
        ],

        'pdo_odbc.db2_instance_name' => [
            '5.1.0'     => false,
            '5.1.1'     => true,
            'extension' => 'pdo',
        ],

        'mbstring.strict_detection' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'mssql.charset' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'oci8.default_prefetch' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'oci8.max_persistent' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'oci8.old_oci_close_semantics' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'oci8.persistent_timeout' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'oci8.ping_interval' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'oci8.privileged_connect' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'oci8.statement_cache_size' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],

        'gd.jpeg_ignore_warning' => [
            '5.1.2' => false,
            '5.1.3' => true,
        ],

        'fbsql.show_timestamp_decimals' => [
            '5.1.4' => false,
            '5.1.5' => true,
        ],
        'soap.wsdl_cache' => [
            '5.1.4'     => false,
            '5.1.5'     => true,
            'extension' => 'soap',
        ],
        'soap.wsdl_cache_limit' => [
            '5.1.4'     => false,
            '5.1.5'     => true,
            'extension' => 'soap',
        ],

        'allow_url_include' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'filter.default' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'filter.default_flags' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'pcre.backtrack_limit' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'pcre.recursion_limit' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'session.cookie_httponly' => [
            '5.1' => false,
            '5.2' => true,
        ],

        'cgi.check_shebang_line' => [
            '5.2.0' => false,
            '5.2.1' => true,
        ],

        'max_input_nesting_level' => [
            '5.2.2' => false,
            '5.2.3' => true,
        ],

        'mysqli.allow_local_infile' => [
            '5.2.3'     => false,
            '5.2.4'     => true,
            'extension' => 'mysqli',
        ],

        'max_file_uploads' => [
            '5.2.11' => false,
            '5.2.12' => true,
        ],

        'cgi.discard_path' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'exit_on_timeout' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'intl.default_locale' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'intl.error_level' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'mail.add_x_header' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'mail.log' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'mbstring.http_output_conv_mimetype' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'mysqli.allow_persistent' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli.max_persistent' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'mysqlnd.collect_memory_statistics' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqlnd',
        ],
        'mysqlnd.collect_statistics' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqlnd',
        ],
        'mysqlnd.debug' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqlnd',
        ],
        'mysqlnd.log_mask' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqlnd',
        ],
        'mysqlnd.net_read_timeout' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqlnd',
        ],
        'mysqlnd.net_cmd_buffer_size' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqlnd',
        ],
        'mysqlnd.net_read_buffer_size' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqlnd',
        ],
        'odbc.default_cursortype' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'phar.readonly' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'phar',
        ],
        'phar.require_hash' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'phar',
        ],
        'phar.extract_list' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'phar',
        ],
        'request_order' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'user_ini.cache_ttl' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'user_ini.filename' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'zend.enable_gc' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'oci8.connection_class' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'oci8.events' => [
            '5.2' => false,
            '5.3' => true,
        ],

        'mysqlnd.mempool_default_size' => [
            '5.3.2'     => false,
            '5.3.3'     => true,
            'extension' => 'mysqlnd',
        ],

        'curl.cainfo' => [
            '5.3.6' => false,
            '5.3.7' => true,
        ],

        'max_input_vars' => [
            '5.3.8' => false,
            '5.3.9' => true,
        ],

        'sqlite3.extension_dir' => [
            '5.3.10'    => false,
            '5.3.11'    => true,
            'extension' => 'sqlite3',
        ],

        'cli.pager' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'cli.prompt' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'cli_server.color' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'enable_post_data_reading' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'phar.cache_list' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'phar',
        ],
        'session.upload_progress.enabled' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'session.upload_progress.cleanup' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'session.upload_progress.name' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'session.upload_progress.freq' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'session.upload_progress.min_freq' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'session.upload_progress.prefix' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'windows_show_crt_warning' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'zend.detect_unicode' => [
            '5.3'         => false,
            '5.4'         => true,
            'alternative' => 'detect_unicode',
        ],
        'zend.multibyte' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'zend.script_encoding' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'zend.signal_check' => [
            '5.3' => false,
            '5.4' => true,
        ],

        'intl.use_exceptions' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'mysqlnd.sha256_server_public_key' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'mysqlnd',
        ],
        'mysqlnd.trace_alloc' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'mysqlnd',
        ],
        'sys_temp_dir' => [
            '5.4' => false,
            '5.5' => true,
        ],
        'xsl.security_prefs' => [
            '5.4' => false,
            '5.5' => true,
        ],
        'opcache.enable' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.enable_cli' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.memory_consumption' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.interned_strings_buffer' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.max_accelerated_files' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.max_wasted_percentage' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.use_cwd' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.validate_timestamps' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.revalidate_freq' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.revalidate_path' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.save_comments' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.load_comments' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.fast_shutdown' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.enable_file_override' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.optimization_level' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.inherited_hack' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.dups_fix' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.blacklist_filename' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.max_file_size' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.consistency_checks' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.force_restart_timeout' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.error_log' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.log_verbosity_level' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.preferred_memory_model' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.protect_memory' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.mmap_base' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.restrict_api' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.file_update_protection' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.huge_code_pages' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],
        'opcache.lockfile_path' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'opcache',
        ],

        'session.use_strict_mode' => [
            '5.5.1' => false,
            '5.5.2' => true,
        ],

        'phpdbg.path' => [
            '5.6.2'     => false,
            '5.6.3'     => true,
            'extension' => 'phpdbg',
        ],

        'mysqli.rollback_on_cached_plink' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'mysqli',
        ],
        'openssl.cafile' => [
            '5.5' => false,
            '5.6' => true,
        ],
        'openssl.capath' => [
            '5.5' => false,
            '5.6' => true,
        ],
        'mysqlnd.fetch_data_copy' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'mysqlnd',
        ],

        'assert.exception' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'pcre.jit' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'phpdbg.eol' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'phpdbg',
        ],
        'session.lazy_write' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'zend.assertions' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'opcache.file_cache' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'opcache',
        ],
        'opcache.file_cache_only' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'opcache',
        ],
        'opcache.file_cache_consistency_checks' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'opcache',
        ],
        'opcache.file_cache_fallback' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'opcache',
        ], // Windows only.

        'opcache.validate_permission' => [
            '7.0.13'    => false,
            '7.0.14'    => true,
            'extension' => 'opcache',
        ],
        'opcache.validate_root' => [
            '7.0.13'    => false,
            '7.0.14'    => true,
            'extension' => 'opcache',
        ],

        'hard_timeout' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'opcache.opt_debug_level' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'opcache',
        ],
        'session.sid_length' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'session.sid_bits_per_character' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'session.trans_sid_hosts' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'session.trans_sid_tags' => [
            '7.0' => false,
            '7.1' => true,
        ],
        'url_rewriter.hosts' => [
            '7.0' => false,
            '7.1' => true,
        ],

        // Introduced in PHP 7.1.25, 7.2.13, 7.3.0.
        'imap.enable_insecure_rsh' => [
            '7.1.24' => false,
            '7.1.25' => true,
        ],

        // Introduced in PHP 7.2.17, 7.3.4.
        'sqlite3.defensive' => [
            '7.2.16'    => false,
            '7.2.17'    => true,
            'extension' => 'sqlite3',
        ],

        'syslog.facility' => [
            '7.2' => false,
            '7.3' => true,
        ],
        'syslog.filter' => [
            '7.2' => false,
            '7.3' => true,
        ],
        'syslog.ident' => [
            '7.2' => false,
            '7.3' => true,
        ],
        'session.cookie_samesite' => [
            '7.2' => false,
            '7.3' => true,
        ],

        'ffi.enable' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'ffi',
        ],
        'ffi.preload' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'ffi',
        ],
        'opcache.cache_id' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'opcache',
        ],
        'opcache.preload' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'opcache',
        ],
        'opcache.preload_user' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'opcache',
        ],
        'zend.exception_ignore_args' => [
            '7.3' => false,
            '7.4' => true,
        ],

        'com.dotnet_version' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'pm.status_listen' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'zend.exception_string_param_max_len' => [
            '7.4' => false,
            '8.0' => true,
        ],

        'fiber.stack_size' => [
            '8.0' => false,
            '8.1' => true,
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

        $iniToken = PassedParameters::getParameter($phpcsFile, $stackPtr, $this->iniFunctions[$functionLc]);
        if ($iniToken === false) {
            return;
        }

        $filteredToken = TextStrings::stripQuotes($iniToken['raw']);
        if (isset($this->newIniDirectives[$filteredToken]) === false) {
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
        return $this->newIniDirectives[$itemInfo['name']];
    }


    /**
     * Get an array of the non-PHP-version array keys used in a sub-array.
     *
     * @since 7.1.0
     *
     * @return array
     */
    protected function getNonVersionArrayKeys()
    {
        return ['alternative'];
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
        $errorInfo                = parent::getErrorInfo($itemArray, $itemInfo);
        $errorInfo['alternative'] = '';

        if (isset($itemArray['alternative']) === true) {
            $errorInfo['alternative'] = $itemArray['alternative'];
        }

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
        return "INI directive '%s' is not present in PHP version %s or earlier";
    }


    /**
     * Allow for concrete child classes to filter the error message before it's passed to PHPCS.
     *
     * @since 7.1.0
     *
     * @param string $error     The error message which was created.
     * @param array  $itemInfo  Base information about the item this error message applies to.
     * @param array  $errorInfo Detail information about an item this error message applies to.
     *
     * @return string
     */
    protected function filterErrorMsg($error, array $itemInfo, array $errorInfo)
    {
        if ($errorInfo['alternative'] !== '') {
            $error .= ". This directive was previously called '%s'.";
        }

        return $error;
    }


    /**
     * Allow for concrete child classes to filter the error data before it's passed to PHPCS.
     *
     * @since 7.1.0
     *
     * @param array $data      The error data array which was created.
     * @param array $itemInfo  Base information about the item this error message applies to.
     * @param array $errorInfo Detail information about an item this error message applies to.
     *
     * @return array
     */
    protected function filterErrorData(array $data, array $itemInfo, array $errorInfo)
    {
        if ($errorInfo['alternative'] !== '') {
            $data[] = $errorInfo['alternative'];
        }

        return $data;
    }
}
