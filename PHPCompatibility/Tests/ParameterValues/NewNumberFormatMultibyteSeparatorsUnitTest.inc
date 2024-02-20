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
number_format($number, decimal_separator: $dec_point, thousands_separator: $thousands_sep, decimals: $decimals); // Undetermined.
// Variables passed in a double quoted string are presumed to be single-byte.
number_format($number, $decimals, "$dec_point", "${thousands->{${'sep'}}}");
number_format($number, $decimals, '.', ','); // Single-byte.

/*
 * Not OK.
 */
number_format($number, $decimals, '.', '::'); // Thousand-sep multi-byte.

// Note: this is an invalid function call, as when the $decimal_separator is passed, the $thousands_separator
// has to be passed too, but that's not the concern of this sniff.
number_format(num: $number, decimal_separator: "-$a", decimals: $decimals,); // Dec point multi-byte (most probably, depends on contents of the variable).

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

// Bug #1679: empty string was incorrectly being flagged as multi-byte.
number_format($number, $decimals, '', ''); // OK.
number_format($number, $decimals, "", ""); // OK.
