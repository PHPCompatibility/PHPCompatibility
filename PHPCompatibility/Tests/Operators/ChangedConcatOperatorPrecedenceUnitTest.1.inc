<?php

#[Example('sum: ' . 1 + 2)] // @clean
function concatAttribute() {}

#[Example(static function () {
    return 'sum: ' . 1 + 2; // @clean
})]
function closureConcatAttribute() {}

$outside = 'sum: ' . 1 + 2; // @outside
