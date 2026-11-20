<?php

#[Example(static function () { // @clean
    return 42;
})]
function closureAttribute() {}

$outside = static function () { return 42; }; // @outside

#[Example(static function () {
    return $this; // @invalid
})]
function invalidThisAttribute() {}
