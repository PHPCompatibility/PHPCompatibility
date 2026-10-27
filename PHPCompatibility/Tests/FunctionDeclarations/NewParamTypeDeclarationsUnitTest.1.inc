<?php

#[Example(static function (array $values, callable $callback, int $number) { // @clean
    return $values;
})]
function paramTypeAttribute() {}

function outsideParamType(array $values) {} // @outside

#[Example(static function (int|string $value) { // @modern
    return $value;
})]
function unionParamAttribute() {}

#[Example(static function (?mixed $value) { // @invalid
    return $value;
})]
function nullableMixedAttribute() {}
