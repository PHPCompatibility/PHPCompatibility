<?php

spl_autoload_register( function ( $class ) {
} );

function something() {}

// PHP 5.4+,
class Test
{
    // PHP 5.4+: Static closures.
    public function testStatic()
    {
        return static function() {
            echo 'static closure';
        };
    }

    // PHP 5.4+: Using this in a class context.
    public function testThis()
    {
        return function() {
            var_dump($this);
            var_dump($this); // Let's make sure we get an error for each line using $this.
        };
    }

    // $this is not available if the closure is declared as static.
    public function testThisStatic()
    {
        return static function() {
            var_dump($this);
            var_dump($this); // Let's make sure we get an error for each line using $this.
        };
    }

    // Valid: using a variable - not $this - in a static closure.
    public function testVarStatic()
    {
        return static function() {
            var_dump($var);
        };
    }
}

// PHP 5.4+: Static closures.
static function() {
    var_dump($something);
}

// Warning: Using $this outside of a class context.
$closure = function() {
   var_dump($this);
}
$foo = new stdClass();
$closure = $closure->bindTo($foo);

// Valid: using a variable - not $this - in a closure.
function() {
   var_dump($something);
}
