<?php

DateTime::CONSTANT;
DateTime::$static_property;
DateTime::static_function();
\DateTime::static_function();
namespace\DateTime::static_function();
AnotherNS\DateTime::static_function();
\FQNS\DateTime::static_function();
$var = (DateTime::$static_property);
$var = (5+AnotherNS\DateTime::$static_property);

namespace Testing {
	DateTime::CONSTANT;
	DateTime::$static_property;
	DateTime::static_function();
	
	class MyClass {
		function test {
			echo self::CONSTANT;
			echo parent::$static_property;
			static::test_function();
		}
	}
}

class MyClass {
	function test {
		echo self::CONSTANT;
		echo parent::$static_property;
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
$theclass::bar(42);
