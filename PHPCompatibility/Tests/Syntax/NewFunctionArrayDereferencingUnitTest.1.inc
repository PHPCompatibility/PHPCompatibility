<?php

#[Example(static function () {
    return getValues()[0]; // @clean
})]
function functionArrayAttribute() {}

function outsideFunctionArray() {
    return getValues()[0]; // @outside
}
