<?php

#[Example(static function () {
    return (binary) 'attribute'; // @clean
})]
function castAttribute() {}

$outside = (binary) 'outside'; // @outside

#[Example(static function () {
    (void) strlen('value'); // @modern
})]
function voidCastAttribute() {}
