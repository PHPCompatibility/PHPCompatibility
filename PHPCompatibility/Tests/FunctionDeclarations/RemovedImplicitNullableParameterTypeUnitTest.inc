<?php

// These should not be a problem.
function testNoType($noType = null) {}
function testNoTypeNoDefault($noDefault) {}
function testScalarNoDefault(int $number) {}
function testNonNullDefault(int $number = 0) {}
function testScalarWithQuestion(
    ?string $word = null,
    ?int $number = null
) {}
function testUnionWithNull(
    string|null $word = null,
    int|float|null $number = null,
    Child|Adult|null $person = null
) {}
function testDNFWithNull(
    A|(B&C)|null $one = null,
    (A&B)|C|null $two = null
) {}

// These should all trigger a problem.
function testSimpleClass(T $thing = null) {}
function testSimpleScalar(int $number = null) {}
function testUnionScalar(
    int|float $number = null,
    int|bool $numberOrBool = null
) {}
function testUnionObject(Child|Adult $person = null) {}
function testIntersection(
    A&B $one = null,
    (A&B) $two = null
) {}
function testDNF(
    A|(B&C) $one = null,
    (A&B)|C $two = null
) {}
