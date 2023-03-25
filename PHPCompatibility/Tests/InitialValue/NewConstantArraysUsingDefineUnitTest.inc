<?php

define('ANIMALS', [
    'dog',
    'cat',
    'bird'
]);

DEFINE('ANIMALS', array(
    'dog',
    'cat',
    'bird'
));

define('ANIMALS', 'dog');

// Test correct function detection.
myClass::define('ANIMALS', 'dog');
$object->define('ANIMALS', 'dog');

class myClass {
    const define = true;
    function &define($name, $value = array()) {}
}

notDefine('ANIMALS', 'dog');

define('ANIMALS');

// Array within a function call.
define('WPDIRAUTH_LDAP_RETURN_KEYS',serialize(array('sn', 'givenname', 'mail')));
define('WPDIRAUTH_LDAP_RETURN_KEYS',serialize(['sn', 'givenname', 'mail']));

// Array dereferencing.
define('DEREF', OTHER['key']);
define('DEREF', 'string'[2]);

// PHP 8.0: calling define() using named parameters.
define(value: array('bird'), constant_name: 'ANIMALS' ); // Error.
define(value: 'not an array', constant_name: 'NOTANARRAY' ); // OK.

// Prevent false positives on PHP 8.0+ nullsafe method calls.
$obj?->define('ANIMALS', []);

// No false positive on namespace define.
MyOwn\define('name', array());

// No false positive on class called "define".
$obj = new Define('name', array());

// Prevent false negative with compound value.
define('NAME', functionReturningArray(array()) + array('sn', 'givenname', 'mail')); // Error.
define('NAME', functionReturningArray(array()) + functionReturningArray(array())); // OK (undetermined).

// Prevent false positive on closure/PHP 7.4 arrow function with PHP 8.1 "new in initializer".
define('CLOSURE_OBJECT_FROM_CLOSURE', function() { return array(1, 2, 3); });
define('CLOSURE_OBJECT_FROM_ARROW', fn() => array(1, 2, 3));
