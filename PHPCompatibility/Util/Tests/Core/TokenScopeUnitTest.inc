<?php

/* test 1 */
$var = false;

/* test 2 */
if ($x === true) {
    echo 'test-if';
}
/* test 3 */
elseif ($y === false) {
    echo 'test-else-if';
}
/* test 4 */
else {
    echo 'test-else';
}

/* test 5 */
for ($i = 0; $i<5;$i++) {
    echo 'test-for';
}

/* test 6 */
foreach ($dataSet as $data) {
    echo $data;
}

switch($y) {
    /* test 7 */
    case 1:
        /* test 8 */
        echo 'test-switch-case';
        break;
    /* test 9 */
    default:
        /* test 10 */
        echo 'test-switch-default';
        break;
}

/* test 11 */
function something() {
    echo 'test-function';
}

class MyClass {
    /* test C1 */
    public $property;
    /* test C2 */
    function something() {}
}

namespace {
    /* test C3 */
    function something() {}

    class MyClass {
        /* test C4 */
        function something() {}
    }
}

/* test C5 */
new class {
    function something() {}
}

interface MyInterface {
    /* test I1 */
    function something();
}

trait MyTrait {
    /* test T1 */
    public $property;

    /* test T2 */
    function something() {}
}
