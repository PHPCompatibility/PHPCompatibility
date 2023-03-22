<?php

spl_autoload_register( function ( $class ) {
} );

function something() {}

// PHP 5.4+.
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
};
$foo = new stdClass();
$closure = $closure->bindTo($foo);

// Valid: using a variable - not $this - in a closure.
function() {
   var_dump($something);
};

// Make sure $this in nested closures does not get reported twice.
class NestedClosures {
	public function testIt() {
		$a = function() {
			return function() {
				return $this;
			};
		}
	}
}

// More PHP 5.4+: using self/parent/static in class context.
class SelfTest
{
	const ABC = '';

    // PHP 5.4+: Using self/parent/static in a class context.
    public function testThis()
    {
        return function() {
            var_dump(SELF::ABC);
            var_dump(self::ABC); // Let's make sure we get an error for each line using self.
            var_dump(parent::ABC);
            var_dump(static::ABC);

            static $a = 'abc';
            static function() {};
        };
    }
}

// Issue #1481 $this in anonymous class in closure.
$closure = function() {
    $obj = new class() {
        public function doSomething(): int {
            var_dump(self::ABC);
            var_dump(static::ABC);

            return $this->x;
        }
    };
    return $obj;
};

// Variation on #1481, any closed structure can be declared within a closure... see https://3v4l.org/Zb22K#veol
$closure = function() {
    class named {
        public function doSomething(static $x): static {
            var_dump(self::ABC);
            return $this->x;
        }
    }
    $a = new named();
    return $a;
};
