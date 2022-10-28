<?php

$hex = 'aa78b5'; // Ok.

$hex = '0xaa78b5';
$hexUpper = '0Xbb99EF';

// Issue 1345: prevent false positives when used with the GMP extension/functions which still support hex numeric strings.
$test = hexdec('aa78b5'); // Ok.
$test = gmp_and($n, '0x7ff0000000'); // Ok.
$test = \Gmp_Div('0xfffffffff', $n); // Ok.

// But don't ignore the issue when in other function calls or non-function calls.
$test = bcadd($n, '0x7ff000000');
$test = gmp_import($data, '0x7', GMP_NATIVE_ENDIAN);
$test = gmp_userland_function($n, '0x7fef');
$test = MyNs\gmp_and($n, '0x7ff00');
$test = $obj->gmp_and($n, '0x7ff00');
$test = $obj?->gmp_and($n, '0x7ff00');
$test = MyClass::gmp_and($n, '0x7ff00');
$test = new GMP_And($n, '0x7ff00');
class Foo {
    public function gmp_and($param = '0x7ff00') {}
}

// Or when the hex numeric string is used in a parameter which doesn't support it.
$test = gmp_init($num, '0x7ff00');
$test = gmp_ROOT(nth: '0x7ff00', num: 10);
