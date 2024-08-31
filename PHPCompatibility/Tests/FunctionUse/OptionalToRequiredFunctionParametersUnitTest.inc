<?php

// These are ok.
parse_str($str, $output);

// These are not.
parse_str($str);
crypt( $str ); // Recommended.

// Prevent false positive on new. Issue #913.
$crypt = new Crypt('password');

// OK.
gmmktime($hour, $minute, $second);
mktime($hour);

// Not OK.
gmmktime();
mktime();

mb_parse_str($encoded_string, $result); // Ok.
mb_parse_str($encoded_string); // Not ok.

openssl_seal ( $data, $sealed_data, $env_keys, $pub_key_ids, $cipher_algo, $iv ); // Ok.
openssl_seal ( $data, $sealed_data, $env_keys, $pub_key_ids ); // Not ok.

openssl_open ( $sealed_data, $open_data, $env_key, $priv_key_id, $cipher_algo, $iv ); // Ok.
openssl_open ( $sealed_data, $open_data, $env_key, $priv_key_id ); // Not ok.

// Prevent false positives on namespaced functions.
\gmmktime(); // Not OK, global function.
MyNameSpace\mktime(); // OK.

// Safeguard support for PHP 8 named parameters.
parse_str(string: $str, result: $output); // OK.
parse_str(result: $output); // OK, well not really, the missing `string` parameter was always required, but that's not the concern of this sniff.
parse_str(string: $str); // Error.

openssl_seal( $data, $sealed_data, cipher_algo: $cipher_algo, iv: $iv, encrypted_keys: $encrypted_keys, public_key: $public_key, ); // Ok.
openssl_seal( $data, $sealed_data, iv: $iv, encrypted_keys: $encrypted_keys, public_key: $public_key, ); // Error.

// Prevent false positives on PHP 8.0+ nullsafe method calls.
$obj?->mktime();

stream_context_set_option($context, $wrapper, $option_name, $value);
stream_context_set_option($context, $options); // Error x 2.

pg_fetch_result($result, $row, $field);
pg_fetch_result($result, $field); // Error.
pg_fetch_result($result, field: $field); // Error.
pg_field_prtlen($result, $row, $field);
pg_field_prtlen($result, $field); // Error.
pg_field_is_null($result, $row, $field);
pg_field_is_null($result, $field); // Error.
