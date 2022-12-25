<?php

/**
 * Valid cross-version/not our targets.
 */

class ClassWithoutKeywords {}

final class FinalClass {}

abstract class AbstractClass {
    function class() {}
}

// Anon classes do not take keywords (and are not mentioned in the RFC).
$anon = new class () {};

/**
 * PHP 8.2+: readonly classes.
 */
readonly class MyReadonlyClass {
    public string $foo;

    // Untyped properties are not allowed in readonly classes, but that's not the concern of this sniff.
    public $bar;

    // Static properties are not allowed in readonly classes, but that's not the concern of this sniff.
    public static int $baz;
}

abstract readonly class AbstractReadonly {}
readonly abstract class ReadonlyAbstract {}

final readonly class FinalReadonly {}
readonly final class ReadonlyFinal {}

// Extending readonly classes is allowed, as long as the child is also marked readonly.
readonly class ChildClass extends ParentClass {}

// The AllowDynamicProperties attribute is not allowed on readonly classes, but that's not the concern of this sniff.
#[AllowDynamicProperties]
readonly class DynamicPropertiesNotAllowed {}

// Verify handling with superfluous whitespace and comments.
readonly
// Comment
abstract
class
ClassName {}

// Duplicate readonly modifier is not allowed, but that's not the concern of this sniff.
readonly readonly class DoubleReadonly {}
