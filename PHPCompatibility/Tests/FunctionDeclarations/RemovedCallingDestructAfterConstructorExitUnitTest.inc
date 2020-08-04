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
    function __construct() {
        exit(1);
    }

    function __destruct() {
        // Destructor will not be called on exit() in constructor.
    }
}

$anon = new class() {
    function __CONSTRUCT() {
        if ($something) {
            die();
        } else {
            exit(2);
        }
    }
};
