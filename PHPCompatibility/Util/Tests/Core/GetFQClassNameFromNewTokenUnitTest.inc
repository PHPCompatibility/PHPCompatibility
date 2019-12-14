<?php

/* test 1 */
new DateTime;


namespace MyTesting;

/* test 2 */
new namespace\DateTime();
/* test 3 */
new DateTime;
/* test 4 */
new \DateTime();
/* test 5 */
new anotherNS\DateTime();
/* test 6 */
new \FQNS\DateTime();


namespace AnotherTesting {
    /* test 7 */
    new namespace\DateTime();
    /* test 8 */
    new DateTime;
    /* test 9 */
    new \DateTime();
    /* test 10 */
    new anotherNS\DateTime();
    /* test 11 */
    new \FQNS\DateTime();
}

/* test 12 */
new DateTime;
/* test 13 */
new \DateTime;
/* test 14 */
new \AnotherTesting\DateTime();


// Variant on issue #205.
$className = 'DateTime';
/* test 15 */
new $className;


// Issue #338 - no infinite loop on unfinished code.
/* test 16 */
$var = new
