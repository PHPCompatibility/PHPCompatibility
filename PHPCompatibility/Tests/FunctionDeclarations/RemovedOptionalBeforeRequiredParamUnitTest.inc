<?php

/*
 * OK on all versions.
 */
function requiredBeforeOptional($a, $b, $c = null, $d = true) {}
function requiredBeforeOptionalWithTypes(?int $a, string $b, callable $c = NULL, bool $d = /*comment*/ true) {}


/*
 * Deprecated in PHP 8.0.
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

/*
 * Deprecated in PHP 8.0, but only flagged as of PHP 8.1.
 */
function nonNullableTypedWithNullDefaultValueBeforeRequired(T1 $a, T2 $b = null, T3 $c) {} // This is fine until PHP 8.4.
$nullableTypedWithNullDefaultValueBeforeOptional = function (T1 $a, ?T2 $b = null, T3 $c = null) {}; // This is fine, even on PHP 8.4.

// Deprecated as of PHP 8.0, but only emits a deprecation notice in PHP itself as of PHP 8.1.
function nullableTypedOptionalBeforeRequired(Okay $a, ?NotOkay $b = /* comment */ null, Required $c) {}

// PHP 8.1 deprecation notice also applies to constructor property promotion.
class ConstructorPropertyPromotionWithNullableType {
    public function __construct(public ?NotOkay $prop = null, $b) {}
}

/*
 * Deprecated in PHP 8.0, but only flagged as of PHP 8.3.
 */
function nullLastUnionTypedWithNullDefaultValueBeforeOptional(T1 $a, T2|null $b = null, T3 $c = null) {} // This is fine, even on PHP 8.4.
$nullFirstUnionTypedWithNullDefaultValueBeforeOptional = fn(T1 $a, null|T2 $b = null, T3 $c = null) => dosomething(); // This is fine, even on PHP 8.4.
$nonNullableIntersectionTypeWithNullDefaultBeforeRequired = function ( Foo&Bar $c = null, $d) {}; // This is fine until PHP 8.4.

// Deprecated as of PHP 8.0, but only emits a deprecation notice in PHP itself as of PHP 8.3.
function nullLastUnionTypedWithNullDefaultValueBeforeRequired(Okay $a, NotOkay|null $b = null, Required $c) {}
function nullFirstUnionTypedWithNullDefaultValueBeforeRequired(Okay $a, null|NotOkay $b = null, Required $c) {}
$nullInDNFTypeWithNullDefaultValueBeforeRequired = function ( (Foo&NotOkay)|null $e = null, $f) {};
function nullStandAloneTypedWithNullDefaultValueBeforeRequired(Okay $a, null $b = null, Required $c) {}
$nullStandAloneTypedWithNullDefaultValueBeforeRequiredWithComment = fn (/*comment*/ null $b = null, Required $c) => $c;
function mixedTypedWithNullDefaultValueBeforeRequired(Okay $a, mixed /*comment*/ $b = null, Required $c) {}

// PHP 8.3 deprecation notice also applies to constructor property promotion.
class ConstructorPropertyPromotionWithUnionTypedWithNull {
    public function __construct(public null|NotOkay $prop = null, $b) {}
}

/*
 * Deprecated in PHP 8.4.
 */
// These should still be fine, even on PHP 8.4.
function untypedWithDefaultValue($a = null, $b = true) {} // = mixed type.
$explicitlyNullableWithDefaultValue = function (?T $var = null) {};
function explicitlyNullableUnionWithDefaultValue(T|null $var = null, int $var2 = 10) {}
$explicitNullableTypeNull = fn (null $var = null): Type => new Type;
 // Will trigger implicitly nullable deprecation for $call, but that's not the concern of this sniff.
function explicitNullableTypeMixed(mixed $var = null, callable $call = null) {}

// Deprecated as of PHP 8.4.
function typedWithNullDefaultBeforeRequired(
    Foo $a = /* comment */ null,
    int $b = null,
    $c,
    $d,
) {} // Deprecated x2.
function implicitNullableTypeClass(T $var = null, $c) {}
$implicitNullableTypeString = function (string $var = null, $c) {};
function implicitNullableTypeUnion(string|int $var = null, $c) {}

// Combination: contains parameters for all deprecations.
function combinationOfAllDeprecations(
    Okay $a,
    ShouldBeNullable $b = null, // PHP 8.4 deprecation.
    NotOkay|null $c = null, // PHP 8.3 deprecation.
    ?NotOkay $d = null, // PHP 8.1 deprecation.
    int $e = 10, // PHP 8.0 deprecation.
    Required $f,
) {}

// Intentional parse error. This has to be the last test in the file.
$closure = function( $a = [], $b
