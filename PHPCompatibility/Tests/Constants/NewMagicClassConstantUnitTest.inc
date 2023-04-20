<?php

namespace foo {
    class bar {}

    echo bar::class; // foo\bar
}

namespace MyNameSpace {
    class xyz {}

    remove_filter('theme_filter', [\MyNameSpace\xyz:: /* comment */ class, 'methodName'], 30);
}

/*
 * False positives check.
 */
echo bar::classProp; // Not the keyword.
$anon = new class {}; // Anonymous class, not the keyword.

/*
 * Differentiate between Name::class (PHP 5.5) and $obj::class (PHP 8.0)
 */
class Foo {
    public function test() {
        // PHP 5.5+.
        if (class_exists(Name::class)) {}
        if (class_exists(Vendor\Name::CLASS)) {}
        if (class_exists(namespace\Name::class)) {}
        if (class_exists(self::class)) {}
        if (class_exists(parent::class)) {}
        if (class_exists(static::class)) {}

        // PHP 8.0+, but only if the result of the expression is an object.
        if (class_exists($obj::class)) {}
        var_dump((new stdClass)::Class);
        var_dump((new MyClass($paramA, $paramB))::class);
        if (class_exists($array['object']::class)) {}
        if (class_exists($obj->otherObjectSavedAsProperty::class)) {}
    }
}

/*
 * Syntaxes which are not supported in any version.
 * PHPCompatibility shouldn't throw an error for these, but it's darn hard to figure out if it's
 * a supported or non-supported syntax.
 * As it would be a fatal error anyway, don't bother differentiating for now.
 */
$name = ($string = 'text')::class; // Using ::class on a string literal.
$name = (1+1)::class; // Using ::class on a literal.
const A = [0]::class; // Using ::class on a literal.
$name = ClassName::CONSTANT_NAME::class; // Objects can not be set as the value for a constant.

// Safeguard against false positives for functions called "class".
class NotTheMagicConstant {
    public function &class() {
        self::class();
        NotTheMagicConstant::class();
        My\Class\ClassName::class();
    }
}

// Safeguard handling of class resolution with PHP 8.0 nullsafe object operator.
class_exists($obj?->otherObjectSavedAsProperty::class));

// Safeguard handling of class resolution found within PHP 8.0+ attribute.
#[MyAttribute(\Fully\Qualified\Other::class)]
function hasAttribute() {}
