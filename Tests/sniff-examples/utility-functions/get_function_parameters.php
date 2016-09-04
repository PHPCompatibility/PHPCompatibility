<?php
/*
 * Count function parameters.
 */
myfunction(1);
myfunction( 1, 2 );
myfunction(1, 2, 3);
myfunction(1, 2, 3, 4);
myfunction(1, 2, 3, 4, 5);
myfunction(1, 2, 3, 4, 5, 6);
myfunction( 1, 2, 3, 4, 5, 6, true );

/*
 * Propertly deal with nested parenthesis.
 * Also see Github issues #111 / #114 / #151.
 */
dirname( dirname( __FILE__ ) ); // 1
(dirname( dirname( __FILE__ ) )); // 1
dirname( plugin_basename( __FILE__ ) ); // 1
dirname( plugin_basename( __FILE__ ), 2 ); // 2
unserialize(trim($value, "'")); // 1
dirname(str_replace("../","/", $value)); // 1
dirname(str_replace("../", "/", trim($value))); // 1
dirname( plugin_basename( __FILE__ ), trim( 2 ) ); // 2
mktime($stHour, 0, 0, $arrStDt[0], $arrStDt[1], $arrStDt[2]); // 6
mktime(0, 0, 0, date('m'), date('d'), date('Y')); // 6
mktime(0, 0, 0, date('m'), date('d') - 1, date('Y') + 1); // 6
mktime(0, 0, 0, date('m') + 1, date('d'), date('Y')); // 6
mktime(date('H'), 0, 0, date('m'), date('d'), date('Y')); // 6
mktime(0, 0, date('s'), date('m'), date('d'), date('Y')); // 6
mktime(some_call(5, 1), another(1), why(5, 1, 2), 4, 5, 6); // 6

/*
 * Testing multi-line function calls.
 */
filter_input_array(
    INPUT_POST,
    $args,
    false
); // 3

gettimeofday (
               true
             ); // 1

/*
 * Deal with unnecessary comma after last param.
 */
json_encode( array(), );

/*
 * Issue #211 - deal with short array syntax
 */
json_encode(['a' => 'b',]);
json_encode(['a' => $a,]);
json_encode(['a' => $a,] + (isset($b) ? ['b' => $b,] : []));
json_encode(['a' => $a,] + (isset($b) ? ['b' => $b, 'c' => $c,] : []));
json_encode(['a' => $a, 'b' => $b] + (isset($c) ? ['c' => $c, 'd' => $d] : []));
json_encode(['a' => $a, 'b' => $b] + (isset($c) ? ['c' => $c, 'd' => $d,] : []));
json_encode(['a' => $a, 'b' => $b] + (isset($c) ? ['c' => $c, 'd' => $d, $c => 'c'] : []));
json_encode(['a' => $a,] + (isset($b) ? ['b' => $b,] : []) + ['c' => $c, 'd' => $d,]);
json_encode(['a' => 'b', 'c' => 'd',]);
json_encode(['a' => ['b',],]);
json_encode(['a' => ['b' => 'c',],]);
json_encode(['a' => ['b' => 'c',], 'd' => ['e' => 'f',],]);
json_encode(['a' => $a, 'b' => $b,]);
json_encode(['a' => $a,] + ['b' => $b,]);
json_encode(['a' => $a] + ['b' => $b, 'c' => $c,]);
json_encode(['a' => $a, 'b' => $b] + ['c' => $c, 'd' => $d]);
json_encode(['a' => $a, 'b' => $b] + ['c' => $c, 'd' => $d,]);
json_encode(['a' => $a, 'b' => $b] + ['c' => $c, 'd' => $d, $c => 'c']);
json_encode(['a' => $a, 'b' => $b,] + ['c' => $c]);
json_encode(['a' => $a, 'b' => $b,] + ['c' => $c,]);
json_encode(['a' => $a, 'b' => $b, 'c' => $c]);
json_encode(['a' => $a, 'b' => $b, 'c' => $c,] + ['c' => $c, 'd' => $d,]);
