<?php

/*
 * Okay cross-version: literal octal notation.
 */
$octal = 052;

// PHP 7.4 octal with numeric literal separator. Not the concern of this sniff.
$octal = 0137_041;

// Invalid octal. Parse error. Not the concern of this sniff.
$octal = 0o_;

// Invalid octal. Parse error. Not the concern of this sniff.
$octal = 0o9;

/*
 * PHP 8.1 explicit octal notation.
 */
$octal = 0o137041;
$octal = 0O137041; // Testing uppercase O.
$octal = 0o137_041; // Explicit notation with separator.
