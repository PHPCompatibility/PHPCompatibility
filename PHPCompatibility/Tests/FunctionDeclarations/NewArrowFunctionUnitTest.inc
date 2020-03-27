<?php

// OK: fn, but not an arrow function.
$a = Foo::fn($param);
$a = MyClass::FN;
$a = MyClass::Fn[$a];
$a = $obj->fn($param);
$a = $obj->FN($param);
$a = MyNS\Sub\Fn($param);
$a = namespace\fn($param);

class Foo {
    const FN = 'a';

    public static function fn($param) {
    }

    public function bar() {
        $this->fn = 'a';
    }
}

// PHP 7.4 arrow functions.
$nums = array_map(fn($n) => $n * $factor, [1, 2, 3, 4]);

$fn1 = Fn($x) => $x + $y;

$fn = fn($c) => $callable($factory($c), $c);

$result = Collection::from([1, 2])
    ->map(fn($v) => $v * 2)
    ->reduce(fn($tmp, $v) => $tmp + $v, 0);

$result = array_map(
    static fn(int $number) : ?int => $number + 1,
    $numbers
);
