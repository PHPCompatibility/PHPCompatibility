<?php

// Valid spread operator use prior to PHP 7.4.
function myFunction(...$params) {}
function_call(1, ...$values);
$arr6 = array(getArr(1, 2, ...$passedToFunctionNotArray), 'c');

/*
 * PHP 7.4: New spread operator in array expressions.
 */
$fruits = ['a' => 'banana', 'b' => 'orange', ...$parts, 'watermelon'];

$arr1 = [1, 2, 3];
$arr2 = [...$arr1];
$arr3 = array(0, ...$arr1);
$arr4 = array(
    ...$arr1,
    ...$arr2,
    111,
);
$arr5 = [
    ...$arr1,
    [...$arr1]
    getArr(1, 2, ...$passedToFunctionNotArray),
];
$arr5 = array(
    ...$arr1,
    array(...$arr1),
    getArr(1, 2, ...$passedToFunctionNotArray),
);

function getArr() { return ['a', 'b']; }
$arr6 = array(...getArr(), 'c');
$arr7 = [...new ArrayIterator(['a', 'b', 'c'])];
$arr8 = [...arrGen()];

// Unpacking array by reference is not supported in PHP 7.4, but not our concern, throw an error anyway.
$arr2 = [...&$arr1];

// Short list, not short array. Parse error. Ignore.
[$a, ...$b] = $array;

// Attributes were introduced after PHP 7.4, so we can safely ignore them for this sniff,
// as these will always be treated as comments prior to PHP 8.0.
class Foo {
    #[IsValid([0, ...[1, 2, 3]])]
    public function __construct() {}
}

// Intentional parse error. These are not valid syntax. We should not identify any errors here.
$array = [(...$test)];
$array = array({...$test});
$array = [{];

// Intentional parse error. This has to be the last test in the file.
$arr5 = array(
    ...$arr1,
    array(...$arr1
