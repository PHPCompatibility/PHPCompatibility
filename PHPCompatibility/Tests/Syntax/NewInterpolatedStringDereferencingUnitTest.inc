<?php

// OK: strings that don't use interpolation or dereferencing.
$a = 'foo';
$a = "foo";
$a = "foo
bar";

// OK: strings that use interpolation, but not dereferencing.
$a = "$bar";
$a = "foo$bar";
$a = "foo
$bar";

// OK: strings that use dereferencing, but not interpolation.
$a = 'foo'[0];
$a = "foo"[0];
$a = 'foo'->baz();
$a = "foo"->baz();
$a = "foo
bar"->baz();

// PHP 8.0: strings that use interpolation and dereferencing.
$a = "foo$bar"[0];
$a = "foo$bar"->baz();
$a = "foo
$bar"->baz();
$a = "foo $lines
$bar
more foo"[10];
$a = "${foo->{"${'a'}"}}"[$c];
