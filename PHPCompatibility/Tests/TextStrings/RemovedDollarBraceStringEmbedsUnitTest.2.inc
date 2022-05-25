<?php

/**
 * The tests involving PHP 7.3+ indented heredocs are in a separate test case file
 * as any code after an indented heredoc will be tokenizer garbage on PHP < 7.3.
 */

// No embeds.
$php73IndentedHeredoc = <<<"EOD"
    some text some text
    EOD;

/*
 * Embedded variables which are supported cross-version.
 */

// Type 1: directly embedding variables.
$php73IndentedHeredoc = <<<"EOD"
    some text $foo[bar] some text
    EOD;

// Type 2: Braces around the variable/expression.
$php73IndentedHeredoc = <<<EOD
    some text {${$bar}} some text
    EOD;

/*
 * PHP 8.2: deprecated forms of embedding variables.
 */

// Type 3: Braces after the dollar sign.
$php73IndentedHeredoc = <<<"EOD"
    some text ${foo['bar']} some text
    EOD;

// Type 4: Variable variables.
$php73IndentedHeredoc = <<<EOD
    Line without embed
    some text ${$object->getMethod()} some text
    some text ${foo["${bar['baz']}"]} some text
    some text ${foo->{${'a'}}} some text
    EOD;
