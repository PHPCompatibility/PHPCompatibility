<?php

// These are ok.
preg_match_all('`[a-z]+`', $subject, $matches);
stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_SSLv23_CLIENT);

// These are not.
preg_match_all('`[a-z]+`', $subject);
stream_socket_enable_crypto($fp, true);

bcscale( 10 ); // OK.
bcscale(); // PHP 7.3+.

$ip = getenv('REMOTE_ADDR'); // OK.
$ip = getenv(); // PHP 7.1+

array_push($stack, "apple", "raspberry"); // OK.
array_push(...$stack); // PHP 7.3+

array_unshift($queue, "apple", "raspberry"); // OK.
array_unshift(...$queue); // PHP 7.3+

ftp_fget ($ftp_stream, $handle, $remote_file, $mode, $resumepos ); // OK.
ftp_fget( $ftp_stream, $handle, $remote_file ); // PHP 7.3+
ftp_fput($ftp_stream,$remote_file,$handle); // PHP 7.3+
ftp_get ($ftp_stream, $local_file, $remote_file); // PHP 7.3+
ftp_nb_fget( $ftp_stream,$handle, $remote_file); // PHP 7.3+
ftp_nb_fput($ftp_stream, $remote_file, $handle); // PHP 7.3+
ftp_nb_get( $ftp_stream, $local_file, $remote_file); // PHP 7.3+
ftp_nb_put( $ftp_stream, $remote_file,$local_file); // PHP 7.3+
ftp_put( $ftp_stream, $remote_file, $local_file); // PHP 7.3+
ftp_put ( $ftp_stream, $remote_file, $local_file,$mode,$startpos); // OK.

array_merge($arrays); // OK.
array_merge(); // PHP 7.4+
array_merge_recursive(); // PHP 7.4+

fgetcsv($handle $length); // OK.
fgetcsv($handle); // PHP 5.1+.

xmlwriter_write_element($xmlwriter, $name); // PHP 5.2.3+.
xmlwriter_write_element_ns($xmlwriter, $prefix, $name, $uri); // PHP 5.2.3+.

imagepolygon($image, $points, $num_points, $color); // OK.
imagepolygon($image, $points, $color); // PHP 8.0+.
imageopenpolygon($image, $points, $color); // PHP 8.0+.
imagefilledpolygon($image, $points, $color); // PHP 8.0+.

array_diff_uassoc($array, $array2, $array3, $array4, $key_compare_func); // OK.
array_diff_ukey($array, $array2, $key_compare_func); // OK.
array_intersect_uassoc($array, $array2, $key_compare_func); // OK.
array_intersect_ukey($array, $array2, $key_compare_func); // OK.
array_udiff_assoc( $array1, $array2, $array3, $value_compare_func ); // OK.
array_udiff_uassoc($array1, $array2, $value_compare_func, $key_compare_func); // OK.
array_udiff($array1, $array2, $value_compare_func ); // OK.
array_uintersect_assoc( $array1, $array2, $value_compare_func ); // OK.
array_uintersect_uassoc($array1, $array2, $array3, $value_compare_func, $key_compare_func); // OK.
array_uintersect($array1, $array2, $value_compare_func ); // OK.

array_diff_assoc($array); // PHP 8.0+.
array_diff_key($array); // PHP 8.0+.
array_diff_uassoc($array, $key_compare_func); // PHP 8.0+.
array_diff_ukey(...$array, $key_compare_func); // PHP 8.0+.
array_diff($array); // PHP 8.0+.
array_intersect_assoc($array); // PHP 8.0+.
array_intersect_key($array); // PHP 8.0+.
array_intersect_uassoc($array, $key_compare_func); // PHP 8.0+.
array_intersect_ukey(...$array, $key_compare_func); // PHP 8.0+.
array_intersect($array); // PHP 8.0+.
array_udiff_assoc ( $array, $value_compare_func ); // PHP 8.0+.
array_udiff_uassoc($array, $value_compare_func, $key_compare_func); // PHP 8.0+.
array_udiff($array, $value_compare_func ); // PHP 8.0+.
array_uintersect_assoc( $array, $value_compare_func ); // PHP 8.0+.
array_uintersect_uassoc($array, $value_compare_func, $key_compare_func); // PHP 8.0+.
array_uintersect($array, $value_compare_func ); // PHP 8.0+.
