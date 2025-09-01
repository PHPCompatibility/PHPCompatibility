<?php

/*
 * Not our targets.
 */
$obj->array_merge_recursive($GLOBALS, $GLOBALS );
ClassName::array_merge_recursive($GLOBALS, $GLOBALS);
$obj?->array_merge_recursive($GLOBALS, $GLOBALS);
My\array_merge_recursive($GLOBALS, $GLOBALS);
$obj = new array_merge_recursive($GLOBALS, $GLOBALS);
register_callback(array_merge_recursive(...));

/*
 * Valid cross-version: not passing $GLOBALS twice to array_merge_recursive().
 */
array_merge_recursive();
\array_merge_recursive($GLOBALS);
array_merge_recursive(my_get_array(), $GLOBALS, $someOtherArray);
array_merge_recursive($GLOBALS, $someOtherArray);
ARRAY_MERGE_RECURSIVE($GLOBALS, $GLOBALS['_GET']);
array_merge_recursive($GLOBALS['_POST'], $GLOBALS);

/*
 * PHP 8.0: catchable fatal error: Recursion detected.
 * PHP 7.x: warning: Recursion detected.
 * PHP < 7.0: would exhaust memory to the limit.
 */
array_merge_recursive($GLOBALS, $GLOBALS);
\Array_Merge_Recursive($GLOBALS, $someOtherArray, $GLOBALS, my_get_array(),);

// More safeguards against false positives on namespaced function calls.
namespace\array_merge_recursive($GLOBALS, $GLOBALS);
\Fully\Qualified\array_merge_recursive($GLOBALS, $GLOBALS);
