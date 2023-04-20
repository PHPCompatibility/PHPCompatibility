<?php

abstract class CrossVersionValid
{
    abstract public function publicOverload();
    abstract protected static function protectedOverload();
    private function privateNonOverloadable() {}
}

trait CrossVersionValidTrait
{
    abstract public static function publicOverload();
    abstract protected function protectedOverload();
    private function privateNonOverloadable() {}
}

// Abstract methods can not be declared in an anonymous class as the abstract keyword
// will not be accepted for the class, but that is not our concern.
$anon = new class() {
    abstract public static function publicOverload();
    abstract protected function protectedOverload();
    private function privateNonOverloadable() {}
};

// PHP 5.1: abstract functions can not be private.
abstract class CrossVersionInValid
{
    abstract private function privateOverload();
    static private abstract function privateStaticOverload();
}

$anon = new class() {
    abstract private function privateOverload();
    static private abstract function privateStaticOverload();
};

/*
 * PHP 8.0: abstract private methods in traits.
 */
trait CrossVersionInValidTrait
{
    abstract private function privateOverload();
    static private abstract function privateStaticOverload();
}

// Global function cannot have abstract nor private keyword.
static function globalFunction() {}

// Interface cannot have private methods, nor abstract methods, but that's not the concern of this sniff.
interface IllegalPrivate {
    abstract private function privateToImplement();
}
