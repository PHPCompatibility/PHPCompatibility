<?php

$var = false;

if ($x === true) {
    echo 'test-if';
}
elseif ($y === false) {
    echo 'test-else-if';
}
else {
    echo 'test-else';
}

for ($i = 0; $i<5;$i++) {
    echo 'test-for';
}

foreach ($dataSet as $data) {
    echo $data;
}

switch($y) {
    case 1:
        echo 'test-switch-case';
        break;
    default:
        echo 'test-switch-default';
        break;
}

function something() {
    echo 'test-function';
}

class MyClass {
    public $property;
    function something() {}
}

namespace {
    function something() {}

    class MyClass {
        function something() {}
    }
}

use Baz;
use Foobar as Baz;
class Foobar { use Baz }
class Foobar { use const Baz }
class Foobar { use function Baz }
class Foobar { use BazTrait { oldfunction as Baz } }
class Foobar { use BazTrait { oldfunction as public Baz } }
class Foobar { use BazTrait { oldfunction as protected Baz } }
class Foobar { use BazTrait { oldfunction as private Baz } }

new class {
    function something() {}
}
