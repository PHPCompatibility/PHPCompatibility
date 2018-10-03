<?php

// Array and string literal dereferencing.
echo array(1, 2, 3)[0];
echo [1, 2, 3][0];
echo [1, [20, 21, 22], 3][1][1]; // Multi-dimensional array - will give two errors in PHPCS < 2.8.2 / bug #1381.
echo 'PHP'[0];
echo "PHP"[0];

// Check against false positives.
echo [1, 2, 3];
echo [1, [20, 21, 22], 3];
echo 'PHP';
echo "P{$H}P"[0]; // Parse error.
echo $array[0];
echo $string[0];
