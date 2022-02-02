<?php

/*
 * Allowed pre-PHP 8.0.
 */
function Foo7(
    $foo,
    $bar
) {
    return $foo + $bar;
}

$closure = function ($foo, $bar) use ($baz, $booboo) {
    return ($foo + $bar) * $baz / $booboo;
};

$arrow = fn($foo, $bar) => $foo + $bar;

class LotsOfParams7 {
    private function __construct(
        ?string $scheme,
        ?string $user,
        ?string $pass,
        ?string $host,
        ?int $port,
        string $path,
        ?string $query,
        ?string $fragment // <-- ARGH!
    ) {
        // Do something.
    }
}

/*
 * PHP 8.0 trailing commas in function declarations.
 */
function Foo8(
    $foo,
    $bar,
) {
    return $foo + $bar;
}

$closure = function ($foo, $bar,) {
    return $foo + $bar;
};

$arrow = fn($foo, $bar,) => $foo + $bar;

class LotsOfParams8 {
    private function __construct(
        ?string $scheme,
        ?string $user,
        ?string $pass,
        ?string $host,
        ?int $port,
        string $path,
        ?string $query,
        ?string $fragment, // Trailing comment.
    ) {
        // Do something.
    }
}

/*
 * PHP 8.0 trailing commas in closure use lists.
 */
$closure = function ($foo, $bar) use ($baz, $booboo,) {
    return ($foo + $bar) * $baz / $booboo;
};

$longArgs_longVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument,
) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3,
) {
   // body
};

/*
 * Still not allowed.
 */

// Free-standing comma.
$c = function(,) {}; // Parse error, but throw an error anyway.

$c = function($foo) use(,) {}; // Parse error, but throw an error anyway.

// Multiple trailing commas.
$a = fn($foo, $bar,,) => $bar; // Parse error, but throw an error anyway.

// Leading comma.
function Leading(, $foo, $bar) {} // Parse error, but not our concern.

// Live coding.
// Intentional parse error. This has to be the last test in the file.
function Unfinished( $foo, $bar,
