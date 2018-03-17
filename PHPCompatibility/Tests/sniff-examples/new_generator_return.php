<?php

// OK.
function gen_one_to_three() {
    for ($i = 1; $i <= 3; $i++) {
        yield $i;
    }
}

function gen_one_to_three() {
	$a = array();
    for ($i = 1; $i <= 3; $i++) {
        $a[] = 'yield'.$i;
    }
    return $a;
}

$gen = function {
    $foo = yield myAsyncFoo();
    $bar = yield myAsyncBar($foo);
    yield "return" => $bar + 42;
};


// PHP 7.0+
function foo() {
    yield 0;
    yield 1;

    return 42;
}

$generator = function () {
    yield 1;
    return yield from bar();
}

function gen() {
    return $foo;
    yield;
}
