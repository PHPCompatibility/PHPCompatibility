<?php

//namespace
namespace FooTest;

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

// do, while and endwhile
do {
} while (false);
while (false):
endwhile;

// if else and elseif
if (true) {
} elseif (false) {
} else {
}

// endif
if (true):
endif;

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

// global
global $a;

// goto
goto a;
a:

// interface and implements
interface FooInterface {}
class FooImplements implements FooInterface {}

// instanceof
if ($b instanceof FoobarClass) {}

// TODO: insteadof

// or
$c = 1 or 2;

// throw
if (false) {
    throw new Exception("foo");
}

// xor
$d = 1 xor 2;
