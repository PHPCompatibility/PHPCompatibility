<?php

// Not sniff's target (examples below focus on NumberFormatter::TYPE_CURRENCY)
// @see RemovedClassConstantsUnitTest::dataNoFalsePositives

[NumberFormatter::NOT_THE_TARGET_CONSTANT, TYPE_CURRENCY]; // TYPE_CURRENCY is not a class constant of NumberFormatter
[OtherClass::TYPE_CURRENCY]; // TYPE_CURRENCY is a constant of a different class
NumberFormatter::TYPE_CURRENCY_ADDITIONAL_TEXT // Additional text after TYPE_CURRENCY makes it a different constant name
case $NumberFormatter: TYPE_CURRENCY; // syntax containing single colon
NumberFormatter::${$TYPE_CURRENCY}; // fetch static property using dynamic name
NumberFormatter::{$TYPE_CURRENCY}(); // call static method using dynamic name
$NumberFormatter::$TYPE_CURRENCY; // fetch static property using dynamic name from object's class
$NumberFormatter::$TYPE_CURRENCY(); // call static method on object's class using explicit method name

// Sniff's target: deprecated
// @see RemovedClassConstantsUnitTest::dataDeprecatedConstants
// @see RemovedClassConstantsUnitTest::dataNoViolationsOnValidVersion

NumberFormatter::
    /* newline and comment between tokens */ TYPE_CURRENCY; // deprecated since PHP 8.3

// Parse error / live coding
// @see RemovedClassConstantsUnitTest::dataNoFalsePositives

NumberFormatter::
