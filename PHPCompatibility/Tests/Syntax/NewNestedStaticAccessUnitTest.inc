<?php

// Non-nested static access is valid cross version.
echo $foo['bar']::$baz;

// PHP 7.0: new nested static access to properties, constants and method calls.
echo $foo::$bar::$baz;
echo $foo::$bar::BAZ;
echo $foo->bar()::baz();
echo $instance->prop::MY_CONSTANT;
echo $bar::$foo['bar']::$baz;
echo $bar::$foo{'bar'}::$baz;
echo $bar::foo()['bar']::$baz;
echo $bar::foo(){'bar'}::$baz;
echo Foo::$boo::$baz;
echo self::$boo::$baz;
echo self::MY_CONSTANT[0]::$baz;
echo Foo::MY_CONSTANT[0]::$baz;
echo Bar::$bar::BAZ;

// Test code style independent sniffing.
echo $obj  ->   /* comment */ $var()
	 :: $bar; // Bad.

// PHP 7.0 change, but outside the scope of this sniff
// (dereferencing operations can now be applied to arbitrary parenthesis-expressions).
echo ($foo::$bar)::$baz;
echo ($foo->bar())::baz();

// Parse error in any PHP version. Outside the scope of this sniff.
echo $bar::($foo['bar'])::$baz;

// Still not supported.
self::MY_CONSTANT::$baz;
Foo::MY_CONSTANT::$baz;

// Issue #1016.
echo Foo::bar()::baz();
