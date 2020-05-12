<?php
/*
 * Test session_module_name() PHP 7.2 change in accepted values.
 */

/*
 * OK.
 */
number_format();
number_format($number);
number_format($number, $decimals);
number_format($number, $decimals, $dec_point, $thousands_sep); // Undetermined.
// Variables passed in a double quoted string are presumed to be single-byte.
number_format($number, $decimals, "$dec_point", "$thousands_sep");
number_format($number, $decimals, '.', ','); // Single-byte.

/*
 * Not OK.
 */
number_format($number, $decimals, '.', '::'); // Thousand-sep multi-byte.

// Note: this is an invalid function call, as when the third param is passed, the fourth has to be passed too,
// but that's not the concern of this sniff.
number_format($number, $decimals, "-$a",); // Dec point multi-byte (most probably, depends on contents of the variable).

number_format(
    $number,
    $decimals,
    <<<EOD
$a-$b
EOD
    ,
    <<<'EOT'
::
EOT
); // Both multi-byte.
