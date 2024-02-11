<?php

// These are ok.
mktime(1, 2, 3, 4, 5, 6);
gmmktime(1, 2, 3, 4, 5, 6);

// These are not.
mktime(1, 2, 3, 4, 5, 6, true);
gmmktime(1, 2, 3, 4, 5, 6, true);

ldap_first_attribute( $link_identifier, $result_entry_identifier, $ber_identifier );
ldap_next_attribute( $link_identifier, $result_entry_identifier, $ber_identifier );

define('CONSTANT', 'foo'); // OK.
define( 'CONSTANT', 'foo', true, );

curl_version(); // OK.
curl_version( CURLVERSION_NOW ); // OK.
curl_version( 4 ); // OK when on Curl version 4.
curl_version( 10505678 );
curl_version( $age );

mb_decode_numericentity($str, $convmap); // OK.
mb_decode_numericentity($str, $convmap, $encoding, $is_hex); // Error.

$conn = pg_connect ( $connection_string, $connect_type ); // OK.
$conn = pg_connect ( $host, $port, $options, $tty, $dbname ); // Error x 3.

imap_headerinfo($imap_stream, $msg_number); // OK.
imap_headerinfo($imap_stream, $msg_number, $fromlength, $subjectlength, $defaulthost); // Error.

odbc_exec($connection_id, $query_string); // OK.
odbc_exec($connection_id, $query_string, $flags); // Error.
odbc_do($connection_id, $query_string, $flags); // Error.

imagepolygon($image, $points, $num_points, $color); // Warning.
imageopenpolygon($image, $points, $num_points, $color); // Warning.
imagefilledpolygon($image, $points, $num_points, $color); // Warning.

mysqli_get_client_info($mysql); // Warning.

// Safeguard support for PHP 8 named parameters.
define(constant_name: 'CONSTANT', value : 'foo'); // OK.
define( case_insensitive : true, value : 'foo', constant_name: 'CONSTANT' ); // Error.

imagepolygon($image, color: $color, points: $points); // OK, well not really as this function sig doesn't support named params, but that's not the concern of this sniff.
imagepolygon($image, color: $color, points: $points, num_points: $num_points); // Warning.

// Prevent false positives on PHP 8.0+ nullsafe method calls.
$obj?->define( 'CONSTANT', 'foo', true, ); // OK.

// Prevent false positive on param which isn't actually a param.
define( 'CONSTANT', 'foo', /*not passed for cross-version compat*/, ); // OK, well, not really, parse error due to trailing comma, but that's not the concern of this sniff

// Safeguard error is thrown on the line containing the parameter.
ldap_first_attribute(
    $link_identifier,
    $result_entry_identifier,
    $ber_identifier // Error.
);

imagerotate($image, $angle, $bg_color); // OK.
imagerotate($image, $angle, $bg_color, $ignore_transparent); // Error.

ldap_exop($ldap, $request_oid, $request_data, $controls, $response_data, $response_oid); // Error x 2.

session_set_save_handler($handler, true); // OK.
session_set_save_handler($open, $close, $read, $write, $destroy, $gc, $create_sid, $validate_sid, $update_timestamp); // Error x 7.
