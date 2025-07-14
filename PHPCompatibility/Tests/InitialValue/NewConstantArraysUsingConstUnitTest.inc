<?php

const ABC = ['a', 'b'];
const AB = array('a', 'b');

const ANIMALS = [
    'dog',
    'cat',
    'bird'
];

const MORE_ANIMALS = array(
    'dog',
    'cat',
    'bird'
);

class MyClass {
    const ANIMALS = [
        'dog',
        'cat',
        'bird'
    ];

    const MORE_ANIMALS = array( 'dog', 'cat', 'bird' );
}

/*
 * Minimal tests against false positives.
 */
const ANIMALS = 'array';

const DEF; // Not an assignment. Useless, but what the heck ;-)

// Multi-constant declaration.
const MULTI_A = 1,
    MULTI_B = array( 'a', 'b' ),
    MULTI_C = 'string',
    MULTI_D = ['a', 'b'],
    MULTI_E = true,
    MULTI_F = array(
        ['c', 'd'],
    );

// Constant array dereferencing.
const DEREF = OTHER['key'];
const DEREF = 'string'[2];

// Safeguard handling of PHP 8.3 typed constants. The sniff should not trigger on the array type keyword.
class TypedConstants {
    protected const array ARRAY_TYPE = // OK.
        [ 10, 20]; // Error.

    private const ?array NULLABLE_ARRAY_TYPE_NULL = null; // OK.
    private const ?array NULLABLE_ARRAY_TYPE_ARRAY = []; // Error.

    private final const array|false UNION_TYPE = false; // OK.

    public const (MyClass&\SomeInterface)|array DNF_TYPE = new MyClass; // OK.
    public const (MyClass&\SomeInterface)|array DNF_TYPE = array(1, 2, 3); // Error.
}
