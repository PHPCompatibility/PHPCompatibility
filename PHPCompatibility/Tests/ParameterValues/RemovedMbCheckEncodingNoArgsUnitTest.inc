<?php
/*
 * Test mb_check_encoding() should no longer be called without arguments since PHP 8.1.
 */

//OK.
mb_check_encoding($value, $encoding);
mb_check_encoding($value);
mb_check_encoding(encoding: $encoding);

// Not OK.
mb_check_encoding();
Mb_Check_Encoding( /* comment */ );
