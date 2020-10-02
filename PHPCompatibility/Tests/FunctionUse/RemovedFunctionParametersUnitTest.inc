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
