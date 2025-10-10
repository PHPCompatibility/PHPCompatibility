<?php

/*
 * Not our targets.
 */
$obj->exit();
$obj?->die();

class Foo {
    const die = 5;
    const exit = 10;

    public function exit() {}
    public function die() {}
}

Foo::exit();

// Exit/die is still a reserved keyword and cannot be used to declare (global) constants/functions or goto labels.
// The below statements are all parse errors, both in PHP 8.4 as well as PHP < 8.4.
const exit = 10;
function die() {}

goto die;
echo "In between\n";
die:
do_something();

/*
 * All okay (from a parameter type point of view).
 */
exit;
die;
exit();
die();
die(0);
exit(1);
exit(0b101);
die(0xAF);
die( /*comment*/ 0o53 /*comment*/ );
exit(254);
// Status code 255 is reserved by PHP itself, but PHP does not refuse it. Behaviour is unchanged, so not our concern.
exit(255);
// Status code 256 is invalid and is ignored, resulting in an actual status code of 0. Behaviour is unchanged, so not our concern.
exit(256);

die('status');
exit('status' . 'type');
exit( "status $message" );
die( <<<'EOD'
status
EOD
);
exit( <<<EOD
my $status message
EOD
);

// Known PHP native constants which result in an integer or string.
exit(PHP_INT_MIN);
die(\PHP_VERSION);
die(E_ERROR + \E_WARNING);

// Assume the cast is for the whole parameter.
exit((string) $status);
die((int) STATUS);

// Undetermined. Ignore.
exit($variable);
die(STATUS_CODE);
exit(get_code([1,2]));
die($obj->get());
exit(\PHP_VERSION()); // Function name mirrors a PHP native constant, but should not be seen as integer.

die(code: 10.5); // Incorrect parameter name.

// Invalid as die only accepts one parameter, but that's not our concern.
die(...$var);

// Behaviour unchanged: Fatal error: Uncaught Error: Object of class stdClass could not be converted to string.
// Mind: a class may implement Stringable or __toString(), so this _may_ work just fine, but in that case,
// again, there is no change in behaviour.
exit( new stdClass );

// Simple operations involving only integers or strings are recognized and handled by the sniff.
exit(10 + 2);
die ('string' . 'string');
die ('string' . 10);

// Potential future scope: infer type of compound values based on operators.
// Currently these are blindly ignored (though for the below examples the behaviour is unchanged anyway).
exit(1 << 2);
exit(10 + ['hello']); // TypeError: unsupported operand types.
die(true + 10);


/*
 * PHP 8.4+: passing an array (or a non-stringable object or a resource, but we can rarely detect that) will now result in a TypeError.
 */

// Was array to string conversion warning + status message "Array", PHP 8.4: TypeError.
exit([]);
die( array(1, 2) );
exit( list($a, $b) = $array);
die( [$a, $b] = $array);

// Was status message "Resource id #...", PHP 8.4: TypeError.
die(STDERR); // PHP native resource.

/*
 * PHP 8.4+: passing a boolean, float or null will convert to an integer exit code instead of a string status message (warning).
 *
 * Note: the declare statements in the below tests would in real code result in a Fatal error for not being at the top
 * of the file (or another error for being invalid/empty). That's not the concern of these tests though.
 * The tests check that if a strict_types declaration is seen, the sniff behaviour changes, not whether the
 * declare statements themselves are correct.
 */
exit(true); // Unless strict_types is used, will be juggled to 1.
die( false ); // Unless strict_types is used, will be juggled to 0.
exit(\PHP_ZTS); // PHP native constant of type boolean.
exit ( 10.0 ); // Unless strict_types is used, will be juggled to integer 10.
exit(status: 1.5); // Will also throw an "Implicit conversion from float to int loses precision" deprecation notice.
die(1.5 + 1); // Will also throw an "Implicit conversion from float to int loses precision" deprecation notice.
die(TRADER_REAL_MIN); // PHP native constant of type float (if available).
die( null ); // Will also throw a "Passing null to parameter #1 ($status) of type string|int is deprecated" notice.

// However, if strict_types is on, these will all result in a TypeError and should be flagged as an error.
declare( strict_types = /*comment*/ 1);
exit(status: \true);
die( false );

declare(); // Checking that invalid (empty) declare statement is ignored.
exit ( 10.0 ); // Still error.
exit(1.5); // Still error.

declare(ticks=1); // Checking that non-strict_types declare statement is ignored.
die(1.5 + 1); // Still error.
die( null ); // Still error.

// A strict_type reset/explicit non-strict types should be recognized though and result in a warning (again).
declare(STRICT_TYPES=0, encoding='utf-8') {
    die( \false ); // Warning.
}

/*
 * PHP 8.4: exit can now be used as a fully qualified function call.
 */
\exit();
\die(10);
