<?php
/*
 * Test mb_strimwidth() should no be called with a negative string $width since PHP 8.3.
 */

/*
 * OK.
 */
// Not our target.
echo MB_STRIMWIDTH;
$obj->mb_strimwidth;
$obj->mb_strimwidth();
$obj?->mb_strimwidth();
MyClass::mb_strimwidth();
My\Vendor\mb_strimwidth();
#[MB_Strimwidth()]
function do_something() {}

mb_strimwidth();
mb_strimwidth( /*comment */ );
mb_strimwidth($str, 100); // No $width passed, fatal error for missing required args, but not the concern of this sniff.
mb_strimwidth($str, -100, 10, '' );
mb_strimwidth($str, -100, -10 * -2, '' );
Mb_Strimwidth(
    // Some comment.
    width: 0
    // phpcs:ignore Standard.Category.Sniff -- for reasons.
);
$a = mb_strimwidth(trim_marker: '', start: -2, string: $str, ); // No $width passed, fatal error for missing required args, but not the concern of this sniff.
$a = mb_strimwidth(trim_marker: '', width: 5, start: -2, string: $str, );

// Undetermined.
mb_strimwidth($str, -100, $width, '' );
mb_strimwidth($str, -100, $width * 2, '' );
\mb_strimwidth($str, -100, ClassName::WIDTH, '' );

/*
 * PHP 8.3: calling mb_strimwidth() with a negative string $width.
 */
$a = mb_strimwidth($str, -100, -10, '' );

// Safeguard support for PHP 8 named parameters.
$a = \MB_StrimWidth(start: -2, width: -10, string: $str, trim_marker: '');
