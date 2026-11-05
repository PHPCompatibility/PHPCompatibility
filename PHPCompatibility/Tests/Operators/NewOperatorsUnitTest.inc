<?php

$x = 5 ** 10;
$x **= 10;

echo $x ?? $a;
$x ??= 5;

echo 1 <=> 1;

$foo = $a?->b();

// Operators introduced before PHP 8.0 are harmless in legacy attribute comments.
#[Example(2 ** 3, 1 <=> 2, null ?? 'fallback')]
function attributeOperators() {}

#[Example(
    3 /* base */ ** 2,
    null /* missing */ ?? (1 <=> 2)
)]
function multilineAttributeOperators() {}

// Keep PHP 8.0 operator detection even in syntactically tokenizable attribute arguments.
#[Example($object?->value)]
function attributeNullsafeOperator() {}

#[Example(2 ** 3)]
function operatorsOutsideAttribute() {
    return 2 ** 3;
}
