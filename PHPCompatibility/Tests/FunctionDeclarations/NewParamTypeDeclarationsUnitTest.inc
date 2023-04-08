<?php

// Array type declaration - PHP 5.1+
function foo(array $a) {}
function foo( Array   $a ) {} // Test extra spacing.

// Callable type declaration - PHP 5.4+
function foo(callable $a) {}

// Scalar type declarations - PHP 7.0+
function foo(BOOL $a) {}
function foo(int $a) {}
function foo(float $a) {}
function foo(string $a) {}

// Iterable type declaration - PHP 7.1+
function foo(iterable $a) {}

// (Very likely) Invalid type declarations - will be treated as class/interface names.
function foo(boolean $a) {}
function foo(integer $a) {}

class MyOtherClass extends MyClass {
    function foo(parent $a) {} // Correction: Valid as of PHP 5.2+.
    function bar(static $a) {}
}

// Class/interface type declaration - PHP 5.0+
function foo(stdClass $a) {}
function foo(DateInterval ...$a) {} // Combined with splat, PHP 5.6+.

// Class/interface type declaration with self keyword - PHP 5.2+
class MyClass {
    function foo(self $a) {}
}

function foo(self $a) {} // Invalid - not within a class.

namespace test {
    class foo {
        function foo(self $a) {}
    }

    function foo(self $a) {} // Invalid - not within a class.
}

// Ok: No function parameters or no type hints.
function foo() {}
function foo( $a, $b ) {}

// Type hints in closures.
function (CallAble $a) {}
function(int $a) {}

// Deal with nullable type hints.
function foo(?callable $a) {}
function foo(?int $a) {}

// Object type declaration - PHP 7.2+
function foo(object $a) {}

// Use of "parent" outside class scope.
function foo(parent $a) {} // Invalid - not within a class.

namespace test {
    function foo(parent $a) {} // Invalid - not within a class.
}

class Bar {
    function baz() {
        $c = function (self $a) {}; // Ok.
        function foo(self $a) {} // Invalid - not within class scope.
        $arrow = fn(parent $a) => $a->prop; // Ok.
    }
}

// PHP 7.4 arrow functions.
$arrow = fn(int $a) => $a * 10;
$arrow = fn(\FQN\ClassName $b) => $b::method();
$arrow = fn(callable $a, string $b, mixed $c) => $a();
$arrow = fn(integer $a) => $a . 'test';
$arrow = fn($a) => $a * 10; // OK.

// Mixed type declaration - PHP 8.0+.
$closure = function (mixed $a) {};

// Invalid: Nullable mixed type declaration.
function NullableMixed(?mixed $a) {};

// PHP 8.0 union types.
function NOTunionTypeBitwiseOrInDefault($var = CONSTANT_A | CONSTANT_B) {}

function unionTypeSimple(int|float $number) {}
function unionTypeSimpleWithBitwiseOrInDefault(int|float $var = CONSTANT_A | CONSTANT_B) {}
function unionTypesTwoClasses(MyClassA|\Package\MyClassB $var) {}
function unionTypesAllBaseTypes(array|bool|callable|int|float|null|object|string $var) {}

// Intentional fatal error - mixing types which cannot be combined, but that's not the concern of the sniff.
class FooBar {
    public function unionTypesAllPseudoTypes(false|mixed|self|parent|iterable|Resource $var) {}
}

// Intentional fatal error - nullability is not allowed with union types, but that's not the concern of the sniff.
$closure = function (?int|float $number) {};

// Intentional fatal error in PHP 8.0/8.1 - null pseudotype is only allowed in union types.
function pseudoTypeNull(null $var = null) {}

// Intentional fatal error in PHP 8.0/8.1 - false pseudotype is only allowed in union types.
function pseudoTypeFalse(false $var = false) {}

// Intentional fatal error - false pseudotype is not allowed in combination with bool, but that's not the concern of the sniff.
function pseudoTypeFalseAndBool(bool|false $var = false) {}

// Intentional fatal error - object is not allowed in combination with class name, but that's not the concern of the sniff.
function objectAndClass(object|ClassName $var) {}

// Intentional fatal error - iterable pseudotype is not allowed in combination with array or Traversable, but that's not the concern of the sniff.
function pseudoTypeIterableAndArray(iterable|array|Traversable $var) {}

// Intentional fatal error - duplicate types are not allowed in union types, but that's not the concern of the sniff.
function duplicateTypeInUnion(int|string|INT $var) {}

// PHP 8.0 constructor property promotion. Only the "normal" parameters should be examined.
class ConstructorPropertyPromotionWithTypes {
    public function __construct(
        protected float|int $propertyIgnore1,
        private mixed $propertyIgnore2,
        public callable $propertyIgnore3,
        callable $normalParam1,
        float|int $normalParam2,
        mixed $normalParam3,
    ) {}
}

// PHP 8.1 intersection types.
function NOTintersectionTypeBitwiseAndInDefault($var = CONSTANT_A & CONSTANT_B) {}

function intersectionTypes(MyClassA&\Package\MyClassB $var) {}
class IntersectionTypes {
    public function setIterator(Traversable&\Countable $countableIterator): void {}
}

// Intentional fatal error - non-class/interface types are not allowed, but that's not the concern of the sniff.
function intersectionTypesIllegalTypes(int&string $var) {}

// Intentional fatal error - hierarchical types are not allowed, but that's not the concern of the sniff.
class HierachicalIntersection {
    function intersectionTypesIllegalTypes(self&\Fully\Qualified\SomeInterface $var) {}
    function intersectionTypesIllegalTypes(Qualified\SomeInterface&parent $var) {}
}

// Intentional fatal error - duplicate types are not allowed, but that's not the concern of the sniff.
function intersectionTypesIllegalTypes(A&B&A $var) {}

// PHP 8.2 true type.
function pseudoTypeTrue(true $var = true) {}

// PHP 8.1 types in enum methods.
enum Suit implements Colorful
{
    case Hearts;
    case Diamonds;

    public function color(string $scheme, ?int $foo = null): string {}
    public function shape(
        A|B $union,
        A&B $intersection,
    ): mixed {}
}
