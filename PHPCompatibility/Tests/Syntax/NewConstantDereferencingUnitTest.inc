<?php

// OK: non-constants using array or object dereferencing
echo $a[0];
echo $a{0};
echo $a->length();

// OK: references to non-constants
class Foo { }
echo $foo::$bar[0];
class Bar extends Foo { }
class Bar implements Foo { }
interface Foo { }
namespace Foo { }
echo $foo->bar[0];
trait Foo { }
use Foo as Bar;
$foo = new Foo;
function foo() { }
if ($foo instanceof Foo) { }
use A, B { B::foo insteadof A };
goto foo;
private Foo $foo;
protected Foo $foo;
public Foo $foo;

// PHP 5.5: constant array dereferencing
echo FOO[0];
echo FOO{0};
echo \Foo\BAR[0];
echo \Foo::BAR[0];

// PHP 7.4: constant object dereferencing
echo FOO->length();
echo \Foo\BAR->length();
echo \Foo::BAR->length();
