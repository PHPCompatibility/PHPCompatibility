<?php

/*
 * Valid cross-version: access and write to $GLOBALS with array dereferencing.
 * Valid cross-version: read-only access to $GLOBALS without array dereferencing.
 */
$GLOBALS['x'] = 1;
$GLOBALS['x']++;
$GLOBALS['x']--;
++$GLOBALS['x'];
--$GLOBALS['x'];
isset($GLOBALS['x']);
unset($GLOBALS [ 'x' ]);
$GLOBALS['z'][] = 1;
$GLOBALS['za'] =& $ref;
$ref2 =& $GLOBALS[ /*comment*/ 'x'];

foreach ($GLOBALS as $var => $value) {
    echo "$var => $value\n";
}

foreach ($GLOBALS as $var => $_)
    $$var =& $GLOBALS[$var];

foreach (get_defined_vars() as $var => $value) {
    $GLOBALS[$var] = $value;
}

function foo() {
    global $GLOBALS;

    $names = \array_keys($GLOBALS);

    return $GLOBALS;
}

if (!array_key_exists('key', $GLOBALS)) {
    $GLOBALS['key'] = '';
}

if ($GLOBALS) {
    // Do something.
}

$obj->assertArrayHasKey('key', $GLOBALS);
$obj->assertArrayNotHasKey('key', $GLOBALS);

var_dump($GLOBALS['td']['nsno']);

// Not calls to the PHP native functions which change the passed parameter by reference.
// Ignore as undetermined.
$var = $function($GLOBALS);
$var = match($GLOBALS) {};
$obj->array_splice($GLOBALS, 0, count($GLOBALS) );
ClassName::array_pop($GLOBALS);
$obj?->array_shift($GLOBALS);
My\extract($GLOBALS, EXTR_REFS);
$obj = new array_splice($GLOBALS);

by_ref($GLOBALS); // Undetermined, not a PHP native function, so we don't know if argument will be changed by reference or not.

// Not valid since PHP 5.4. Not the concern of this sniff.
class Foo {
    function array_pop($GLOBALS) {}
    function &array_shift($GLOBALS) {}
}

// Join behaviour has not changed. Already behaved as if it used a copy. See: https://3v4l.org/UNgnI
function changeVarViaJoinOfGlobals() {
    $a = 1;
    $arrayJoin  = [];
    $arrayJoin += $GLOBALS;
    $arrayJoin['a'] = 2;
    var_dump($a);
}


/*
 * PHP 8.1 compile error: Cannot append to $GLOBALS.
 * https://3v4l.org/HOYvC
 */
$GLOBALS[] = 'new';
$GLOBALS [ /*comment*/  ] = 'new';

/*
 * PHP 8.1: changed behaviour with variables with an int/float name.
 * Int: https://3v4l.org/O6j34, float: https://3v4l.org/RIZHo
 */
function mismatchedIntegerKeyHandling() {
    ${1} = 1;
    $GLOBALS[ 1 ] = 2;
    var_dump(${1}); // PHP < 8.1: int(1), PHP 8.1: int(2).

    $GLOBALS[ 23_5231 ]['key'][] = 2; // Key uses PHP 7.4 numeric literal with underscore.
    $GLOBALS[ 0o324 ]['obj']->prop = 2; // PHP 8.1 octal literal key.
}

function mismatchedFloatKeyHandling() {
    ${1.2435} = 1;
    $GLOBALS[ 1.2435 ] = 2;
    var_dump(${1.2435}); // PHP < 8.1: int(1), PHP 8.1: int(2).
}

/*
 * PHP 8.1: changed behaviour - the recursive 'GLOBALS' subkey no longer exists.
 * https://3v4l.org/ej4fN
 */
isset($GLOBALS['GLOBALS' /*comment*/]); // PHP < 8.1: true, PHP 8.1: false.
var_dump($GLOBALS[ "GLOBALS" ]); // PHP < 8.1 existed, PHP 8.1: not longer exists - Warning: Undefined global variable $GLOBALS.

/*
 * PHP 8.1 compile error: writes to $GLOBALS without dereferencing.
 * https://3v4l.org/Y0tvM
 */
unset($GLOBALS);
unset($something, $_GET['key'], $GLOBALS, $obj->prop);

/*
 * PHP 8.1 compile error: writes to $GLOBALS without dereferencing.
 * https://3v4l.org/44aBU
 */
$GLOBALS = [];
$GLOBALS = array();
$GLOBALS += array("foo" => "foo");
$GLOBALS =& $x;
$GLOBALS += get_defined_vars();
list($s, $GLOBALS, $h) = [1, 2, 3];
[$s, $GLOBALS, $h] = [1, 2, 3]; // False negative due to short list use.
foreach ([1] as $GLOBALS) {}
foreach ([1] as &$GLOBALS) {}

// Also problematic on PHP < 8.1 - Fatal error: Cannot assign reference to non referencable value -, but that's outside of the scope of this sniff.
list(&$GLOBALS) = [1];

// Also problematic on PHP < 8.1 - Uncaught TypeError: Cannot in/decrement array -, but that's outside of the scope of this sniff.
$GLOBALS++;
$GLOBALS--;
++$GLOBALS;
--$GLOBALS;

/*
 * PHP 8.1 compile error: Cannot acquire reference to $GLOBALS.
 * https://3v4l.org/fI97h
 */
$x =& $GLOBALS;
$x = &$GLOBALS;

/*
 * PHP 8.1: changed behaviour - assigning $GLOBALS to another variable now creates a copy.
 * https://3v4l.org/B3qju and https://3v4l.org/aS8AV
 */
function changeVarViaCopyOfGlobals() {
    $a = 1;
    $globals = $GLOBALS; // Pre-8.1: Ostensibly by-value copy, but not really. PHP 8.1: read-only copy.
    $globals['a'] = 2;
    var_dump($a); // PHP < 8.1: int(2), PHP 8.1: int(1).

    $globals ??= $GLOBALS; // Pre-8.1: Ostensibly by-value copy, but not really. PHP 8.1: read-only copy.
}

/*
 * PHP 8.1 runtime error: passing $GLOBALS by reference.
 */
extract($GLOBALS, EXTR_REFS); // Doesn't throw an error in PHP 8.1, but behaviour has changed according to the RFC.
array_splice($GLOBALS, 0, count($GLOBALS));
\array_pop($GLOBALS);
array_shift($GLOBALS);

// Using PHP 8.0 named parameters.
// Note: these tests will only work on PHP 8.0 due to PHP having renamed the relevant parameters in PHP 8.0...
array_splice(offset: 0, array: $GLOBALS, length: count($GLOBALS));
array_walk(callback: functionName, arg: $extra, array: $GLOBALS);

// Live coding.
// These tests have to be the last tests in the file.
$GLOBALS[ = 'x';

$GLOBALS
