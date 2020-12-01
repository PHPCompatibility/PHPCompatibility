<?php

/*
 * No parameters, no issue.
 */
do_something();

/*
 * Allowed pre-PHP 8.0.
 */
array_fill(0, 100, 50);
array_fill(START_INDEX, \COUNT, MyNS\VALUE);

/*
 * PHP 8.0 named parameters in function calls.
 */
array_fill(start_index: 0, count: 100, value: 50);

array_fill(
    start_index : 0,
    count       : 100,
    value       : 50,
);

// Test whitespace and comment tolerance.
array_fill ( start_index : 0, /*comment */ count /*comment*/ : 100, value: 50);

htmlspecialchars($string, double_encode: false);

array_fill(
    start_index: $obj->getPos(skip: false),
    count: count(array_or_countable: $array),
    value: 50
);

namespace\function_name(label:$string, more: false);
Partially\Qualified\function_name(label:$string, more: false);
\Fully\Qualified\function_name(label: $string, more:false);
$fn(label: $string, more:false);
$obj->methodName(label: $foo, more: $bar);
$obj = new MyClass(label: $string, more:false);
$obj = new self(label: $string, more:true);
$obj = new static(label: $string, more:false);

$anon = new class(label: $string, more: false) {
    public function __construct($label, $more) {}
};

// Syntaxes not yet supported by PHPCSUtils.
${$fn}(label: $string, more:false); // False negative.
$obj->{$var}(label: $foo, more: $bar); // False negative.

// Non-ascii names.
foo(💩💩💩: [], Пасха: 'text', _valid: 123);

foo( label: $cond ? true : false, more: $cond ? CONSTANT_A : CONSTANT_B );

echo $cond ? foo( label: $something ) : foo( more: $something_else );

// Reserved keywords are allowed.
foobar(
    abstract: $value,
    function: $value,
    protected: $value,
    object: $value,
    parent: $value,
);

// Compile error: named param before positional. Report anyway.
test(param: $bar, $foo);

// Error Exception: duplicate parameter passed. Report anyway.
test(param: 1, /* testDuplicateName2 */ param: 2);

// Error Exception: named params cannot be used with variadic. Report anyway.
array_fill(start_index: 0, ...[100, 50]);

// Compile error: named params cannot be used after variadic. Report anyway.
test(...$values, param: $value); // Compile-time error

/*
 * Still not allowed.
 */
// Parse error: dynamic name. Ignore.
function_name($variableStoringParamName: $value);

// Parse error: exit is a language construct, not a function. Named params not supported.
exit(status: $value);

// Parse error: empty is a language construct, not a function. Named params not supported.
empty(variable: $value);

// Parse error: eval is a language construct, not a function. Named params not supported.
eval(code: $value);

// Parse error: "named param" in arbitrary parentheses.
$calc = (something: $value / $other);
