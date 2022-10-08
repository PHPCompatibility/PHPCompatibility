<?php

/*
 * Using the spread operator for something other than first class callables.
 */

// PHP 5.6: function declarations using variadic arguments.
function foo( $param, ...$variadic);
$closure = function (&...$variadic) {};

// PHP 5.6: argument unpacking in function calls.
$return = function_call(...$array);
$return = function_call(...$array, named: $param);
test(...new ArrayIterator([1, 2, 3]));
test(...$args1, ...$args2);

// PHP 7.4: unpacking inside arrays.
// PHP 8.1: unpacking inside arrays with string keys is now also supported.
$fruits = ['banana', 'orange', ...$parts, 'watermelon'];
$array = [...$array1, ...$array2];


/*
 * PHP 8.1: first class callables.
 */
$callable = strlen(...);
return $closure(...);
$invokableObject(...);
$obj->method(  ...  );
$obj->$methodStr(...);
($obj->property)(... /*comment*/ );
Foo::method(...);
$classStr::$methodStr(
    /*comment*/
    ...
    );
self::{$complex . $expression}(...);
'strlen'(...);
[$obj, 'method'](...);
[Foo::class, 'method'](...);
