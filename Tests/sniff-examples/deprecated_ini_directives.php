<?php

ini_set('define_syslog_variables', 'a');
$a = ini_get('define_syslog_variables');

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

ini_set('safe_mode_protected_env_vars', 1);
$a = ini_get('safe_mode_protected_env_vars');

ini_set('session.save_handler', 1); // Ok.
$a = ini_get('session.save_handler'); // Ok.

ini_set('always_populate_raw_post_data', 1);

ini_set('iconv.input_encoding', 'a');
$a = ini_get('iconv.input_encoding');

ini_set('iconv.output_encoding', 'a');
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
