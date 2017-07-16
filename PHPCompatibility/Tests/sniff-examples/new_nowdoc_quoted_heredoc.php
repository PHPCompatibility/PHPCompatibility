<?php

// Normal Heredoc is ok.
$str = <<<EOD
Example of string
spanning multiple lines
using nowdoc syntax.
EOD;

/*
 * Nowdoc test cases.
 */

// Nowdoc is not.
$str = <<<'LABEL'
Example of string
spanning multiple lines
using nowdoc syntax.
LABEL;

$array = array( 'nowdoc' => <<<'a_c'
Example of string
spanning multiple lines
using nowdoc syntax.
a_c
);

// Test properly identifying the closer.
$str = <<<'LABEL'
Now this is not the end: LABEL;
The next line is ;-)
LABEL;

$str = <<<'LABEL'
This is not the end:
LABEL; The next line is ;-)
LABEL;

// Test skipping forward. Doesn't work in PHPCS < 2.0.
echo <<<'NECHO'
You create a nowdoc using the
following syntax <<<'ID' to start
and you end the nowdoc with
the ID on a line by itself, like
so:
ID;
Easy, or not ?
NECHO;

/*
 * Quoted heredoc test cases.
 */

// Quoted heredoc is not.
$str = <<<"LABEL"
Example of string
spanning multiple lines
using nowdoc syntax.
LABEL;

$array = array( 'heredoc' => <<<"a_c"
Example of string
spanning multiple lines
using nowdoc syntax.
a_c
);

// Test properly identifying the closer.
$str = <<<"LABEL"
Now this is not the end: LABEL;
The next line is ;-)
LABEL;

$str = <<<"LABEL"
This is not the end:
LABEL; The next line is ;-)
LABEL;

// Test skipping forward. Doesn't work in PHPCS < 2.0.
echo <<<"NECHO"
You create a quoted heredoc using the
following syntax <<<"ID" to start
and you end the heredoc with
the ID on a line by itself, like
so:
ID;
Easy, or not ?
NECHO;
