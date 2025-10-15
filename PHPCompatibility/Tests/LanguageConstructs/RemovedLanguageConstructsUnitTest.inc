<?php

/*
 * PHP 8.5: the backtick operator is deprecated.
 */
// Don't flag backticks in `comments`
echo 'or when `backticks` are used in text strings';

$output = `ls -al`;
$output = `mklink /J $junk0 $dir0`;
