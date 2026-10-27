<?php

#[Example(static function ($className) {
    return $className::VALUE; // @clean
})]
function dynamicStaticAttribute() {}

function outsideDynamicStatic($className) {
    return $className::VALUE; // @outside
}
