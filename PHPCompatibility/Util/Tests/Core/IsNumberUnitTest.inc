<?php

/*
 * Make sure that numbers are correctly identified.
 *
 * The below should *NOT* be recognized as numbers.
 */
 
/* test 1 */
$a = [];

/* test 2 */
$a = - $b;

/* test 4 */
$a = +;

/* test 5 */
$a = new SomeClass;

/* test 6 */
$a = 1-;

/* test 7 */
$a = 1.23-;

/* test 8 */
$a = 1.23 - 1;

/* test 9 */
$a = 5 * 8;

/* test 10 */
$a = '10 things' . ' or nothing';

/*
 * Make sure that zero numbers are correctly identified.
 *
 * The below should be recognized as numbers (integers).
 */

/* test ZI1 */
$a = 0;

/* test ZI2 */
$a = +0;

/* test ZI3 */
$a = - false;

/* test ZI4 */
$a = '0';

/* test ZI5 */
$a = - '        0 things';

/* test ZI6 */
$a = null;

/* test ZI7 */
$a = - 'not a numeric string';


/*
 * Make sure that zero numbers are correctly identified.
 *
 * The below should be recognized as numbers (integers).
 */

/* test ZF1 */
$a = 0.0;

/* test ZF2 */
$a = - 0.0000000000;


/*
 * Make sure that numbers are correctly identified.
 *
 * The below should be recognized as numbers (integers).
 */

/* test I1 */
$a = 1;

/* test I2 */
$a = -10;

/* test I3 */
$a = /* */     +          10;

/* test I4 */
$a = - /* comment */ 10;

/* test I5 */
$a = +
    // comment
	10;

/* test I6 */
$a = '10';

/* test I7 */
$a = +  /* comment */ "10";

/* test I8 */
$a = - '10 barbary lane'; // PHP 7.1+: Non well-formed numeric value, but will still work.

/* test I9 */
$a = <<<EOT
10
EOT;

/* test I10 */
// PHP will only look at the first line!
$a = - <<<'EOT'
1
0
EOT;

/* test I11 */
$a = '        10 barbary lane';

/* test I12 */
$a = + '
        10 barbary lane';

/* test I13 */
$a = - '0xCC00F9'; // Though the behaviour is different between PHP 5 vs PHP 7.

/* test I14 */
$a = - true;

/* test I15 */
$a = + '  0123 things';

/* test I16 */
$a = -+-+10;

/*
 * Make sure that numbers are correctly identified.
 *
 * The below should be recognized as numbers (floats).
 */

/* test F1 */
$a = 1.23;

/* test F2 */
$a = -10.123;

/* test F3 */
$a = +          10.123;

/* test F4 */
$a = - /* comment */ 10.123;

/* test F5 */
$a = +
    // phpcs:ignore Standard.Category.Sniff -- testing handling of PHPCS annotations.
	10.123;

/* test F6 */
$a = '10.123';

/* test F7 */
$a = +  /* comment */ "10.123";

/* test F8 */
$a = - '10E3 barbary lane'; // PHP 7.1+: Non well-formed numeric value, but will still work.

/* test F9 */
$a = - '10e8 barbary lane'; // PHP 7.1+: Non well-formed numeric value, but will still work.

/* test F10 */
$a = <<<EOT
10.123
EOT;

/* test F11 */
$a = +'0.123';
