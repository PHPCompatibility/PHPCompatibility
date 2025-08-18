<?php

// OK.
$handle = fopen("/home/rasmus/file.txt", "r");
$handle = \fopen("/home/rasmus/file.gif", mode: 'wb');
$handle = fopen("/home/rasmus/file.gif", 'a' . $additional_modes);

// Not OK.
$handle = fopen("/home/rasmus/file.txt", "re"); // PHP 7.0.16+
$handle = \fopen("/home/rasmus/file.gif", 'c+'); // PHP 5.2.6+
$handle = fopen(mode: 'c', filename: "/home/rasmus/file.gif"); // PHP 5.2.6+
$handle = FOpen("/home/rasmus/file.gif", 'c'./*comment*/'e'); // PHP 5.2.6+ and PHP 7.0.16+

// Issue #1043 - ignore function calls, constants etc.
$handle = fopen("/home/rasmus/file.gif", setFormat('c+'));
$handle = fopen("/home/rasmus/file.txt", $array["re"]);
$handle = fopen("/home/rasmus/file.gif", namespace\setFormat('c+'));
$handle = \fopen("/home/rasmus/file.gif", \Fully\Qualified\setFormat('c+'));
$handle = fopen("/home/rasmus/file.gif", Partially\Qualified\setFormat('c+'));

// Safeguard against false positives. Not our target.
$obj->fopen("/home/rasmus/file.txt", "re");
$obj?->fopen("/home/rasmus/file.txt", "re");
MyClass::fopen("/home/rasmus/file.txt", "re");
\Fully\Qualified\fopen("/home/rasmus/file.txt", "re");
Partially\Qualified\fopen("/home/rasmus/file.txt", "re");
namespace\fopen("/home/rasmus/file.txt", "re");
