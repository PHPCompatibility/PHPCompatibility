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

// Intentional fatal error - mixing types which cannot be combined, but that's not the concern of the sniff.
function unionTypesAllPseudoTypes($var) : false|MIXED|self|parent|static|iterable|Resource|void {}

// Intentional fatal error - nullability is not allowed with union types, but that's not the concern of the sniff.
$closure = function () use($a) :?int|float {};

// Intentional fatal error - null pseudotype is only allowed in union types.
function pseudoTypeNull(): null {}

// Intentional fatal error - false pseudotype is only allowed in union types.
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
