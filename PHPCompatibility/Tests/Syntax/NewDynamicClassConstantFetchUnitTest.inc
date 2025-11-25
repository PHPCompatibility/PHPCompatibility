<?php

// Not sniff's target, using similar tokens or syntax
$$foo; // fetch variable using dynamic name
$foo->$bar; // fetch object property using dynamic name
Foo::${$bar}; // explicit class name, fetch static property using dynamic name
$foo->{$bar}(); // call object method using dynamic name
Foo::{$bar}(); // explicit class name, call static method using dynamic name
$foo::$bar; // fetch static property using dynamic name from object’s class
$foo::bar(); // call static method on object’s class using explicit method name
$array{'index'}; // curly brace array access, string index
$array{10}; // curly brace array access, numeric index
$className::class; // fetch static constant
Foo::class; // explicit class name, fetch static constant

// Sniff's target: invalid syntax on <=8.2
Foo::{$bar}->prop;
Foo::{$bar}->method();
Foo::{$bar}?->prop;
Foo::{$bar}?->method();
Foo::{$bar}::CONSTANT_NAME;
Foo::{$bar->get('index')}; // complex expression as constant name
self::{$bar}; // class is self
static::{$array['index']}; // class is static
static::{Foo::class}; // inner expression double colon `::` shouldn't interfere
Foo::
    /* multiline, comment between relevant tokens */
    {$bar};
