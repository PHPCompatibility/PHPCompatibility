<?php
$a = $b = $c = 0;

// Always OK:
$a ? $b : $c;

// Only in 5.3 and above:
$a ?: $c;

$isError = ($function != 'ini_get') ?: false;

$hasComment = $a ? /* deliberately empty */ : $b;

// Short ternaries inside attributes are ignored before PHP 8.0.
#[Example(true ?: false)]
class SingleLineShortTernaryAttribute {}

#[Example(
    true ? /* deliberately empty */ : false
)]
class MultiLineShortTernaryAttribute {}

// Ordinary code must still be checked.
$ordinary = true ?: false;
