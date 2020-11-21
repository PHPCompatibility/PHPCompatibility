<?php

/*
 * OK - normal use of magic constants.
 */
require __DIR__ . '/filename.php';
expectsCallback([__CLASS__, 'method']);

// Not a magic constant.
echo __MAGIC__;

/*
 * PHP 8.0: Magic constant dereferencing.
 */
echo __FUNCTION__[0];
$a = array(__trait__[$i]);
$b = __NAMESPACE__[$x][$y];
expectsCallback([__CLASS__[strlen(__CLASS__) - 1], 'method']);
$var = functionCall(__METHOD__[++$i]);
echo __LINE__[0][1];
echo __file__[strlen(__FILE__) - 1];
require __DIR__[0];

/*
 * Check against false positives.
 */

// Array assignments to magic constants is not possible.
__NAMESPACE__[] = 'x';
__Dir__[ /* comment */ ] += 'x';
__line__[0] -= 'x';
__METHOD__[0][1] .= 'x';
__namespace__[0][1][2] *= 'x';

// Dereferencing using curly braces is not supported.
echo __LINE__{0};

// Live coding.
// Intentional parse error, this has to be the last test case in the file.
echo __METHOD__[
