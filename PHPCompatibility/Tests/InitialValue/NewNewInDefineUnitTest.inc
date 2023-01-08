<?php

/*
 * Valid cross-version.
 */
define('NO_NEW', 10);
define('NESTED_NEW', mine(new Foo)); // Was already allowed as long as the return value was not an object.


/*
 * Not our targets.
 */
notDefine('NOT_DEFINE', new \Foo());

define($name); // Missing required param, but not our concern.

Some\Package\define('NOT_GLOBAL_FUNCTION', new Foo);
$obj?->define('NOT_GLOBAL_FUNCTION', new Foo);
ClassName::define('NOT_GLOBAL_FUNCTION', new Foo);

class myClass {
    const define = new Foo;
    function &define($a, $b = new Foo) {}
}


/*
 * Undetermined.
 */
define('VARIABLE_VALUE', $object);
define('CONST_VALUE', Foo::CONST_NAME);
define('PROP_VALUE', Foo::$prop);


/*
 * PHP 8.1 new in define.
 */
define('PASSING_NEW_OBJECT', new Foo);
define('PASSING_NEW_WITH_PARAMS', new Foo($var));
define('PASSING_NEW_WITH_NESTED_NEW', new Foo(new Bar())); // Will only throw 1 error.
define(value: new Foo(), constant_name: 'NAMED_PARAMS', );

// Handle nesting in arrays correctly. Prior to PHP 8.1, objects were not allowed in array values, now they are.
define('OBJECT_IN_LONG_ARRAY', array(new Foo));
define('OBJECT_IN_NESTED_LONG_ARRAY', array(array(new Foo)));
define('OBJECT_IN_SHORT_ARRAY', [new Foo]);

// Prevent false negatives when there is an outer array wrapper.
$array = array(
    function ($value) {
        define($value, array(new Foo));
    },
);
