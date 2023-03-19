<?php

class LateStatic {
    private static $bar;

    public function test() {
        self::foo(); // Ok.
        static::foo(); // Late static binding.
        echo static::$bar; // Late static binding.
        $name = static::class; // Late static binding.

        $obj = new static; // Late static binding.
        $obj = new static(); // Late static binding.

        $foo = $foo instanceof static ? 'foo' : 'bar'; // Late static binding.

        return $foo instanceof static; // Late static binding.
    }

    public static function foo() {} // Ok.
}

static function testing() { // Ok.
    static $var; // Ok.
}

static::testing(); // Bad. Outside class scope.

$closure = static function() {};
$arrow = static fn() => 'foo';

class Foo {
    public function hasStaticDefaultValue( $param = static::SOME_CONST) {} // Late static binding.

    public function hasStaticParamType(static $param) {}
    public function hasStaticParamUnionType(static|string $param) {}
    public function hasStaticReturnType(): static {}
    public function hasStaticReturnIntersectionType(): static&MyInterface {}
    public function static() {}
}

// Live coding/parse error.
// This has to be the last test in the file.
static
