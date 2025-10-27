<?php

// Some other type casts.
(string) 1234;
(float) '1.5';

// Deprecated unset type cast.
(unset) $a;

// Verify space & case independency.
(	unset	) $a;
( Unset ) $a;

// PHP 7.4 deprecated real type cast.
$a = (real) '1.5';
$a = ( real ) '1.5';
$a = (double) '1.5'; // Make sure this doesn't throw a false positive.

// PHP 8.5 deprecated type casts.
$a = (integer) '15';
$a = (boolean) 1;
$a = (   DOUBLE   ) '1.5';
$a = (binary) '15';

// More false positives safeguards.
$a = (int) '15';
$a = (Bool) 1;
