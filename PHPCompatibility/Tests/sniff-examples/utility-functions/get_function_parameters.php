<?php

/* Case S1 */
myfunction( 1, 2, 3, 4, 5, 6, true );

/*
 * Propertly deal with nested parenthesis.
 * Also see Github issues #111 / #114 / #151.
 */
/* Case S2 */
dirname( dirname( __FILE__ ) ); // 1
/* Case S3 */
mktime($stHour, 0, 0, $arrStDt[0], $arrStDt[1], $arrStDt[2]); // 6

/*
 * Deal with unnecessary comma after last param.
 */
/* Case S4 */
json_encode( array(), );

/*
 * Issue #211 - deal with short array syntax within parameters.
 */
/* Case S5 */
json_encode(['a' => $a,] + (isset($b) ? ['b' => $b,] : []));

/*
 * Even though a language construct and not a function call, the functions should
 * work just as well for long arrays.
 */
/* Case A1 */
$foo = array(some_call(5, 1), another(1), why(5, 1, 2), 4, 5, 6); // 6
/* Case A3 */
$foo = array( 1, 2, 3, 4, 5, 6, true );
/* Case A4 */
$foo = array('a' => $a, 'b' => $b, 'c' => $c);

// Same goes for short arrays.
/* Case A2 */
$bar = [0, 0, date('s'), date('m'), date('d'), date('Y')]; // 6
/* Case A5 */
$bar = [str_replace("../", "/", trim($value))]; // 1
/* Case A6 */
$bar = [0 => $a, 2 => $b, (isset($c) ? 6 => $c : 6 => null)];
