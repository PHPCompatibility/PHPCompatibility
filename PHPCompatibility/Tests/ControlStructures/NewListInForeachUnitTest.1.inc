<?php

#[Example(static function () {
    foreach (array(array(1, 2)) as list($left, $right)) {} // @clean
})]
function foreachListAttribute() {}

function outsideForeachList() {
    foreach (array(array(1, 2)) as list($left, $right)) {} // @outside
}
