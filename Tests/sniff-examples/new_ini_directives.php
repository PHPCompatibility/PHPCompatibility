<?php
function some_random_function() {} // Verify sniff doesn't flag this line.
some_random_function(); // Verify sniff doesn't flag this line.
ini_set('display_errors', 1); // Verify sniff doesn't flag this ini directive.
ini_set($iniName, 'filter.default'); // Verify sniff doesn't flag on second parameter.

ini_set('allow_url_include', 1);
$test = ini_get('allow_url_include');
ini_set("allow_url_include", 1); // Verify sniff works when using double quotes.

ini_set('pcre.backtrack_limit', 1);
$test = ini_get('pcre.backtrack_limit');

ini_set('pcre.recursion_limit', 1);
$test = ini_get('pcre.recursion_limit');

ini_set('session.cookie_httponly', 1);
$test = ini_get('session.cookie_httponly');

ini_set('max_input_nesting_level', 1);
$test = ini_get('max_input_nesting_level');

ini_set('user_ini.filename', 1);
$test = ini_get('user_ini.filename');

ini_set('user_ini.cache_ttl', 1);
$test = ini_get('user_ini.cache_ttl');

ini_set('exit_on_timeout', 1);
$test = ini_get('exit_on_timeout');

ini_set('mbstring.http_output_conv_mimetype', 1);
$test = ini_get('mbstring.http_output_conv_mimetype');

ini_set('request_order', 1);
$test = ini_get('request_order');

ini_set('cli.pager', 1);
$test = ini_get('cli.pager');

ini_set('cli.prompt', 1);
$test = ini_get('cli.prompt');

ini_set('cli_server.color', 1);
$test = ini_get('cli_server.color');

ini_set('max_input_vars', 1);
$test = ini_get('max_input_vars');

ini_set('zend.multibyte', 1);
$test = ini_get('zend.multibyte');

ini_set('zend.script_encoding', 1);
$test = ini_get('zend.script_encoding');

ini_set('zend.signal_check', 1);
$test = ini_get('zend.signal_check');

ini_set('session.upload_progress.enabled', 1);
$test = ini_get('session.upload_progress.enabled');

ini_set('session.upload_progress.cleanup', 1);
$test = ini_get('session.upload_progress.cleanup');

ini_set('session.upload_progress.name', 1);
$test = ini_get('session.upload_progress.name');

ini_set('session.upload_progress.freq', 1);
$test = ini_get('session.upload_progress.freq');

ini_set('enable_post_data_reading', 1);
$test = ini_get('enable_post_data_reading');

ini_set('windows_show_crt_warning', 1);
$test = ini_get('windows_show_crt_warning');

ini_set('intl.use_exceptions', 1);
$test = ini_get('intl.use_exceptions');

ini_set('mysqlnd.sha256_server_public_key', 1);
$test = ini_get('mysqlnd.sha256_server_public_key');

ini_set('auto_globals_jit', 1);
$test = ini_get('auto_globals_jit');

ini_set('com.code_page', 1);
$test = ini_get('com.code_page');

ini_set('date.default_latitude', 1);
$test = ini_get('date.default_latitude');

ini_set('date.default_longitude', 1);
$test = ini_get('date.default_longitude');

ini_set('date.sunrise_zenith', 1);
$test = ini_get('date.sunrise_zenith');

ini_set('date.sunset_zenith', 1);
$test = ini_get('date.sunset_zenith');

ini_set('ibase.default_charset', 1);
$test = ini_get('ibase.default_charset');

ini_set('ibase.default_db', 1);
$test = ini_get('ibase.default_db');

ini_set('mail.force_extra_parameters', 1);
$test = ini_get('mail.force_extra_parameters');

ini_set('mime_magic.debug', 1);
$test = ini_get('mime_magic.debug');

ini_set('mysqli.max_links', 1);
$test = ini_get('mysqli.max_links');

ini_set('mysqli.default_port', 1);
$test = ini_get('mysqli.default_port');

ini_set('mysqli.default_socket', 1);
$test = ini_get('mysqli.default_socket');

ini_set('mysqli.default_host', 1);
$test = ini_get('mysqli.default_host');

ini_set('mysqli.default_user', 1);
$test = ini_get('mysqli.default_user');

ini_set('mysqli.default_pw', 1);
$test = ini_get('mysqli.default_pw');

ini_set('report_zend_debug', 1);
$test = ini_get('report_zend_debug');

ini_set('session.hash_bits_per_character', 1);
$test = ini_get('session.hash_bits_per_character');

ini_set('session.hash_function', 1);
$test = ini_get('session.hash_function');

ini_set('soap.wsdl_cache_dir', 1);
$test = ini_get('soap.wsdl_cache_dir');

ini_set('soap.wsdl_cache_enabled', 1);
$test = ini_get('soap.wsdl_cache_enabled');

ini_set('soap.wsdl_cache_ttl', 1);
$test = ini_get('soap.wsdl_cache_ttl');

ini_set('sqlite.assoc_case', 1);
$test = ini_get('sqlite.assoc_case');

ini_set('tidy.clean_output', 1);
$test = ini_get('tidy.clean_output');

ini_set('tidy.default_config', 1);
$test = ini_get('tidy.default_config');

ini_set('zend.ze1_compatibility_mode', 1);
$test = ini_get('zend.ze1_compatibility_mode');

ini_set('date.timezone', 1);
$test = ini_get('date.timezone');

ini_set('detect_unicode', 1);
$test = ini_get('detect_unicode');

ini_set('fbsql.batchsize', 1);
$test = ini_get('fbsql.batchsize');

ini_set('realpath_cache_size', 1);
$test = ini_get('realpath_cache_size');

ini_set('realpath_cache_ttl', 1);
$test = ini_get('realpath_cache_ttl');

ini_set('mbstring.strict_detection', 1);
$test = ini_get('mbstring.strict_detection');

ini_set('mssql.charset', 1);
$test = ini_get('mssql.charset');

ini_set('gd.jpeg_ignore_warning', 1);
$test = ini_get('gd.jpeg_ignore_warning');

ini_set('fbsql.show_timestamp_decimals', 1);
$test = ini_get('fbsql.show_timestamp_decimals');

ini_set('soap.wsdl_cache', 1);
$test = ini_get('soap.wsdl_cache');

ini_set('soap.wsdl_cache_limit', 1);
$test = ini_get('soap.wsdl_cache_limit');

ini_set('filter.default', 1);
$test = ini_get('filter.default');

ini_set('filter.default_flags', 1);
$test = ini_get('filter.default_flags');

ini_set('cgi.check_shebang_line', 1);
$test = ini_get('cgi.check_shebang_line');

ini_set('mysqli.allow_local_infile', 1);
$test = ini_get('mysqli.allow_local_infile');

ini_set('max_file_uploads', 1);
$test = ini_get('max_file_uploads');

ini_set('cgi.discard_path', 1);
$test = ini_get('cgi.discard_path');

ini_set('intl.default_locale', 1);
$test = ini_get('intl.default_locale');

ini_set('intl.error_level', 1);
$test = ini_get('intl.error_level');

ini_set('mail.add_x_header', 1);
$test = ini_get('mail.add_x_header');

ini_set('mail.log', 1);
$test = ini_get('mail.log');

ini_set('mysqli.allow_persistent', 1);
$test = ini_get('mysqli.allow_persistent');

ini_set('mysqli.max_persistent', 1);
$test = ini_get('mysqli.max_persistent');

ini_set('mysqli.cache_size', 1);
$test = ini_get('mysqli.cache_size');

ini_set('mysqlnd.collect_memory_statistics', 1);
$test = ini_get('mysqlnd.collect_memory_statistics');

ini_set('mysqlnd.collect_statistics', 1);
$test = ini_get('mysqlnd.collect_statistics');

ini_set('mysqlnd.debug', 1);
$test = ini_get('mysqlnd.debug');

ini_set('mysqlnd.net_read_buffer_size', 1);
$test = ini_get('mysqlnd.net_read_buffer_size');

ini_set('odbc.default_cursortype', 1);
$test = ini_get('odbc.default_cursortype');

ini_set('zend.enable_gc', 1);
$test = ini_get('zend.enable_gc');

ini_set('curl.cainfo', 1);
$test = ini_get('curl.cainfo');

ini_set('sqlite3.extension_dir', 1);
$test = ini_get('sqlite3.extension_dir');

ini_set('session.upload_progress.prefix', 1);
$test = ini_get('session.upload_progress.prefix');

ini_set('zend.detect_unicode', 1);
$test = ini_get('zend.detect_unicode');

ini_set('mysqlnd.log_mask', 1);
$test = ini_get('mysqlnd.log_mask');

ini_set('mysqlnd.mempool_default_size', 1);
$test = ini_get('mysqlnd.mempool_default_size');

ini_set('mysqlnd.net_cmd_buffer_size', 1);
$test = ini_get('mysqlnd.net_cmd_buffer_size');

ini_set('mysqlnd.net_read_timeout', 1);
$test = ini_get('mysqlnd.net_read_timeout');

ini_set('phar.cache_list', 1);
$test = ini_get('phar.cache_list');

ini_set('mysqlnd.trace_alloc', 1);
$test = ini_get('mysqlnd.trace_alloc');

ini_set('sys_temp_dir', 1);
$test = ini_get('sys_temp_dir');

ini_set('xsl.security_prefs', 1);
$test = ini_get('xsl.security_prefs');

ini_set('session.use_strict_mode', 1);
$test = ini_get('session.use_strict_mode');

ini_set('mysqli.rollback_on_cached_plink', 1);
$test = ini_get('mysqli.rollback_on_cached_plink');

ini_set('assert.exception', 1);
$test = ini_get('assert.exception');

ini_set('pcre.jit', 1);
$test = ini_get('pcre.jit');

ini_set('session.lazy_write', 1);
$test = ini_get('session.lazy_write');

ini_set('zend.assertions', 1);
$test = ini_get('zend.assertions');
