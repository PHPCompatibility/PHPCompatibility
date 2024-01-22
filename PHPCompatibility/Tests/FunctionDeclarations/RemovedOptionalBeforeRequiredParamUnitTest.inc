<?php

/*
 * OK on all versions.
 */
function requiredBeforeOptional($a, $b, $c = null, $d = true) {}
function requiredBeforeOptionalWithTypes(?int $a, string $b, callable $c = null, bool $d = /*comment*/ true) {}
function requiredTypedWithNullDefaultBeforeRequired(Foo $a = /* comment */ null, int $b = null, $c, $d) {}

/*
 * Deprecated in PHP 8.
 */
function optionalBeforeRequired($a = [], $b, $c) {}
function nonNullTypedOptionalBeforeRequired(int $a = 1, bool $b) {}

$closure = function ($a = 10 * DAY_IN_SECONDS, $b) {};
$arrow = fn(?bool $a = true, ?bool $b): string => $a ? (string) $b : '';

// Parse error, nothing in default, not our concern. Throw error anyway.
$closure = function ($a = /*comment*/, $b) {};

// Prevent false positives on variadic parameters.
function variadicIsOptionalByNature($a, int ...$b) {}
function variadicIsOptionalByNatureWithExtraOptional($a, $b = null, ...$c) {}
// Intentional parse error. This has always been an error though, so ignore for this sniff.
function variadicBeforeRequiredWasAlwaysAnError(...$a, $b) {}

// Rule also applies to constructor property promotion.
class ConstructorPropertyPromotion {
    public function __construct(
        public $propA = 10,
        protected $propB,
    ) {}
}

class MixedWithOptionalProperty {
    public function __construct(
        public $propA = false,
        $normalParam
    ) {}
}

class MixedWithOptionalParam {
    public function __construct(
        public $propA,
        $normalParam = 10, // OK.
    ) {}
}

function nonNullableTypedWithNullDefaultValueBeforeRequired(T1 $a, T2 $b = null, T3 $c) {} // This is fine.
function nullableTypedWithNullDefaultValueBeforeOptional(T1 $a, ?T2 $b = null, T3 $c = null) {} // This is fine.
// Deprecated as of PHP 8.0, but only emits a deprecation notice in PHP itself as of PHP 8.1.
function nullableTypedOptionalBeforeRequired(T1 $a, ?T2 $b = /* comment */ null, T3 $c) {}

function nullLastUnionTypedWithNullDefaultValueBeforeOptional(T1 $a, T2|null $b = null, T3 $c = null) {} // This is fine.
function nullFirstUnionTypedWithNullDefaultValueBeforeOptional(T1 $a, null|T2 $b = null, T3 $c = null) {} // This is fine.
// Deprecated as of PHP 8.0, but only emits a deprecation notice in PHP itself as of PHP 8.3.
function nullLastUnionTypedWithNullDefaultValueBeforeRequired(T1 $a, T2|null $b = null, T3 $c) {}
function nullFirstUnionTypedWithNullDefaultValueBeforeRequired(T1 $a, null|T2 $b = null, T3 $c) {}

// Intentional parse error. This has to be the last test in the file.
$closure = function( $a = [], $b
