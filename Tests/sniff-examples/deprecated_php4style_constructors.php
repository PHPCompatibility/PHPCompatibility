<?php
class foo {
    function foo() {
        echo 'I am the constructor - but I shouldn\'t be';
    }
}

class bar {
    function bar() {
        echo 'I am just the bar method';
    }
    function __construct() {
        echo 'I am the real constructor';
    }
}

class barFOO {
    function BARfoo() {
        echo 'I am the constructor - but I shouldn\'t be';
    }
}

namespace foobar {

    class foobar {
        function foobar() {
            echo 'I am just the bar method';
        }
    }
}
