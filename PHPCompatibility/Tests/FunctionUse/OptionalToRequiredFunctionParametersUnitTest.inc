<?php

// These are ok.
parse_str($str, $output);

// These are not.
parse_str($str);
crypt( $str ); // Recommended.
