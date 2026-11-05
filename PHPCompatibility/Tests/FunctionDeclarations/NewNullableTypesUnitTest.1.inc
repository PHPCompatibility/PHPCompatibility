<?php

#[Example(static function (?int $value): ?string { // @clean
    return null;
})]
function nullableAttribute() {}

function outsideNullable(?int $value): ?string { // @outside
    return null;
}
