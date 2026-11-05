<?php
/**
 * These should be ok.
 */
$str = 'This is fake array: []';
$arr = array();
$arr[] = 'add a value';

/**
 * These should be flagged.
 */
$arr = [];
$arr = [1,2,3];
$arr[] = ['A','B'];

$arr[] = [
    'A',
    'B'
];

// Direct array dereferencing (tokenizer bug in older PHPCS versions).
echo [ 1,2,3 ][0]; // Error x1 (not 2).

// Short list, not short array.
[$a, $b] = $array;

// Short arrays inside attributes are ignored before PHP 8.0.
#[Example([1, [2, 3]])]
class SingleLineShortArrayAttribute {}

#[Example(
    [1,
        [2, 3],
    ]
)]
class MultiLineShortArrayAttribute {}

// Ordinary code must still be checked.
$ordinary = [1, 2];
