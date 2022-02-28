<?php

$binary = 0b001001101;
$invalidBinary = 0b0123456;

$validOctal = 061; // Ok.
$invalidOctal = 08;
$invalidOctal = 038;
$invalidOctal = 091;

$binaryUpper = 0B10001;

// Check cross-version handling of PHP 7.4 numeric literal sequences with underscore separators.
$binary = 0b00100_1101;
$validOctal = 06_1; // Ok.
$invalidOctal = 03_8;

// Intentional parse error.
$invalidBinary = 0b012_3456; // Will still trigger a warning for invalid binary.

// PHP 8.1: valid octal using explicit octal notation.
$validOctal = 0o123;
$validOctal = 0O321;

// PHP 8.1: invalid octal using explicit octal notation.
$invalidOctal = 0o91;
$invalidOctal = 0O282;
$invalidOctal = 0o28_2;
$invalidOctal = 0o2_82;
