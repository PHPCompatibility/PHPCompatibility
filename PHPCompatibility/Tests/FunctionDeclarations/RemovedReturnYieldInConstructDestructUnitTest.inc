<?php

/*
 * OK.
 */

// Global functions, not OO constructor/destructor.
function __construct() : int {
    return 123;
}

function __destruct() {
    yield 321;
}

// No methods.
$anon = new class() {
    const FOO = 10;
};

// Methods which are not constructors/destructors can be disregarded.
class NotAConstructor {
    public function __set($name, $value) {
        // Do something.
        return $success;
    }

    public function Foo(): int {
        // Do something.
        return false;
    }

    public function Bar() {
        return $this;
    }
}

// Abstract Constructors/Destructors don't have a body, so can be skipped.
abstract class AbstractMethods {
    abstract protected function __construct();
    abstract protected function __destruct();
}

// PHP 4 style constructors are not regarded as constructors since PHP 8.0, so should not be flagged for the PHP 8.6 deprecation.
class PHP4StyleConstructorShouldBeIgnored {
    public function PHP4StyleConstructorShouldBeIgnored() {
        // Do something.
        return $success;
    }
}

// Constructors/Destructors without a return statement are fine.
class NoReturn {
    protected function __construct()
    {
        do_something();
    }

    protected function __destruct()
    {
        do_something();
    }
}

// Constructors/Destructors with a return statement, but without a value, are fine.
$anon = new class extends ReturnNoValue {
    public function __construct() {
        if ($foo) {
            return   ;
        } else {
            return /* comments are fine */
            // Even when spread over multiple lines.
            ;
        }

        return;
    }

    public function __destruct() {
        do_something();
        return;
    }
};

// Early return without a value is also fine.
trait EarlyReturnIsStillAllowed
{
    public function __construct()
    {
        if (random_int(0, 1)) {
            return; // Skipping the rest of the logic remains legal.
        }

        echo "Constructing", PHP_EOL;
    }

    public function __destruct()
    {
        if (random_int(0, 1)) {
            return; // Skipping the rest of the logic remains legal.
        }

        echo "Destructing", PHP_EOL;
    }
}

// Prevent false positives for nested functions/closures.
class NestedFunctions {
    public function __construct() {
        function global_function() {
            return true;
        }
    }

    public function __destruct() {
        function global_function($foo) {
            return $foo;
        }
    }
}

class NestedClosures {
    public function __construct() {
        do_something(
            function () {
                return true;
            },
        );
    }

    public function __destruct() {
        $this->callback = function () {
            return 10;
        };
    }
}

$anon = new class extends NestedArrowFunctions {
    public function __construct() {
        do_something(
            fn() : int => yield from [1, 2, 3],
        );
    }

    public function __destruct() {
        $this->callback = fn() : int => yield 10;
    }
};

class NestedAnonClass {
    public function __construct() {
        do_something(
            new class () {
                public function foo() {
                    return true;
                }
            },
        );
    }

    public function __destruct() {
        $this->anonClass = new class() extends Foo {
            private function dataProvider() {
                yield 10;
                yield 20;
            }
        };
    }
}


/*
 * PHP 8.6: returning from a constructor or destructor is deprecated.
 */
class ReturnFromConstructDestruct
{
    public function __construct()
    {
        if ($foo) {
            return 123 ?>
        <?php
        } else {
            return 456;
        }
    }

    public function __destruct()
    {
        if ($foo) {
            return 123;
        }

        return 456;
    }
}

trait YieldInConstructDestruct
{
    public function __construct()
    {
        yield 123;
        yield 456;
    }

    public function __destruct()
    {
        yield 123;
        yield 456;
    }
}

$anonClass = new class extends YieldFromInConstructDestruct
{
    public function __construct()
    {
        yield from [3, 4];
        yield /*comment*/ from [3, 4];
    }

    public function __destruct()
    {
        yield    from [3, 4];
        yield

        from [3, 4];
    }
};
