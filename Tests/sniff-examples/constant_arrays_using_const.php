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

const ABC; // Not an assignment. Useless, but what the heck ;-)
