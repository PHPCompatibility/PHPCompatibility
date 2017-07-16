<?php

/* Case 1 */
$var = false;

/* Case 2 */
if ($x === true) {
    echo 'test-if';
}
/* Case 3 */
elseif ($y === false) {
    echo 'test-else-if';
}
/* Case 4 */
else {
    echo 'test-else';
}

/* Case 5 */
for ($i = 0; $i<5;$i++) {
    echo 'test-for';
}

/* Case 6 */
foreach ($dataSet as $data) {
    echo $data;
}

switch($y) {
    /* Case 7 */
    case 1:
        /* Case 8 */
        echo 'test-switch-case';
        break;
    /* Case 9 */
    default:
        /* Case 10 */
        echo 'test-switch-default';
        break;
}

/* Case 11 */
function something() {
    echo 'test-function';
}

class MyClass {
    /* Case C1 */
    public $property;
    /* Case C2 */
    function something() {}
}

namespace {
    /* Case C3 */
    function something() {}

    class MyClass {
        /* Case C4 */
        function something() {}
    }
}

/* Case U1 */
use Baz;
/* Case U2 */
use Foobar as Baz;
/* Case U3- */
class Foobar { use /* Case U3 */ Baz }
/* Case U4- */
class Foobar { use /* Case U4 */ const Baz }
/* Case U5- */
class Foobar { use /* Case U5 */ function Baz }
/* Case U6 */
class Foobar { use BazTrait { oldfunction as Baz } }
/* Case U7 */
class Foobar { use BazTrait { oldfunction as public Baz } }
/* Case U8 */
class Foobar { use BazTrait { oldfunction as protected Baz } }
/* Case U9 */
class Foobar { use BazTrait { oldfunction as private Baz } }

/* Case C5 */
new class {
    function something() {}
}
