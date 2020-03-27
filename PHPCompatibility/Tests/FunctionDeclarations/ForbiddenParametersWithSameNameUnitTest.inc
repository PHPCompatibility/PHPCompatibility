<?php

function foo($a, $b, $unused, $unused) { }

function foobaz() {} // No parameters = no error.

function ($a, $b, $unused, $unused) {}; // Anonymous function with params of same name.

function varsAreCaseSensitive( $a, $A ) {}

$arrow = fn ($a, $b, $a) => $a * $b; // PHP 7.4 arrow function.

interface Foo {
    public function bar( $a, $a );
}

abstract class Foo {
    abstract public function bar( $a, $a );
}

// Don't throw errors during live code review.
function foobar($a,$a
