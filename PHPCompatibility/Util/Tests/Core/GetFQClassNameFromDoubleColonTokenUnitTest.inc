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
	DateTime::static_function();

	class MyClass {
		function test {
			/* test 13 */
			echo self::CONSTANT;
			/* test 14 */
			echo parent::$static_property;
			/* test 15 */
			static::test_function();
		}
	}
}


class MyClass {
	function test {
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
