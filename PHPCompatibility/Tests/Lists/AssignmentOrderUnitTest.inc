<?php

/*
 * Same behaviour cross-version.
 */
list($id, $name) = $data;

foreach ($data as list($id, $name)) {}

list($a, list($b, $c)) = array(1, array(2, 3));

list( , , , ) = $data; // Empty list, not our concern and no variables to examine anyway.

/*
 * PHP 7.0: list() no longer assigns variables in reverse order.
 */
list($a[], $a[], $a[]) = [1, 2, 3];
list($a[], $a[0], $a[]) = [1, 2, 3];
list($a, $b, $a) = [1, 2, 3];
[$a->propA, $a->propB, $a      ->     propA] = [1, 2, 3];

list($a, list($b, $a)) = array(1, array(2, 3));

list(list($a, $b), list($b, $a)) = array(array(10, 11), array(2, 3));

// Also detect this in short list syntax.
[$a[], $a[], $a[]] = [1, 2, 3];
[$a[], $a[0], $a [ ] ] = [1, 2, 3];
[$a, $b, $a] = [1, 2, 3];
[$a->propA, $a->propB, $a->propA] = [1, 2, 3];

[$a, [$b, $a]] = array(1, array(2, 3));

[[$a, $b], [$b, $a]] = array(array(10, 11), array(2, 3));

// Also detect this in keyed lists.
list('name' => $a, 'id' => $b, 'field' => $a) = ['name' => 1, 'id' => 2, 'field' => 3];
['name' => $a, 'id' => $b, 'field' => $a] = ['name' => 1, 'id' => 2, 'field' => 3];

// Don't get confused with variable keys.
list($foo => $a, $bar => $b, 'field' => $c) = ['name' => 1, 'id' => 2, 'field' => 3];
list($b => $a, $a => $b, 'field' => $c) = ['name' => 1, 'id' => 2, 'field' => 3];

// Don't get confused when some of the entries are empty.
list( , $a, , $b, , $a, ,) = array[1, 2, 3, 4, 5, 6, 7, 8];

// Test handling of specific tokenizer issue.
if (true) {}
[$a, [$b, $a]] = array(1, array(2, 3));

// Test handling of list vars with differing whitespace.
list( $a [ 'key' ], $b [ 'key' ], list($a['key'], $b['key'])) = $array;

// Safeguard handling of PHP 7.1+ keyed lists.
// Okay: duplicate variable is used as a key not as the variable assignment.
list("id" => $id, "name" => $name, $name => $value) = $data[0];
["id" => $id, "name" => $name, $id => $value] = $data[0];

// Error: duplicate variable is used for the variable assignment.
list("id" => $id, "name" => $name, $label => $id) = $data[0];
["id" => $id, "name" => $name, $label => $name] = $data[0];

// Safeguard handling of PHP 7.3+ lists with reference assignment.
list($a, &$b, &$a) = $array;
[$a, &$b, &$a] = $array;

// Don't trigger on unfinished code during live code review.
// This has to be the last test in the file!
list(
