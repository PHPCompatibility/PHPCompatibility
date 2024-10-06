<?php

// Not our target.
$obj?->fputcsv($stream, $fields, $separator, $enclosure, '');
MyClass::fgetcsv($stream, $length, $separator, $enclosure, '');
My\Ns\str_getcsv($string, $separator, $enclosure, '');
$class = new fputcsv($stream, $fields, $separator, $enclosure, '');

// Undetermined value. Ignore for the PHP 7.4 change as undetermined. OK for PHP 8.4 as param was passed.
fputcsv($stream, $fields, $separator, $enclosure, $escape);
fgetcsv($stream, $length, $separator, $enclosure, $escape);
\str_getcsv($string, $separator, $enclosure, $escape);
fputcsv($stream, $fields, $separator, $enclosure, get_escape(''));
fgetcsv($stream, $length, $separator, $enclosure, MyClass::ESCAPE_CHAR);
\str_getcsv($string, $separator, $enclosure, $obj->escape_char);
fgetcsv($stream, $length, $separator, $enclosure, $obj?->get(['escape', '']));

// OK in all versions, target param is a non-empty string.
\fputcsv($stream, $fields, $separator, $enclosure, '\\');
fgetcsv($stream, escape: '$');
str_getcsv($string, $separator, $enclosure, " ");

// OK for PHP < 8.4. Flag for PHP 8.4+. Target param not provided.
fputcsv();
\fgetcsv();
str_getcsv();
fputcsv($stream, $fields);
\fgetcsv($stream);
str_getcsv($string);
fputcsv($stream, $fields, $separator $enclosure, eol: "\r\n");
fgetcsv($stream, $length, $separator, $enclosure, /* comment */);
\str_getcsv($string, escape_char: ''); // Typo in param name.
\fgetcsv($string, escape: /*comment*/, length: 10); // Parse error, empty param value, but not our concern.

// PHP 7.4+: passing an empty string as the $escape parameter will disable escaping.
fputcsv($stream, $fields, /*comment*/ escape: /*comment*/ ''/*comment*/ );
\fgetcsv($stream, $length, $separator, $enclosure, '');
str_getcsv(
    $string,
    $separator,
    $enclosure,
    // phpcs:ignore Stnd.Cat.Sniff -- for (testing) reasons.
    "",
);
