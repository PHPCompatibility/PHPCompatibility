<?php

list() = $infoArray;
list(, ,) = $infoArray;
list(/*comment*/) = $infoArray;
list( /*comment*/ /*another comment*/ ) = $infoArray;
list($x, list(), $y) = $a;

/*
 * The below list assignments are all valid.
 */
list( $item, $anotherItem ) = $infoArray;
list($drink, , $power) = $infoArray;
list(, , $power) = $infoArray;
list($a[0], $a[1], $a[2]) = $infoArray;
list( ${$drink} ) = $infoArray;

// Invalid with short list syntax.
[] = $infoArray;
[, ,] = $infoArray;
[/*comment*/] = $infoArray;
[ /*comment*/ /*another comment*/ ] = $infoArray;
[$x, [], $y] = $a;

// Test handling of tokenizer issue in older PHPCS versions.
if (true) {}
[,[],] = $infoArray;

// Parse errors. Ignore.
list(,(),) = $infoArray;
[,(),] = $infoArray;

// Issue #1341, safeguard against a false positive.
foreach(["a"=>[]] as $v){}

// Don't trigger on unfinished code during live code review.
list(
