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

openssl_seal ( $data, $sealed_data, $env_keys, $pub_key_ids, $method, $iv ); // Ok.
openssl_seal ( $data, $sealed_data, $env_keys, $pub_key_ids ); // Not ok.

openssl_open ( $sealed_data, $open_data, $env_key, $priv_key_id, $method, $iv ); // Ok.
openssl_open ( $sealed_data, $open_data, $env_key, $priv_key_id ); // Not ok.
