<?php

// OK.
echo htmlentities( $string, ENT_QUOTES, 'UTF-8' );
echo htmlspecialchars( $string, encoding: $encoding, flags: ENT_COMPAT, double_encode: false );
echo html_entity_decode( $string, ENT_COMPAT, $encoding );
echo get_html_translation_table( $table, ENT_COMPAT, $encoding );

// Not OK - error.
echo htmlentities( string: $string, flags: $flags, double_encode: false );
echo htmlspecialchars($string);
echo HTML_entity_decode( $string, ENT_COMPAT );
echo get_html_translation_table( $table );
