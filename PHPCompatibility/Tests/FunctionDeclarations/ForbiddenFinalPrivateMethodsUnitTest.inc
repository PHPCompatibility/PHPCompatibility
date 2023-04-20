<?php

/*
 * OK cross version.
 */
class CrossVersionValid
{
    private final function __construct() {}
    final public function publicFinal() {}
    final protected static function protectedFinal() {}
    private function privateNonOverloadable() {}
}

trait CrossVersionValidTrait
{
    final private function __CONSTRUCT() {}
    final public static function publicFinal() {}
    final protected function protectedFinal() {}
    private function privateStillOverloadable() {} // Open question in RFC PR https://github.com/php/php-src/pull/5401
}

$anon = new class() {
    final private function __Construct() {}
    final public static function publicFinal();
    final protected function protectedFinal();
    private function privateNonOverloadable() {}
};

/*
 * PHP 8.0: private methods cannot be final as they are never overridden by other classes.
 */
class CrossVersionInValid
{
    final private function privateFinal();
    static private final function privateStaticFinal();
}

$anon = new class() {
    final private function privateFinal();
    static final private function privateStaticFinal();
};

trait CrossVersionInValidTrait
{
    final private function privateFinal();
    static private final function privateStaticFinal();
}

// Global function cannot have final nor private keyword.
static function globalFunction() {}

// Interface cannot have private methods, nor final methods, but that's not the concern of this sniff.
interface IllegalPrivate {
    final private function privateToImplement();
}

// Safeguard handling of PHP 8.1+ enums.
enum MyEnum {
    final public static function publicFinal();
    final private function privateFinal() {}
}
