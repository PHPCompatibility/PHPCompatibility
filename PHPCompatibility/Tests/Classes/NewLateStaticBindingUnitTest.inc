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
