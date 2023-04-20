<?php

// This is fine.
$obj->notToString($param);
$obj->__toString();
$obj::__toString();
MyClass::__toString(
	// Comment.
);

class MyClass {
    public function foo() {
        self::__toString();
        static::__toString();
        parent::__toString();
        $this->__toString();
        $another->__toString( /* Comment */ );
    }
}

// Irrelevant, not the magic method.
echo __toString($param);
echo Some\NameSp\__toString($param);
echo namespace\__toString($param);
MyClass::__TOSTRING; // Constant, not method.
$obj->__tostring; // Property, not method.

class Bar {
	public function foo() {
        $this->__toString; // Property.
        self::$__toString; // Property.
        self::__toString; // Constant.
	}
}

// PHP 5.3: The __toString() magic method can no longer accept arguments.
$obj->__toString($param);
$obj::__toString($param);
MyClass::__toString($param);

class MyClass {
    public function foo() {
		// Includes testing case-insensitivity as function names in PHP are (case-insensitive).
        self::__toString($param);
        static::__tostring($param);
        parent::__toSTRING($param);
        $this->__toString($param);
        $another->__toString($param);
    }
}

$obj?->__toString($param);

// Ignore PHP 8.1 first class callable syntax as undetermined as it is unknown whether params will be passed.
register_callback($obj->__toString(...));
