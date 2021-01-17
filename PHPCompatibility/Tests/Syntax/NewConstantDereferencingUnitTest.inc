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

// PHP 5.5: constant array dereferencing
echo FOO[0];
echo FOO{0};

// PHP 7.4: constant object dereferencing
echo FOO->length();
