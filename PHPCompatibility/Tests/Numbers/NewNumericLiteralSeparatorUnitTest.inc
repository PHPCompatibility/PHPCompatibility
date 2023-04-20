<?php

// OK: ordinary numeric sequences.
$a = 1000000000;
$b = 107925284.88;
$discount = 13500;

// OK: Constant name starting with an underscore is perfectly valid.
const _100 = 100;

$a = 100 'test'; // Intentional parse error, not our concern.

// PHP 7.4 numeric sequences with a numeric literal separator.
$threshold = 1_000_000_000;  // a billion!
$testValue = 107_925_284.88; // scale is hundreds of millions
$discount  = 135_00;         // $135, stored as cents

$a = 6.674_083e-11; // float
$a = 6.674_083e+11; // float
$a = 299_792_458;   // decimal
$a = 0xCAFE_F00D;   // hexadecimal
$a = 0b0101_1111;   // binary
$a = 0137_041;      // octal

// More test cases.
$a = 1_2.3_4e1_23;

// Invalid use of underscores in numeric sequences.
// Each underscore in a numeric literal must be directly between two digits.
// The below snippets all produce "Parse error: syntax error" in PHP 7.4. Ignore these as not the concern of this sniff.
$a = 100_;   // trailing
$a = 1__1;   // next to underscore
$a = 1_.0;   // next to decimal point
$a = 1._0;   // next to decimal point
$a = 0x_123; // next to x
$a = 0b_101; // next to b
$a = 1_e2;   // next to e
$a = 1e_2;   // next to e

// More test cases.
$a = 0xCAFE_F00D_.892;

// PHP 8.1 explicit octal notation.
$octal = 0o137_041;
