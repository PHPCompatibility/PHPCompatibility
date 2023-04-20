<?php

/*
 * Valid cross-version.
 */
list($id1, $name1) = $data[0];

foreach ($data as list($id, $name)) {}

list($a, list($b, $c)) = array(1, array(2, 3));

/*
 * PHP 7.1: support for keys in list().
 */
list(1 => $oneBit, 2 => $twoBit, 3 => $threeBit) = $powersOfTwo;
list("id" => $id1, "name" => $name1) = $data[0];
["id" => $id1, "name" => $name1] = $data[0];
[$foo => $bar] = $bar;
list(7 => $seven, "elePHPant" => $elePHPant) = $contrivedMixedKeyTypesExample;
list((string)$a => $store["B"], (string)$c => $store["D"]) = $e->getIndexable();

foreach ($data as list("id" => $id, "name" => $name)) {}
foreach ($data as ['id' => $id, 'name' => $name]) {}

// Test multi-line handling.
// Test with trailing comma in list(). Turns out this has been allowed since forever.
        list(
            "name" => $this->name,
            "colour" => $this->colour,
            "age" => $this->age,
            "cuteness" => $this->cuteness,
        ) = $attributes;

// Test detecting nested keyed lists and throwing the error at the correct line.
list(
	list("x" => $x1, "y" => $y1),
	list("x" => $x2, "y" => $y2)
) = $points;

[
	["x" => $x1, "y" => $y1],
	["x" => $x2, "y" => $y2],
] = $points;

list(
	'a' => 
		list($x1, $y1),
	'b' =>
		list($x2, $y2),
) = $points;

// Test handling of tokenizer issue in older PHPCS versions.
if (true) {}
["id" => $id1, "name" => &$name1] = $data;

/*
 * Invalid syntaxes.
 */

// Mixed keyed and unkeyed.
// Parse error, but not our concern, throw an error anyway for the key found.
list($unkeyed, "key" => $keyed) = $array;

// Empty elements are not allowed where keys are specified.
// Parse error, but not our concern, throw an error anyway for the key found.
list(,,,, "key" => $keyed) = $array;

// Safeguard that the sniff doesn't trigger on short arrays.
$a = ["x" => $x1, "y" => $y1];

// Safeguard handling of PHP 7.3+ lists with reference assignment.
list("id" => &$id1, "name" => $name1) = $data[0];
[$id1, & /*comment*/ $name1] = $data[0];

// Don't trigger on unfinished code during live code review.
// This has to be the last test in the file!
list(
