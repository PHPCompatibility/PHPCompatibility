<?php

/* test 1 */
DateTime::CONSTANT;
/* test 2 */
DateTime::$static_property;
/* test 3 */
DateTime::static_function();
/* test 4 */
\DateTime::static_function();
/* test 5 */
namespace\DateTime::static_function();
/* test 6 */
AnotherNS\DateTime::static_function();
/* test 7 */
\FQNS\DateTime::static_function();
/* test 8 */
$var = (DateTime::$static_property);
/* test 9 */
$var = (5+AnotherNS\DateTime::$static_property);


namespace Testing {
    /* test 10 */
    DateTime::CONSTANT;
    /* test 11 */
    DateTime::$static_property;
    /* test 12 */
    namespace\DateTime::static_function();

    class MyClass extends Foo {
        function test() {
            /* test 13 */
            echo self::CONSTANT;
            /* test 14 */
            echo parent::$static_property;
            /* test 15 */
            static::test_function();
        }
    }
}


class MyClass extends Bar {
    function test() {
        /* test 16 */
        echo self::CONSTANT;
        /* test 17 */
        echo parent::$static_property;
        /* test 18 */
        static::test_function();
    }
}

// Issue #205
class Foo {
    static public function bar($a) {
        echo __METHOD__ . '() called with $a = ' . $a;
    }
}
$theclass = 'Foo';
/* test 19 */
$theclass::bar(42);

/* test 20 */
// Intentional fatal error / cannot access self when no class scope is active.
class Unrelated {}
self::OUTSIDE_CLASS;

$anon = new class {
    public function foo() {
        /* test 21 */
        self::INSIDE_ANON_CLASS;
    }
};

trait TraitNameIsNotClassName {
    public function foo() {
        /* test 22 */
        self::INSIDE_TRAIT;
    }
}

interface HasConstantScalarDeclaration {
    public CONST_A = 10;
    /* test 23 */
    public CONST_B = self::CONST_A + 10;
}

/* test 24 */
\DateTime
    /*comment*/
        ::comment_tolerance();

$anon = new readonly class {
    public function foo() {
        /* test 25 */
        self::INSIDE_READONLY_ANON_CLASS;
    }
};
