<?php

class MutuallyExclusiveMethods
{
    function __sleep() {} // no violation because __serialize is present, so __sleep is already not called
    function __wakeup() {} // no violation because __unserialize is present, so __wakeup is already not called
    function __serialize() {}
    function __unserialize(array $data) {}
}

class DeclaredInClass
{
    function __sleep() {} // violation
    function __wakeup() {} // violation
}

trait DeclaredInTrait
{
    function __sleep() {} // violation
    function __wakeup() {} // violation
}

interface DeclaredInInterface
{
    function __sleep(); // no violation
    function __wakeup(); // no violation
}

$DeclaredInAnonymousClass = new class {
    function __sleep() {} // violation
    function __wakeup() {} // violation
};

class DeclaredInNested
{
    function nested()
    {
        function __sleep() {} // no violation because not a valid magic method in this context
        function __wakeup() {} // no violation because not a valid magic method in this context
    }
}

enum DeclaredInEnum {
    public function __sleep() {} // no violation because not a valid magic method in this context
    public function __wakeup() {} // no violation because not a valid magic method in this context
}
