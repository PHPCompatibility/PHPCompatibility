<?php

// OK: non-constants using array or object dereferencing
echo $a[0];
echo $a{0};
echo $a->length();

// OK: references to non-constants
class Bar extends Foo implements Baz, \ArrayAccess {}
interface Foo {}
namespace Foo {}
trait Foo {}
$foo = new Foo;
if ($foo instanceof Foo) {}
use A as C, B { B::foo insteadof A };
goto foo;
private Foo $foo;
public function addMatcher(MatcherInvocation $matcher): void {}

// PHP 5.5: constant array dereferencing.
echo FOO[0];
echo FOO{0};
echo \Foo\BAR[0];
echo \Foo::BAR[0];
echo FOO[0]['foo'];
echo \Foo\BAR['foo'][1];
echo \Foo::BAR[20]['bar'];

// PHP 7.4: constant object dereferencing.
echo FOO->length();
echo \Foo\BAR->length();
echo \Foo::BAR->length();
