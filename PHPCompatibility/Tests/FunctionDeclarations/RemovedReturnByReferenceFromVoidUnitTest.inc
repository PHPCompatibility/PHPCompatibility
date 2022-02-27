<?php

/*
 * Valid cross-version.
 */

// Not returning by reference.
function noReference() : void {}

$closure = function(): void {};

class Foo {
    function test(): void {}
}

$anon = new class() {
    function test(): void {}
};

abstract class AbstractFoo {
    abstract function test(): void;
}

interface FooInterface {
    function test(): void;
}

trait FooTrait {
    function test(): void {}
}

// No return type.
function &noReturnType() {}

$closure = function &() {};

class FooNoReturn {
    function &test() {}
}

// Different return type.
function &differentReturnType(): array {}

$closure = function &() : string {};

class FooDifferentReturn {
    function &test(): int {}
}


/*
 * PHP 8.1: Returning by reference from a void function is deprecated.
 */
function &referenceWithVoidReturnType() : void {}

$closure = function &(): void {};

class Bar {
    function &test(): void {}
}

$anon = new class() {
    function &test(): void {}
};

abstract class AbstractBar {
    abstract function &test(): void;
}

interface BarInterface {
    function &test(): void;
}

trait BarTrait {
    function &test(): void {}
}
