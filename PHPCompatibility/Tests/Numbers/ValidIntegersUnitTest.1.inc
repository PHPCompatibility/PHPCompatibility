<?php

#[Example(0b1010, 0B1111)] // @clean
function binaryAttribute() {}

#[Example(static function () {
    return 0b1010; // @clean
})]
function closureBinaryAttribute() {}

$outside = 0b1010; // @outside

#[Example(0b102)] // @invalidBinary
function invalidBinaryAttribute() {}

#[Example(089)] // @invalidOctal
function invalidOctalAttribute() {}
