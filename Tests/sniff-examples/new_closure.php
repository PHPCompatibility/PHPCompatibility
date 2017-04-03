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
}

// PHP 5.4+: Static closures.
static function() {
    var_dump($something);
}
