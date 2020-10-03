<?php

/*
 * OK no change in behaviour cross-version.
 */
class CrossVersionValid
{
    function __construct() {
        throw new Exception();
    }

    function __destruct() {
        // Destructor will not be called on thrown exception in constructor.
    }
}

$anon = new class() {
    function __Construct() {
        throw new Exception();
    }

    function __Destruct() {
        // Destructor will not be called on thrown exception in constructor.
    }
};

/*
 * PHP 8.0: If an object constructor exit()s, the object destructor will no longer be called.
 */
class CrossVersionInValid
{
    public function __construct() {
        exit(1); // Error.
    }

    public function __destruct() {
        // Destructor will not be called on exit() in constructor.
    }
}

$anon = new class() extends ClassWhichMayOrMayNotContainADestructMethod {
    function __CONSTRUCT() {
        if ($something) {
            die(); // Warning.
        } else {
            exit(2); // Warning.
        }
    }
};

abstract class HasDestructAndExtends extends ClassWhichMayOrMayNotContainADestructMethod
{
    public function __construct() {
        // Test skipping over irrelevant code.
        $array = [
            'closure' => function() { exit; }, // Ignore, nested closed scope.
        ];

        $anon = new class {
            function something() {
                exit; // Ignore, nested closed scope.
            }
        };

        $array = array(
            'closure' => function() { exit; }, // Ignore, nested closed scope.
        );

        die; // Error.
    }

    /**
     * This should be easily skipped over.
     */
    abstract function something();

    public function __DeStruct() {
        // Destructor will not be called on exit() in constructor.
    }
}

trait CrossVersionInValid
{
    public function __construct() {
        exit(1); // Error.
    }

    public function __destruct() {
        // Destructor will not be called on exit() in constructor.
    }
}

class DoesntExtendAndDoesntHaveDestructMethodButUsesTrait {
    use TraitWhichMayOrMayNotContainADestructMethod;

    public function __construct() {
        die(1); // Warning.
    }
}

/*
 * Prevent false positives.
 */
class DoesntExtendAndDoesntHaveDestructMethod {
    public function __construct() {
        exit(1);
    }
}

trait CantExtendAndDoesntHaveDestructMethod {
    public function __construct() {
        exit(1);
    }
}

class ExitNotInFunctionScope {
    public function __construct() {
        $this->property = function($param) {
            exit(1);
        };
    }

    public function __destruct() {}
}
