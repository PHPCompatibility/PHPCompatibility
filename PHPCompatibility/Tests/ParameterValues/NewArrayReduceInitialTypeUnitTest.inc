<?php

// OK.
array_reduce( $array, $callback );
array_reduce( $array, $callback, 0 );
array_reduce( $array, $callback, 15 );
array_reduce( $array, $callback, 15 * 3 );
array_reduce( $array, $callback, 24 * 60 * 60 );
array_reduce( $array, $callback, /*initial*/ -100 /*initial*/ );
array_reduce( $array, $callback, 'string' ); // Will be typecast by PHP.
array_reduce( $array, $callback, 20.5 ); // Will be typecast by PHP.
array_reduce( $array, $callback, false ); // Will be typecast by PHP.
array_reduce( $array, $callback, initial: null ); // Will be typecast by PHP.

// Not OK - error.
array_reduce( $array, $callback, array() );

// Not OK - warning.
array_reduce( $array, $callback, $initial /*initial*/ );
array_reduce( $array, $callback, $this->initial );
array_reduce( $array, $callback, self::INITIAL );
array_reduce( $array, $callback, 15 * $initial );
array_reduce( $array, $callback, new stdClass );

// Safeguard support for PHP 8 named parameters.
array_reduce( $array, initial: array(), callback: $callback ); // Error.
