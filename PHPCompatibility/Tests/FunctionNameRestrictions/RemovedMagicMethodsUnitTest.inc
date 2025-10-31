<?php

// Not sniff's target: using similar tokens or syntax
// @see RemovedMagicMethodsUnitTest::dataNoFalsePositives
$function = __sleep();
function foo($__sleep) {};
function() use ($__sleep) {};

// Not sniff's target: excluded from certain contexts
// @see RemovedMagicMethodsUnitTest::dataSoftDeprecations
// @see RemovedMagicMethodsUnitTest::dataNoViolationsOnValidVersion
function __sleep() {} // bare functions are not magic methods
function __wakeup() {} // bare functions are not magic methods

interface DeclaredInInterface // only implementations are impacted
{
    function __sleep();
    function __wakeup();
}

class MutuallyExclusiveMethods // disregarded when another method is already overriding our target method
{
    function __sleep() {} // __serialize is already present
    function __wakeup() {} // __unserialize is already present
    function __serialize() {}
    function __unserialize(array $data) {}
}

class DeclaredInNested // don't act like magic methods when nested in functions/methods
{
    function nested()
    {
        function __sleep() {}
        function __wakeup() {}
    }
}

enum DeclaredInEnum { // don't act like magic methods in enums
    public function __sleep() {}
    public function __wakeup() {}
}

// Sniff's target: deprecated/removed in specific versions
// @see RemovedMagicMethodsUnitTest::testSoftDeprecations
class DeclaredInClass
{
    function __sleep() {} // soft-deprecated since PHP 8.5
    function __wakeup() {} // soft-deprecated since PHP 8.5
}

abstract class DeclaredInAbstractClass
{
    function __sleep() {} // soft-deprecated since PHP 8.5
    function __wakeup() {} // soft-deprecated since PHP 8.5
}

trait DeclaredInTrait
{
    function __sleep() {} // soft-deprecated since PHP 8.5
    function __wakeup() {} // soft-deprecated since PHP 8.5
}

$DeclaredInAnonymousClass = new class {
    function __sleep() {} // soft-deprecated since PHP 8.5
    function __wakeup() {} // soft-deprecated since PHP 8.5
};

// Parse error / live coding
// @see RemovedMagicMethodsUnitTest::dataNoFalsePositives
class Foo
{
    function __sleep(
}
