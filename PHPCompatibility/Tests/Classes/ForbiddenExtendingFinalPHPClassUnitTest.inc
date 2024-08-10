<?php

namespace {
    // OK.
    class NotExtended {}
    class ImplementsNotExtended implements Foo {}
    class MyClass extends PHP_Incomplete_Class {}
    $anonClass = new class extends SomethingElse {};
    class NotThePHPNativeClass extends My\NS\__PHP_Incomplete_Class {}

    // Extending a final class.
    class Incomplete extends __PHP_Incomplete_Class {}
    $anonClass = new class extends \__PHP_Incomplete_Class {};
}

namespace MyNamespace {
    // OK.
    class NotExtended {}
    class ImplementsNotExtended implements Foo {}
    class MyClass extends PHP_Incomplete_Class {}
    class NotThePHPNativeClass extends \My\__PHP_Incomplete_Class {}

    // Extending a final class.
    $anonClass = new class extends \__PHP_Incomplete_Class {};
    class MyGMP extends \GMP {}
}
