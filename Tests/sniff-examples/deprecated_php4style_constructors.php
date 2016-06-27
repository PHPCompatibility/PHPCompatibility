<?php
class foo {
    function foo() {
        echo 'I am the constructor - but I shouldn\'t be';
    }
}

class bar {
    function bar() {
        echo 'I am just the foo method';
    }
    function __construct() {
        echo 'I am the real constructor';
    }
}
