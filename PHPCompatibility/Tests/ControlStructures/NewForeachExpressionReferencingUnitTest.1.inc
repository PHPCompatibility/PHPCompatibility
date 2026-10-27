<?php

#[Example(static function () {
    foreach (array(1, 2) as &$value) {} // @clean
})]
function foreachReferenceAttribute() {}

function outsideForeachReference() {
    foreach (array(1, 2) as &$value) {} // @outside
}
