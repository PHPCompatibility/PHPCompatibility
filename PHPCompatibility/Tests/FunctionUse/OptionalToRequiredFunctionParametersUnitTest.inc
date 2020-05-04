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
