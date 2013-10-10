<?php

class Foobar
{
    public $id = 0;
}

$foobar = new Foobar();
$foobar2 = &new Foobar();
$foobar3 = & new Foobar();
