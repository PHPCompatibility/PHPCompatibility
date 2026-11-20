<?php

#[Example(static function (): int { // @clean
    return 42;
})]
function returnTypeAttribute() {}

function outsideReturnType(): int { return 42; } // @outside

#[Example(static function (): int|string { // @modern
    return 42;
})]
function unionReturnAttribute() {}

#[Example(static function (): void|int { // @invalid
})]
function invalidVoidReturnAttribute() {}
