<?php

// namespace
namespace FooTest;
namespace\function_call();

// Nested namespace declarations.
namespace Foo\Bar\Baz;
namespace Foo\Bar\Baz {}

// Scoped global namespace.
namespace {
    // Do something.
}

// abstract
abstract class FoobarAbstract {
    abstract function foo() {}
}

// and
$a = 1 and 2;

// array
$exampleArray = array(1, 2);
function typehint(array $param) : array {}

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

// callable
function doThing(callable $callback) {
}

// switch, case, default and endswitch
switch ($a):
    case 1:
        break;
    default:
        break;
endswitch;

// try, catch and finally
try {
} catch (Exception $e) {
} finally {
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

// enum
enum Direction {
    case Up;
    case Down;
}

enum Suit: string implements Colorful {
    case Hearts = 'H';
    case Diamonds = 'D';
    case Clubs = 'C';
    case Spades = 'S';
}

// eval
eval( 'echo "Hello World!";' );

// exit
exit( 'Exit Message' );
exit(1);

// extends, final, private, protected, public, var
class FooExtends extends \Exception {
    private $fooPrivate;
    protected $fooProtected;
    public $fooPublic;
    var $fooVar;
    final public function __construct() {
    }
}

// fn
$arrow = fn($a) : int => rand(0,10) * $a;

// for and endfor
for ($i = 0; $i < 2; $i++):
endfor;

// function
function abcdef() {}
function &mySayHello() {}
$closure = function() {};

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
interface FooInterface  {
    public $prop;
    public function name() {}
}
class FooImplements implements FooInterface {}

// instanceof
if ($b instanceof FoobarClass) {}

// insteadof, use, as
class WithTraits {
    use A, B {
        B::smallTalk insteadof A;
        A::bigTalk insteadof B;
        B::bigTalk as talk;
    }
}

// isset
isset( $foo );

// list
list( $a, $b, $c ) = $array;
foreach ($array as list($key, $value)) {}
foreach ([[1, 2], [2, 3]] as list($a, $b)):
    // Do something.
endforeach;

// match
$val = match($foo) {
    'bar' => 10,
    default => 20,
};

// or
$c = 1 or 2;

// print
print "Hello world!";
print($var);

// readonly
class ContainsReadonlyProp {
    readonly $propA;
    public readonly $propB;
}
final readonly class MyReadonly {}

// require
require __DIR__ . '/path/to/file.php';
require( $file );

// require_once
require_once __DIR__ . '/path/to/file.php';
require_once( $file );

// return
return;
return $foo;

// static
class staticClass {
    public static $prop;

    static protected function staticFn() {
        static $varA, $varB;

        static::method_name();
    }
}

// throw
if (false) {
    throw new Exception("foo");
}

// trait
trait InjectMe {
    public $prop;
    public function name() {}
}

// unset
unset( $foo );

// xor
$d = 1 xor 2;

// yield
yield $item;
yield from $array;

// Magic constants
echo __CLASS__, __FUNCTION__, __METHOD__, __NAMESPACE__, __TRAIT_;
var_dump(__DIR__, __FILE__, __LINE__);

// Other reserved keywords.
$var = null;
$true = TRUE;
$false = false;

function softReservedTypeHints(
    bool $bool,
    int $int,
    float $float,
    string $string,
    iterable $iterable,
    object $object,
    mixed $mixed
) : void
{}

function softReservedReturnTypeNever() : never {}

// Other reserved keywords are ok to use as a function or constant name.
function null() {}
function true() {}
use function Some\BooleanFalse as false;
function bool() {}
use My\Abc\{
    function IntegerMath as int,
};
const float = 1;
const string = 1;
use const Some\FileObject as resource;
const object = 1;
use My\Abc\{
    const MixerLogic as mixed,
};
const numeric = 1;
class HasTrait {
    use MyTrait {
        getArray as private iterable;
    }
}
const VOID = null;
const MIXED = 'mixed';
function never() {}
const ENUM = MyEnum::CaseName;
function enum() {}

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
$a = new class {};
$a = new class extends FooBar implements SomeInterface {};

/**
 * Issue #124/#126: Valid use statements with function or const (PHP 5.6+).
 */
use function MyFunctionName;
use function MyFunctionName as func;
use const MyCONSTANT;

// Closure use should be ignored.
$closure = function () use ($a) {};

// Test call to define() using named parameters.
define(value: 'class', constant_name: 'BAR');

// Test readonly as a function name. This is a (temporary) exception.
function readonly() {}

class ReadonlyFoo {
    public function readonly() {}
}

// Issue #1434: ensure there are no false positives when `enum` is tokenized a T_STRING and handled via the special casing.
use My\Enum\Switch;
use function enum;
use const ENUM;

class EnumSpecialCasingTest
{
    use Enum {
        enum as public;
        class as final;
    }

    public function methodName()
    {
        foreach (self::ENUM as $key => $value) {}

        self::ENUM or \true;
        self::ENUM and \true;
        self::ENUM xor \false;
    }
}

// Issue #1474: more protection against `enum` false positives.
use Elao\Enum\Bridge\Symfony\Validator\Constraint\Enum as AssertEnum;

// Ensure there are no false positives when a keyword which became reserved in PHP >= 7.0 is used as a method name.
class ReservedKeywordsAsMethodNames {
    public function fn() {}
    public function match() {}
}

// Ensure there are no false positives when a keyword which became reserved in PHP >= 7.0 is used as a class constant name.
class ReservedKeywordsAsMethodNames {
    protected const FN = 'something';
    private const MATCH = 'something';
    public const READONLY = 'something';
}
