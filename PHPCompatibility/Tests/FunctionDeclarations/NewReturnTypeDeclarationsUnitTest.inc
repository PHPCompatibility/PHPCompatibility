<?php

// PHP 7.0+
function fooBool($a): bool {}
function fooInt($a): INT {}
function fooFloat($a): float {}
function fooString($a): ?string {}
function fooArray($a): array {}
function fooCallable($a): callable {}
function fooSelf($a): self {}
function fooParent($a): parent {}
function fooBaz($a): Baz {}
function fooGNSBaz($a): \Baz {}
function fooNSBaz($a): myNamespace\Baz {}
function fooNSBaz2($a): \myNamespace\Baz {}

// PHP 7.1+
function fooIterable($a): iterable {}
function fooVoid($a): void {}

// Anonymous function.
function($a): Callable {}

// OK: no return type hint.
function fooNone($a) {}
function ($a) {}

// PHP 7.2+
function fooObject($a): ObJect {}

function fooInterspersedWithComments($a) :
    // Comment.
    ?
    // phpcs:ignore Standard.Category.Sniff -- ignore something about a return type declaration.
    \myNamespace\
    // Comment.
    Baz
{
}

// PHP 7.4 arrow functions.
$arrow = fn($a) => $a * 10;
$arrow = fn($a) : int => $a * 10;

// PHP 8.0+
class Foo {
    public function fooStatic($a): static {
        $closure = function ($a): ?static {};
    }
}

// Mixed type declaration - PHP 8.0+.
function fooMixed($a): mixed {}

// Invalid: Nullable mixed type declaration.
$arrow = fn($a) : ?mixed => $a * 10;

// PHP 8.0 union types.
function unionTypeSimple($number) : int|float {}
function unionTypesTwoClasses($var): MyClassA|\Package\MyClassB {}
function unionTypesAllBaseTypes() : array|bool|callable|int|float|null|Object|string {}

// This is fine.
function unionTypesAllPseudoTypes($var) : false|self|parent|static|iterable|Resource {}

// Intentional fatal error - nullability is not allowed with union types, but that's not the concern of the sniff.
$closure = function () use($a) :?int|float {};

// Intentional fatal error in PHP 8.0/8.1 - null pseudotype is only allowed in union types.
function pseudoTypeNull(): null {}

// Intentional fatal error in PHP 8.0/8.1 - false pseudotype is only allowed in union types.
function pseudoTypeFalse(): false {}

// Intentional fatal error - false pseudotype is not allowed in combination with bool, but that's not the concern of the sniff.
function pseudoTypeFalseAndBool(): bool|false {}

// Intentional fatal error - object is not allowed in combination with class name, but that's not the concern of the sniff.
function objectAndClass(): object|ClassName {}

// Intentional fatal error - iterable pseudotype is not allowed in combination with array or Traversable, but that's not the concern of the sniff.
interface FooBar {
    public function pseudoTypeIterableAndArray(): iterable|array|Traversable;
}

// Intentional fatal error - duplicate types are not allowed in union types, but that's not the concern of the sniff.
function duplicateTypeInUnion(): int|string|INT {}

// PHP 8.1 noreturn type
function subTypeForAlwaysExitOrThrow(): never {
    throw new Exception();
}

$closure = function() : Never {
    redirect();
    exit;
};

// Invalid: nullable or union with void.
function voidIsStandAloneA($a): ?void {}
function voidIsStandAloneB($a): int|void {}

// Invalid: nullable or union with mixed.
function mixedIsStandAloneB($a): int|MIXED {}

// Invalid: nullable or union with never.
function neverIsStandAloneA($a): ?never {}
function neverIsStandAloneB($a): int|never {}


// PHP 8.1 intersection types.
function intersectionTypes($var):MyClassA&\Package\MyClassB {}
class IntersectionTypes {
    public function setIterator($countableIterator): Traversable&\Countable {}
}

// Intentional fatal error - non-class/interface types are not allowed, but that's not the concern of the sniff.
function intersectionTypesIllegalTypes($var):int&string {}

// Intentional fatal error - hierarchical types are not allowed, but that's not the concern of the sniff.
class HierachicalIntersection {
    function intersectionTypesIllegalTypes($var) : self&\Fully\Qualified\SomeInterface {}
    function intersectionTypesIllegalTypes($var): Qualified\SomeInterface&parent {}
    function intersectionTypesIllegalTypes($var): static&SomeInterface {}
}

// Intentional fatal error - duplicate types are not allowed, but that's not the concern of the sniff.
function intersectionTypesIllegalTypes(): A&B&A {}

// PHP 8.2 true type.
function pseudoTypeTrue(): true {}

// PHP 8.1 types in enum methods.
enum Suit implements Colorful
{
    public function color(): ?string {}
    public function shape(): mixed {}
    public function union(): A|B {}
}

// PHP 8.2 DNF types.
function DNFTypes($var): (Foo&Bar)|null {}
$closure = function($a) : (A&B)|(C&D) {};

// Intentional fatal error - &/| reversed, but that's not the concern of the sniff.
$arrow = fn($a):B&(D|W)|int => $a;

// Intentional fatal error - segments are not unique, but that's not the concern of the sniff.
$arrow = fn() : (A&B)|(B&A) => $a;

// Intentional fatal error - segments which are strict subsets of others are disallowed, but that's not the concern of the sniff.
// Intentional fatal error - using self type outside a class is invalid.
$arrow = fn($a) : (A&self)|A => $a;
