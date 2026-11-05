<?php

#[Example(__DIR__, __NAMESPACE__)] // @clean
function keywordAttribute() {}

#[Example(static function () {
    yield 1; // @clean
})]
function generatorAttribute() {}

$outside = __DIR__; // @outside

#[Example(static function () {
    return match (1) { 1 => 'value' }; // @modern
})]
function matchAttribute() {}
