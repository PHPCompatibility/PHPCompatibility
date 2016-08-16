<?php

$var = false;
function something() {}

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
