<?php

#[Example(static function ($value = <<<ATTRIBUTE_DEFAULT
attribute default
ATTRIBUTE_DEFAULT
) { // Default parameter diagnostic is attached to its variable above.
    static $cache = <<<ATTRIBUTE_STATIC
attribute static
ATTRIBUTE_STATIC;
    return $value . $cache;
})]
function heredocAttribute() {}

function outsideHeredoc($value = <<<OUTSIDE_DEFAULT
outside default
OUTSIDE_DEFAULT
) {
    return $value;
}
