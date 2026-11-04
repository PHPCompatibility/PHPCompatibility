<?php

/*
 * Not our target.
 */
declare(strict_types=1);
declare(ticks=2, strict_types=0);

declare(ticks=1):
    // Do something.
enddeclare;

declare(ticks=20) {
    // Do something.
}

// Invalid, but not our concern.
declare(invalid=true);

/*
 * Valid: declaring encoding not using block mode.
 */
declare(encoding='utf8');
declare(
    /* comment*/
    ENCODING
    =
    // phpcs:ignore Stnd.Cat.SniffName -- for reasons.
    'ISO-8859-1'
);


/*
 * PHP 8.7: block mode for declaring encoding is no longer allowed.
 */
declare(encoding='utf8') {
    echo "ok\n";
}
declare(encoding="ISO-8859-15"):
    echo "ok\n";
enddeclare;

declare(ENCODING=INVALID_VALUE, ticks=2) {
    echo "ok\n";
}
declare(
    /** comment */
    ticks = 15, // comment
    // phpcs:ignore Stnd.Cat.SniffName -- for reasons.
    encoding  =   "ISO-8859-15"
):
    echo "ok\n";
enddeclare;
