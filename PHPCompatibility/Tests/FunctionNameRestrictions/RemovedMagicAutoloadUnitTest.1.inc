<?php

function __autoload($someclass) {
    echo 'I am the autoloader - I am deprecated from PHP 7.2 onwards';
}

class fooclass {
    function __autoload($someclass) {
        echo 'I am NOT an autoloader as I\'m not in the global namespace';
    }
}

interface foointerface {
    function __autoload($someclass);
}

trait footrait {
    function __autoload($someclass) {
        echo 'I am NOT an autoloader as I\'m not in the global namespace';
    }
}

fooanonclass(new class {
    function __autoload($someclass) {
        echo 'I am NOT an autoloader as I\'m not in the global namespace';
    }
});

class Nested {
    public function test() {
        // Nested function becomes available in the global namespace.
        function __autoload($someclass) {
            echo 'I am the autoloader - I am deprecated from PHP 7.2 onwards';
        }
    }
}

enum fooenum {
    public function __autoload($someclass) {
        echo 'I am NOT an autoloader as I\'m not in the global namespace';
    }
}
