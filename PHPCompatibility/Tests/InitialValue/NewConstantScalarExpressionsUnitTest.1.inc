<?php

#[Example(static function ($value = 1 + 2) { // @clean
    static $cache = 3 + 4; // @clean
    return $value + $cache;
})]
function scalarAttribute() {}

function outsideScalar($value = 1 + 2) { // @outside
    static $cache = 3 + 4; // @outside
    return $value + $cache;
}
