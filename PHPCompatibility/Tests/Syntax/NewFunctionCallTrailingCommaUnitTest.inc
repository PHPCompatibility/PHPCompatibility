<?php

/*
 * Allowed pre-PHP 7.3.
 */
isset($foo, $bar, $baz);
unset($foo, $bar, $baz);
myFunction($foo, $bar);
$myClosure($foo, $bar);

/*
 * PHP 7.3 trailing commas in function calls + isset + unset.
 */
// Isset & unset.
unset($foo, $bar,);
var_dump(isset($foo, $bar,));

unset(
    $foo,
    $bar,
    $baz,
);

// Function calls, including calls to methods and closures.
echo $twig->render(
    'index.html',
    compact('title', 'body', 'comments',), // x2.
);

$newArray = \array_merge(
    $arrayOne,
    $arrayTwo,
    ['foo', 'bar'],
);

var_dump($whatIsInThere, $probablyABugInThisOne, $oneMoreToCheck,);

$text = SomeNameSpace\PartTwo\PartThree\functionName($en, 'comma', 'Jane',);

$foo = new Foo( 'constructor', 'bar', );

$foo->bar(
  'method',
  'bar',
);

$foo( 'invoke','bar' , );

MyNamespace\Foo::bar('method','bar',);

$bar = function(...$args) {};
$bar('arg1', 'arg2',);

/*
 * Still not allowed.
 */
// Trailing comma in function declaration. Update: Allowed since PHP 8.0, but not the concern of this sniff.
function bar($a, $b,) {}
$closure = function ($a, $b,) {}

// Free-standing comma.
foo(,); // Parse error, but throw an error anyway.

// Multiple trailing commas.
foo('function', 'bar',,); // Parse error, but throw an error anyway.

// Leading comma.
foo(, 'function', 'bar'); // Parse error, but not our concern.

// List with trailing comma.
list($drink, $color, $power, ) = $info; // Was already allowed.

// Safeguard that closure use statements with trailing commas are ignored.
// This is allowed since PHP 8.0, but not the concern of this sniff.
$closure = function () use( $a, $b, ) {};
