<?php

/*
 * OK.
 */
// Not our target - safeguard against false positives on method calls and namespaced function calls.
ClassName::trim( $string );
$obj->ltrim($string);
$obj?->rtrim( $string );
namespace\trim($string);
\Fully\Qualified\ltrim( $string );
Partially\Qualified\rtrim($string);

// OK cross-version.
echo trim( $string, '/' );
echo trim( $string, " \f\n\r\t\v\x00" );
echo ltrim($string, $chars);
echo \rtrim( characters: "\n\r", string: $string, );
echo trim($string, MyClass::CONST_NAME);
echo LTRIM( $text, $obj->chars );
echo RTrim( $text, $obj->get_chars() );

/*
 * Not OK - error when both PHP < 8.6 and PHP 8.6+ need to be supported.
 */
echo trim( $string );
echo ltrim($string, /*comment*/ );
echo RTrim( string: $string );
echo \ltrim($string);
