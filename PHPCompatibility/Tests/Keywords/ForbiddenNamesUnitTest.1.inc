<?php

//namespace
namespace FooTest;

// Nested namespace declarations.
namespace Foo\Bar\Baz;
namespace Foo\Bar\Baz {}

// abstract
abstract class FoobarAbstract { }

// and
$a = 1 and 2;

// array
$exampleArray = array(1, 2);

// foreach, endforeach, use and as
foreach (array() as $item):
endforeach;
use \Exception as SomeException;

// break and continue
foreach (array(1,2,3) as $number) {
    if ($number == 1) {
        continue;
    }

    break;
}

//callable
function doThing(callable $callback) {
}

// switch, case, default and endswitch
switch ($a):
    case 1:
        break;
    default:
        break;
endswitch;

// try and catch
// TODO: add finally (PHP 5.5)
try {
} catch (Exception $e) {
}

// class
class FoobarClass {}

// new and clone
$a = new FoobarClass();
$b = clone $a;

// const
const ELEPHANT = "elephant";

// declare
declare(ticks=1);
declare(ticks=1):
enddeclare;

// die
die();

// do, while and endwhile
do {
} while (false);
while (false):
endwhile;

// echo
echo "Hello World!";

$str = "Hello World!";
echo $str;

// if else and elseif
if (true) {
} elseif (false) {
} else {
}

// endif
if (true):
endif;

// empty
empty($foo);

// eval
eval( 'echo "Hello World!";' );

// exit
exit( 'Exit Message' );
exit(1);

// extends, final, private, protected, public
class FooExtends extends \Exception {
    private $fooPrivate;
    protected $fooProtected;
    public $fooPublic;
    var $fooVar;
    final public function __construct() {
    }
}

// for and endfor
for ($i = 0; $i < 2; $i++):
endfor;

// function
function abcdef() {}
function &mySayHello() {}

// global
global $a;

// goto
goto a;
a:

// include
include __DIR__ . '/path/to/file.php';
include( $file );

// include_once
include_once __DIR__ . '/path/to/file.php';
include_once( $file );

// interface and implements
interface FooInterface {}
class FooImplements implements FooInterface {}

// instanceof
if ($b instanceof FoobarClass) {}

// TODO: insteadof

// isset
isset( $foo );

// list
list( $a, $b, $c ) = $array;
foreach ($array as list($key, $value)) {}
foreach ([[1, 2], [2, 3]] as list($a, $b)):
    // Do something.
endforeach;

// or
$c = 1 or 2;

// print
print "Hello world!";

// require
require __DIR__ . '/path/to/file.php';
require( $file );

// require_once
require_once __DIR__ . '/path/to/file.php';
require_once( $file );

// return
return;
return $foo;

// throw
if (false) {
    throw new Exception("foo");
}

// unset
unset( $foo );

// xor
$d = 1 xor 2;

// yield
yield $item;

// Issue #117: Traits: alias method with changed visibility.
class MyClass1 {
    use HelloWorld { sayHello as protected; }
}
class MyClass2 {
    use HelloWorld {
        sayHello as private;
    }
}
class MyClass3 {
    use HelloWorld { sayHello as public; }
}

class MyClass1 {
    use HelloWorld {
        sayHello as protected myProtectedHello;
    }
}
class MyClass2 {
    use HelloWorld { sayHello as private myPrivateHello; }
}
class MyClass3 {
    use HelloWorld { sayHello as public myPublicHello; }
}

class MyClass3 {
    use HelloWorld { sayHello as final; }
}

class MyClass4 {
    use HelloWorld { sayHello as final myFinalHello; }
}

// Anonymous classes.
$a = new class {}

/**
 * Issue #124/#126: Valid use statements with function or const (PHP 5.6+).
 */
use function MyFunctionName;
use function MyFunctionName as func;
use const MyCONSTANT;
