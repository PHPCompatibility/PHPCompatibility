<?php

/*
 * Valid prior to PHP 7.3.
 */
list($id1, $name1) = $data[0];
[$id1, $name1] = $data[0];

foreach ($data as list($id, $name)) {}
foreach ($data as [$id, $name]) {}


/*
 * PHP 7.3: list() reference assignments.
 */
list($a, &$b) = $array;
[$a, &$b] = $array;

list(
    &$a,
    $b,
    ,
    list(
        &$c,
        $d
    )
) = $array;

[
    &$a,
    $b,
    ,
    [&$c, &$d]
] = $array;

foreach ($array as list(&$a, &$b)) {}
foreach ($array as [&$a, $b]) {}

// Test handling of tokenizer issue in older PHPCS versions.
if (true) {}
[$id1, &$name1] = $data;
