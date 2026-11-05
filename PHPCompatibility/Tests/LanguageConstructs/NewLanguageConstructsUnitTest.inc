<?php

$foobar = new \Foobar();

function newTest(...$arguments) {}

$foobar = new namespace\Foobar();
$foobar = new Partially\Qualified\Foobar();

use Something\{
    More,
};

// Attribute class names and arguments are comments on PHP versions before 8.0.
#[Vendor\Example(\Vendor\Flags::VALUE, namespace\Flags::VALUE)]
function namespacedAttribute() {}

#[\Vendor\Example([...array(1, 2)])]
function attributeArraySpread() {}

#[One\First, \Two\Second(
    \Three\Flags::VALUE,
    [...array(3, 4)]
)]
function groupedAttributes() {}

#[Vendor\Example]
function namespaceOutsideAttribute() {
    return \Vendor\Flags::VALUE;
}

#[Vendor\Example]
function spreadOutsideAttribute() {
    return array(...$values);
}
