<?php

// PHP 7.0+
function foo($a): bool {}
function foo($a): int {}
function foo($a): float {}
function foo($a): string {}
function foo($a): array {}
function foo($a): callable {}
function foo($a): self {}
function foo($a): parent {}
function foo($a): Baz {}
function foo($a): \Baz {}
function foo($a): myNamespace\Baz {}
function foo($a): \myNamespace\Baz {}

// PHP 7.1+
function foo($a): iterable {}
function foo($a): void {}

// Anonymous function.
function($a): callable {}

// OK: no return type hint.
function foo($a) {}
function ($a) {}

// PHP 7.2+
function foo($a): object {}

function fooInterspersedWithComments($a) :
	// Comment.
	?
	// phpcs:ignore Standard.Category.Sniff -- ignore something about a return type declaration.
	\myNamespace\
	// Comment.
	Baz
{
}
