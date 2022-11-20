<?php

/*
 * The below directives are valid.
 */
declare(ticks=1);
declare ( TICKS = 1 ) {} // Test with varying spacing and case-sensitivity.
declare(Encoding='ISO-8859-1');
declare(strict_types=1) {
    $var = false;
}

/*
 * The below directives have invalid values.
 */
declare(ticks=TICK_VALUE); // Invalid - only literals may be given as directive values.
declare(encoding='invalid'); // Invalid - not a valid encoding.
declare(Strict_Types=false); // Invalid - only 1 is a valid value.

// Invalid directive name.
declare(
    invalid=true
);

// Strict types can have value 0.
declare(strict_types=0);

// Strict types does not type juggle the value.
declare(strict_types='0');
declare(
    // Comment.
    strict_types='1'
);

/*
 * Testing multi-declare statements.
 */
declare(
    ticks = 10.56,
    encoding='UTF-8',
    strict_types=0
); // All valid.

declare(ticks = new stdClass, encoding=$encoding, strict_types=false, unknown = 'invalid', ); // All invalid in one way or another; also: trailing comma not allowed.

// Incomplete directive.
declare(
