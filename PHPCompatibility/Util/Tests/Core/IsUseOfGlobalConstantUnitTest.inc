<?php

/*
 * Make sure that global constants are correctly identified.
 *
 * The below should *NOT* be recognized as global constant usage.
 */

/* test 1 */
namespace PHP_VERSION_ID {}

/* test 2 */
namespace MY\OTHER\PHP_VERSION_ID\NS {}

/* test 3 */
use PHP_VERSION_ID;

/* test 4 */
use Something, PHP_VERSION_ID, SomethingElse;

/* test 5 */
class PHP_VERSION_ID {
    /* test 6 */
    const PHP_VERSION_ID = 'something';
    /* test 7 */
    private function PHP_VERSION_ID() {}
}

/* test 8 */
class ABC extends PHP_VERSION_ID {}

/* test 9 */
class DEF implements PHP_VERSION_ID {}

/* test 10 */
interface PHP_VERSION_ID {}

/* test 11 */
trait PHP_VERSION_ID {}

/* test 12 */
$a = new PHP_VERSION_ID;

/* test 13 */
$a = new PHP_VERSION_ID();

/* test 14 */
function PHP_VERSION_ID() {}

/* test 15 */
echo PHP_VERSION_ID();

/* test 16 */
echo My\UsedAsNamespace\PHP_VERSION_ID\something;

/* test 17 */
My\UsedAsNamespace\PHP_VERSION_ID\something::something_else();

/* test 18 */
if ( $abc instanceof PHP_VERSION_ID ) {}

/* test 19 */
goto PHP_VERSION_ID;

/* test 20 */
echo \mynamespace\PHP_VERSION_ID;

/* test 21 */
echo My_Class::PHP_VERSION_ID;

/* test 22 */
echo $this->PHP_VERSION_ID;

/* test 23 */
use const SomeNamespace\PHP_VERSION_ID as SSP; // PHP 5.6+

/* test 24 */
use const ABC as PHP_VERSION_ID;

/* test 25 */
use const SomeNamespace\{PHP_VERSION_ID, TEMPLATEPATH}; // PHP 7.0+

class Talker {
    /* test 26 */
    use A, PHP_VERSION_ID, C {
        /* test 27 */
        PHP_VERSION_ID::smallTalk insteadof A;
        /* test 28 */
        A::bigTalk insteadof PHP_VERSION_ID;
    }
}

class MyClass2 {
    use HelloWorld {
        /* test 29 */
        sayHello as private PHP_VERSION_ID;
        /* test 30 */
        sayGoodbye as protected PHP_VERSION_ID;
        /* test 31 */
        sayHowdy as public PHP_VERSION_ID;
    }
}

/* test 32 */
echo $this?->PHP_VERSION_ID;

/*
 * Make sure that global constants are correctly identified.
 *
 * The below should be recognized as global constant usage.
 */

/* test A1 */
echo PHP_VERSION_ID;

/* test A2 */
echo \PHP_VERSION_ID; // Global constant.

/* test A3 */
$folder = basename( PHP_VERSION_ID );

/* test A4 */
include PHP_VERSION_ID . '/js/myfile.js';

/* test A5 */
use const PHP_VERSION_ID as SSP;

/* test A6 */
switch( PHP_VERSION_ID ) {
    /* test A7 */
    case PHP_VERSION_ID:
        break;
}

/* test A8 */
const PHP_VERSION_ID = 'something';

/* test A9 */
$array[PHP_VERSION_ID] = 'something';

/* test A10 */
const ABC = '123',
      DEF = '456',
      PHP_VERSION_ID = 'something',
	  GHI = 789;
