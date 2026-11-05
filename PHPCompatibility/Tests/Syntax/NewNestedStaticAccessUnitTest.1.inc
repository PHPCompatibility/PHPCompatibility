<?php

#[Example(static function ($object) {
    return $object->factory()::VALUE; // @clean
})]
function nestedStaticAttribute() {}

function outsideNestedStatic($object) {
    return $object->factory()::VALUE; // @outside
}

#[Example(static function () {
    return Registry::TARGET::VALUE; // @modern
})]
function constantDereferenceAttribute() {}
