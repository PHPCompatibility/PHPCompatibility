<?php

function assert($something) {}

class fooclass {
    public function Assert($something) {}
}

interface foointerface {
    public function assert($something);
}

trait footrait {
    public function assert($something) {}
}

fooanonclass(new class {
    private function assert($something) {}
});

namespace ScopedNS {
	function AsSeRt($something) {}
}

class nested {
    public function hasNested($something) {
        function assert($something) {}
    }
}

$cl = function() {
    function assert($something) {}
};

enum fooenum {
    public function ASSERT($something) {}
}
