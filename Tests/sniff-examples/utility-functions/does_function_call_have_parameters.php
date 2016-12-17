<?php
/*
 * Tests for doesFunctionCallHaveParameters()
 *
 * Added after Github issue #120 / #152.
 */

/*
 * No parameters.
 */
some_function();
some_function(     );
some_function( /*nothing here*/ );
some_function(/*nothing here*/);

/*
 * Has parameters.
 */
some_function( 1 );
some_function(1,2,3);
some_function(true);

/*
 * Even though a language construct and not a function call, the function should work just as well for arrays.
 */

/*
 * No parameters.
 */
$foo = array();
$foo = array(     );
$foo = array( /*nothing here*/ );
$foo = array(/*nothing here*/);
$bar = [];
$bar = [     ];
$bar = [ /*nothing here*/ ];
$bar = [/*nothing here*/];

/*
 * Has parameters.
 */
$foo = array( 1 );
$foo = array(1,2,3);
$foo = array(true);
$bar = [ 1 ];
$bar = [1,2,3];
$bar = [true];
