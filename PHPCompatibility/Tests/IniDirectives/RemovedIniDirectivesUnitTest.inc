<?php

ini_set(option: 'define_syslog_variables', value: 'a');
$a = ini_get(option: 'define_syslog_variables');

ini_set('register_globals', 'a');
$a = ini_get('register_globals');

ini_set('register_long_arrays', 1);
$a = ini_get('register_long_arrays');

ini_set('magic_quotes_gpc', 1);
$a = ini_get('magic_quotes_gpc');

ini_set('magic_quotes_runtime', 1);
$a = ini_get('magic_quotes_runtime');

ini_set('magic_quotes_sybase', 1);
$a = ini_get('magic_quotes_sybase');

ini_set('allow_call_time_pass_reference', 1);
$a = ini_get('allow_call_time_pass_reference');

ini_set('highlight.bg', 1);
$a = ini_get('highlight.bg');

ini_set('session.bug_compat_42', 1);
$a = ini_get('session.bug_compat_42');

ini_set('session.bug_compat_warn', 1);
$a = ini_get('session.bug_compat_warn');

ini_set('y2k_compliance', 1);
$a = ini_get('y2k_compliance');

ini_set('zend.ze1_compatibility_mode', 1);
$a = ini_get('zend.ze1_compatibility_mode');

ini_set('safe_mode', 1);
$a = ini_get('safe_mode');

ini_set('safe_mode_gid', 1);
$a = ini_get('safe_mode_gid');

ini_set('safe_mode_include_dir', 1);
$a = ini_get('safe_mode_include_dir');

ini_set('safe_mode_exec_dir', 1);
$a = ini_get('safe_mode_exec_dir');

ini_set('safe_mode_allowed_env_vars', 1);
$a = ini_get('safe_mode_allowed_env_vars');

ini_set( /*comment*/ 'safe_mode_protected_env_vars', 1);
$a = ini_get('safe_mode_protected_env_vars');

ini_set('session.save_handler', 1); // Ok.
$a = ini_get('session.save_handler'); // Ok.

ini_set('always_populate_raw_post_data', 1);

ini_set('iconv.input_encoding', 'a');
$a = ini_get('iconv.input_encoding');

ini_set('iconv.output_encoding' /*comment*/, 'a');
$a = ini_get('iconv.output_encoding');

ini_set('iconv.internal_encoding', 'a');
$a = ini_get('iconv.internal_encoding');

ini_set('mbstring.http_input', 'a');
$a = ini_get('mbstring.http_input');

ini_set('mbstring.http_output', 'a');
$a = ini_get('mbstring.http_output');

ini_set('mbstring.internal_encoding', 'a');
$a = ini_get('mbstring.internal_encoding');

ini_set('always_populate_raw_post_data', 1);
$a = ini_get('always_populate_raw_post_data');

ini_set('asp_tags', 1);
$a = ini_get('asp_tags');

ini_set('xsl.security_prefs', 1);
$a = ini_get('xsl.security_prefs');

ini_set('fbsql.batchSize', 1);
$a = ini_get('fbsql.batchSize');

ini_set('ifx.allow_persistent', 1);
$a = ini_get('ifx.allow_persistent');

ini_set('ifx.blobinfile', 1);
$a = ini_get('ifx.blobinfile');

ini_set('ifx.byteasvarchar', 1);
$a = ini_get('ifx.byteasvarchar');

ini_set('ifx.charasvarchar', 1);
$a = ini_get('ifx.charasvarchar');

ini_set('ifx.default_host', 1);
$a = ini_get('ifx.default_host');

ini_set('ifx.default_password', 'abc');
$a = ini_get('ifx.default_password');

ini_set('ifx.default_user', 'abc');
$a = ini_get('ifx.default_user');

ini_set('ifx.max_links', 1);
$a = ini_get('ifx.max_links');

ini_set('ifx.max_persistent', 1);
$a = ini_get('ifx.max_persistent');

ini_set('ifx.nullformat', 1);
$a = ini_get('ifx.nullformat');

ini_set('ifx.textasvarchar', 1);
$a = ini_get('ifx.textasvarchar');

ini_set('detect_unicode', 1);
$a = ini_get('detect_unicode');

ini_set('mbstring.script_encoding', 1);
$a = ini_get('mbstring.script_encoding');

// Ini directive with variable for ini name.
$iniName = 'ifx.default_user';
ini_set($iniName, 'ifx.default_password'); // Ok, as we're interested in the variable name, not the value.

ini_set('mcrypt.algorithms_dir', 1);
$a = ini_get('mcrypt.algorithms_dir');

ini_set('mcrypt.modes_dir', 1);
$a = ini_get('mcrypt.modes_dir');

ini_set('session.entropy_file', 1);
$a = ini_get('session.entropy_file');

ini_set('session.entropy_length', 1);
$a = ini_get('session.entropy_length');

ini_set('session.hash_function', 1);
$a = ini_get('session.hash_function');

ini_set('session.hash_bits_per_character', 1);
$a = ini_get('session.hash_bits_per_character');


// Test correct function & parameter detection.
myClass::ini_set('ANIMALS', 'dog');
$object->ini_get('ANIMALS', 'dog');

class myClass {
    const ini_set = true;
    function ini_get() {}
}

ini_setter('ANIMALS', 'dog');
ini_set();

ini_set('mbstring.func_overload', 2);
$a = ini_get('mbstring.func_overload');

ini_set('sql.safe_mode', true);
$a = ini_get('sql.safe_mode');

ini_set('track_errors', true);
$a = ini_get('track_errors');

ini_set('opcache.fast_shutdown', true);
$a = ini_get('opcache.fast_shutdown');

ini_set('birdstep.max_links', 10);
$a = ini_get('birdstep.max_links');

ini_set('opcache.inherited_hack', true);
$a = ini_get('opcache.inherited_hack');

ini_set('pdo_odbc.db2_instance_name', 'string');
$a = ini_get('pdo_odbc.db2_instance_name');

ini_set('ibase.allow_persistent', true);
$a = ini_get('ibase.allow_persistent');

ini_set('ibase.max_persistent', '-1');
$a = ini_get('ibase.max_persistent');

ini_set('ibase.max_links', 10);
$a = ini_get('ibase.max_links');

ini_set('ibase.default_db', 'string');
$a = ini_get('ibase.default_db');

ini_set('ibase.default_user', 'string');
$a = ini_get('ibase.default_user');

ini_set('ibase.default_password', 'string');
$a = ini_get('ibase.default_password');

ini_set('ibase.default_charset', 'string');
$a = ini_get('ibase.default_charset');

ini_set('ibase.timestampformat', 'string');
$a = ini_get('ibase.timestampformat');

ini_set('ibase.dateformat', '%Y-%m-%d %H:%M:%S');
$a = ini_get('ibase.dateformat');

ini_set('ibase.timeformat', '%Y-%m-%d');
$a = ini_get('ibase.timeformat');

ini_set('pfpro.defaulthost', 'string');
$a = ini_get('pfpro.defaulthost');

ini_set('pfpro.defaultport', 'string');
$a = ini_get('pfpro.defaultport');

ini_set('pfpro.defaulttimeout', 'string');
$a = ini_get('pfpro.defaulttimeout');

ini_set('pfpro.proxyaddress', 'string');
$a = ini_get('pfpro.proxyaddress');

ini_set('pfpro.proxyport', 'string');
$a = ini_get('pfpro.proxyport');

ini_set('pfpro.proxylogon', 'string');
$a = ini_get('pfpro.proxylogon');

ini_set('pfpro.proxypassword', 'string');
$a = ini_get('pfpro.proxypassword');

ini_set('allow_url_include', 'On');
$a = ini_get('allow_url_include');

ini_set('opcache.load_comments', 'On');
$a = ini_get('opcache.load_comments');

ini_set('phar.extract_list', 1);
$test = ini_get('phar.extract_list');

ini_set('mime_magic.debug', 1);
$test = ini_get('mime_magic.debug');

ini_set('mime_magic.magicfile', 'path');
$test = ini_get('mime_magic.magicfile');

ini_set('hwapi.allow_persistent', 0);
$test = ini_get('hwapi.allow_persistent');

ini_set('crack.default_dictionary', 0);
$test = ini_get('crack.default_dictionary');

ini_set('dbx.colnames_case', 0);
$test = ini_get('dbx.colnames_case');

ini_set('fbsql.allow_persistent', 0);
$test = ini_get('fbsql.allow_persistent');

ini_set('fbsql.generate_warnings', 0);
$test = ini_get('fbsql.generate_warnings');

ini_set('fbsql.autocommit', 0);
$test = ini_get('fbsql.autocommit');

ini_set('fbsql.max_persistent', 0);
$test = ini_get('fbsql.max_persistent');

ini_set('fbsql.max_links', 0);
$test = ini_get('fbsql.max_links');

ini_set('fbsql.max_connections', 0);
$test = ini_get('fbsql.max_connections');

ini_set('fbsql.max_results', 0);
$test = ini_get('fbsql.max_results');

ini_set('fbsql.default_host', 0);
$test = ini_get('fbsql.default_host');

ini_set('fbsql.default_user', 0);
$test = ini_get('fbsql.default_user');

ini_set('fbsql.default_password', 0);
$test = ini_get('fbsql.default_password');

ini_set('fbsql.default_database', 0);
$test = ini_get('fbsql.default_database');

ini_set('fbsql.default_database_password', 0);
$test = ini_get('fbsql.default_database_password');

ini_set('ingres.allow_persistent', 0);
$test = ini_get('ingres.allow_persistent');

ini_set('ingres.default_database', 0);
$test = ini_get('ingres.default_database');

ini_set('ingres.default_password', 0);
$test = ini_get('ingres.default_password');

ini_set('ingres.default_user', 0);
$test = ini_get('ingres.default_user');

ini_set('ingres.max_links', 0);
$test = ini_get('ingres.max_links');

ini_set('ingres.max_persistent', 0);
$test = ini_get('ingres.max_persistent');

ini_set('msql.allow_persistent', 0);
$test = ini_get('msql.allow_persistent');

ini_set('msql.max_persistent', 0);
$test = ini_get('msql.max_persistent');

ini_set('msql.max_links', 0);
$test = ini_get('msql.max_links');

ini_set('mssql.allow_persistent', 0);
$test = ini_get('mssql.allow_persistent');

ini_set('mssql.max_persistent', 0);
$test = ini_get('mssql.max_persistent');

ini_set('mssql.max_links', 0);
$test = ini_get('mssql.max_links');

ini_set('mssql.min_error_severity', 0);
$test = ini_get('mssql.min_error_severity');

ini_set('mssql.min_message_severity', 0);
$test = ini_get('mssql.min_message_severity');

ini_set('mssql.compatibility_mode', 0);
$test = ini_get('mssql.compatibility_mode');

ini_set('mssql.connect_timeout', 0);
$test = ini_get('mssql.connect_timeout');

ini_set('mssql.timeout', 0);
$test = ini_get('mssql.timeout');

ini_set('mssql.textsize', 0);
$test = ini_get('mssql.textsize');

ini_set('mssql.textlimit', 0);
$test = ini_get('mssql.textlimit');

ini_set('mssql.batchsize', 0);
$test = ini_get('mssql.batchsize');

ini_set('mssql.datetimeconvert', 0);
$test = ini_get('mssql.datetimeconvert');

ini_set('mssql.secure_connection', 0);
$test = ini_get('mssql.secure_connection');

ini_set('mssql.max_procs', 0);
$test = ini_get('mssql.max_procs');

ini_set('mssql.charset', 0);
$test = ini_get('mssql.charset');

ini_set('sqlite.assoc_case', 0);
$test = ini_get('sqlite.assoc_case');

ini_set('mysql.allow_local_infile', 0);
$test = ini_get('mysql.allow_local_infile');

ini_set('mysql.allow_persistent', 0);
$test = ini_get('mysql.allow_persistent');

ini_set('mysql.max_persistent', 0);
$test = ini_get('mysql.max_persistent');

ini_set('mysql.max_links', 0);
$test = ini_get('mysql.max_links');

ini_set('mysql.trace_mode', 0);
$test = ini_get('mysql.trace_mode');

ini_set('mysql.default_port', 0);
$test = ini_get('mysql.default_port');

ini_set('mysql.default_socket', 0);
$test = ini_get('mysql.default_socket');

ini_set('mysql.default_host', 0);
$test = ini_get('mysql.default_host');

ini_set('mysql.default_user', 0);
$test = ini_get('mysql.default_user');

ini_set('mysql.default_password', 0);
$test = ini_get('mysql.default_password');

ini_set('mysql.connect_timeout', 0);
$test = ini_get('mysql.connect_timeout');

ini_set('sybase.allow_persistent', 0);
$test = ini_get('sybase.allow_persistent');

ini_set('sybase.max_persistent', 0);
$test = ini_get('sybase.max_persistent');

ini_set('sybase.max_links', 0);
$test = ini_get('sybase.max_links');

ini_set('sybase.interface_file', 0);
$test = ini_get('sybase.interface_file');

ini_set('sybase.min_error_severity', 0);
$test = ini_get('sybase.min_error_severity');

ini_set('sybase.min_message_severity', 0);
$test = ini_get('sybase.min_message_severity');

ini_set('sybase.compatability_mode', 0);
$test = ini_get('sybase.compatability_mode');

ini_set('assert.quiet_eval', 0);
$test = ini_get('assert.quiet_eval');

ini_set('auto_detect_line_endings', 0);
$test = ini_get('auto_detect_line_endings');

ini_set('log_errors_max_len', 0);
$test = ini_get('log_errors_max_len');

ini_set('date.default_latitude', 0);
$test = ini_get('date.default_latitude');

ini_set('date.default_longitude', 0);
$test = ini_get('date.default_longitude');

ini_set('date.sunset_zenith', 0);
$test = ini_get('date.sunset_zenith');

ini_set('filter.default', 0);
$test = ini_get('filter.default');

ini_set('filter.default_options', 0);
$test = ini_get('filter.default_options');

ini_set('oci8.old_oci_close_semantics', 0);
$test = ini_get('oci8.old_oci_close_semantics');

// Prevent false positives on PHP 8.0+ nullsafe method calls.
$obj?->ini_set('y2k_compliance', 1);

// Safeguard no false positives on PHP 8.1+ first class callables.
register_callback(ini_set(...));

// Safeguard against false positives when target param not found.
ini_set(value: 1); // Missing param.
$test = ini_get(ini: 'filter.default_options'); // Wrong param name.

ini_set('mysqlnd.fetch_data_copy', 0);
$test = ini_get('mysqlnd.fetch_data_copy');

ini_set('mysqli.reconnect', 0);
$test = ini_get('mysqli.reconnect');
