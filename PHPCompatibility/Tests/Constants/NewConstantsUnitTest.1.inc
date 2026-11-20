<?php

#[Example(E_DEPRECATED, \E_DEPRECATED)] // @clean
function constantAttribute() {}

#[Example(static function () {
    return E_DEPRECATED; // @clean
})]
function closureConstantAttribute() {}

$outside = E_DEPRECATED; // @outside

#[Example(T_MATCH)] // @modern
function modernConstantAttribute() {}

#[Example(T_BAD_CHARACTER)] // @removed
function reintroducedConstantAttribute() {}
