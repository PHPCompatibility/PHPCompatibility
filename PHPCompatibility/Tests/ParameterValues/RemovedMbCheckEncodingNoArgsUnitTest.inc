<?php
/*
 * Test mb_check_encoding() should no longer be called without arguments since PHP 8.1.
 */

//OK.
mb_check_encoding($value, $encoding);
mb_check_encoding($value);
\mb_check_encoding(encoding: $encoding);

// Not OK.
mb_check_encoding();
\Mb_Check_Encoding( /* comment */ );

// Safeguard against false positives on method calls and namespaced function calls.
ClassName::mb_check_encoding();
$obj->mb_check_encoding();
$obj?->mb_check_encoding();
namespace\mb_check_encoding();
\Fully\Qualified\mb_check_encoding();
Partially\Qualified\mb_check_encoding();
