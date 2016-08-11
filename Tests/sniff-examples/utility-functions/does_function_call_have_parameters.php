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
