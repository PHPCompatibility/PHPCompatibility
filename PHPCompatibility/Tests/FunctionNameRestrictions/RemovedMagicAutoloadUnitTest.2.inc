<?php

namespace magicAutoloadDeprecations;

function __autoload($someclass) {
    echo 'I am NOT an autoloader as I\'m not in the global namespace';
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

enum fooenum {
    public function __autoload($someclass) {
        echo 'I am NOT an autoloader as I\'m not in the global namespace';
    }
}
