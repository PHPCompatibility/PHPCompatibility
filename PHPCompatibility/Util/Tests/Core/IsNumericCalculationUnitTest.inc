<?php

/*
 * Make sure that numeric calculations are correctly identified.
 *
 * The below should *NOT* be recognized as numeric calculations.
 */

/* test A1 */
$a = 10;

/* test A2 */
$a = [] + array();

/* test A3 */
$a = $b + $c;

/* test A4 */
$a = 'not a numeric string' . 'nor this';

/* test A5 */
$a = 10 << 2;


/*
 * The below should be recognized as numeric calculations.
 */

/* test B1 */
$a = 10 * 5;

/* test B2 */
$a = 10 + 5;

/* test B3 */
$a = -10 - +-+5;

/* test B4 */
$a = 10 + 5 * -3.2 - 20 / 2.1 % 1 ** 3;

/* test B5 */
$b = - false + '0';

/* test B6 */
$a = 10 + 'not a numeric string' * 3;

/* test B7 */
$a = 10 * 3 + '123 numeric start of string';
