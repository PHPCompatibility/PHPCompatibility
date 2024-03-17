<?php

/*
 * OK on all versions.
 */
function requiredBeforeOptional($a, $b, $c = null, $d = true) {}
function requiredBeforeOptionalWithTypes(?int $a, string $b, callable $c = NULL, bool $d = /*comment*/ true) {}
function nullableTypedOptionalBeforeRequired(Foo $a = /* comment */ null, ?int $b = null, $c, $d) {}

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
function variadicIsOptionalByNatureWithExtraOptional($a, $b = NULL, ...$c) {}
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

// Test handling of constant expression in default value. This should throw a deprecation notice.
function constantExpressionInDefault( int $a = MY_CONST ? 10 : /*comment*/ NULL, $b) {}
function constantExpressionInDefaultNoRequireAfter( $a, ?int $b = MY_CONST ? /*comment*/ 10 : NULL, $c = 10) {} // This should be okay.

// Check handling of PHP 8.1 new in initializers when `null` is passed as a parameter.
// $a - $d should all be flagged for 8.0, the fact that new in initializers is not supported on PHP 8.0 is irrelevant and handled by another sniff.
function newInInitializers(
    OkayNoParens $a = new OkayNoParens,
    NullAsParamInInitializer $b = new NullAsParamInInitializer(null),
    string $c = 'text' . new ClassWithToStringMethod(null),
    Required $d,
    OkayOptionalIsLast $e = new OkayOptionalIsLast,
) {}

// Intentional parse error. This has to be the last test in the file.
$closure = function( $a = [], $b
