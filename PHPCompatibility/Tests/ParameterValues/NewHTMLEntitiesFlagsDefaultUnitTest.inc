<?php

// OK cross-version.
echo htmlentities( $string, ENT_QUOTES, 'UTF-8' );
echo htmlspecialchars( $string, $flags);
echo \html_entity_decode( string: $string, encoding: 'ISO-8859-1', flags: ENT_COMPAT );
echo htmlspecialchars_decode($string, ENT_COMPAT | ENT_XHTML);
echo get_html_translation_table( $table, ENT_NOQUOTES, $encoding );

// Not OK - error when both PHP < 8.1 and PHP 8.1+ need to be supported.
echo htmlentities( $string );
echo htmlspecialchars($string);
echo HTML_entity_decode( string: $string, encoding: $encoding );
echo \htmlspecialchars_decode($string);
echo get_html_translation_table( $table );

// Safeguard against false positives on method calls and namespaced function calls.
ClassName::htmlentities( $string );
$obj->htmlspecialchars_decode($string);
$obj?->htmlentities( $string );
namespace\htmlspecialchars_decode($string);
\Fully\Qualified\htmlentities( $string );
Partially\Qualified\htmlspecialchars_decode($string);
