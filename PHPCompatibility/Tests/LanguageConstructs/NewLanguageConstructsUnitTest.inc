<?php

$foobar = new \Foobar();

function newTest(...$arguments) {}

$foobar = new namespace\Foobar();
$foobar = new Partially\Qualified\Foobar();

use Something\{
    More,
};
