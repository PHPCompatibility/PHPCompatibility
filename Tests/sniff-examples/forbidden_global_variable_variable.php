<?php

/*
 * Forbidden global variable variables.
 */
global $$test;

// Multi-variable and multi-line global statements.
global $test, $$test, $$$test;

global $test,
    $$obj->$bar,
    $testing,
    $$test;

/*
 * Ok.
 */
global $test;
global ${$test};
global ${"name"};
global ${"name_$type"};
global ${$var['key1']['key2']};
global ${$obj->$bar};
global ${$obj->{$var['key']}};

// Live coding.
global
