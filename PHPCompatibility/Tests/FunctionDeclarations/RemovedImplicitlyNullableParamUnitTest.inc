<?php

/*
 * OK on all versions.
 */
function noDefaultValue(Type $var) {}
function noDefaultValueWithNullableType(?Type $var) {}

function noNullDefaultValue(int $var = 10) {}
function noNullDefaultValueButContainsNull(Type $var = new Type(null)) {}

function nullDefaultNoType($var = null) {}
function nullDefaultWithNullableType(?T $var = null) {}
function nullDefaultWithNullInUnionType(Countable|null $var = null) {}
function nullDefaultWithNullInDNFType((Foo&Bar)|null $var = /*comment*/ null) {}
function nullDefaultWithNullType(null $var = null /*comment*/ ) {}
function nullDefaultWithMixedType(mixed $var = null) {}
function nullDefaultWithMixedTypeAndComments( /*comment*/ mixed /*comment*/ $var = null) {}

// Fatal error on all versions.
// The old exception did not apply to properties. A non-nullable typed property with a non-null default value
// was always a fatal error, so these should be ignored by the sniff as this is not part of the new deprecation.
class ConstructorPropertyPromotion {
    public function __construct(
        public Foo|Bar $propA = null,
        protected array $propB = null,
        int $param = 10,
    ) {}
}

// Test handling of constant expression which includes null in default value.
// These were never recognized by PHP as implicitly nullable and would throw a fatal
// TypeError when the function was called, so this is outside the scope of the new deprecation.
// Issue about this in PHP Core: https://github.com/php/php-src/issues/13752
function constantExpressionInDefault( int $a = MY_CONST ? 10 : NULL) {}
function constantExpressionWithNewInDefault(Type $var = (MY_CONST ? new Type() : null)) {}


/*
 * Deprecated in PHP 8.4.
 */
function nullDefaultWithNonNullableType(Type $var = /*comment*/ null) {}

$closure = function (
    callable $callable = null,
    int $scalarType = null /*comment*/,
    int|float $scalarUnionType = null,
    Child|Adult $objectUnionType = /*comment*/ null,
    Foo & /*comment*/ Bar $intersectionType = null,
    (A&B)|string $dnfType = null,
    $requiredParam,
) {};

$arrow = fn(iterable $i = null): bool => doSomething($i);

// Intentional parse error. This has to be the last test in the file.
$closure = function( Type $a = null, $b
