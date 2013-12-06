<?php

function some_random_function() {} // Verify sniff doesn't flag this line
some_random_function(); // Verify sniff doesn't flag this line
ini_set('display_errors', 1); // Verify sniff doesn't flag this ini directive

ini_set('allow_url_include', 1);
$test = ini_get('allow_url_include');
ini_set("allow_url_include", 1);

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
