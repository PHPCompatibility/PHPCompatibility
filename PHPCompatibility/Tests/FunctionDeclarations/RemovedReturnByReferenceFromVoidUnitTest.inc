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
    abstract function test(): Void;
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

$closure = function &(): VOID {};

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
    function &test(): Void;
}

trait BarTrait {
    function &test(): void {}
}

/*
 * Safeguard handling of PHP 7.4+ arrow functions.
 */
$arrow = fn(): void => $a; // OK.
$arrow = fn &() => $a; // OK.
$arrow = fn&() : int|bool => $a; // OK.
$arrow = fn &(): void => $a; // Error.

/*
 * Safeguard handling of PHP 8.1+ methods in enums.
 */
enum FooEnum {
    public function notReturnByRef(): void {} // OK.
    public function &noReturnType() {} // OK.
    public function &notVoidReturnType(): Foo&Bar {} // OK.
    public function &voidReturnType(): void {} // Error.
}
