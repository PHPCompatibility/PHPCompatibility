<?php

/*
 * These are not calls to the __clone() magic method.
 */
$a = __clone();
BadPracticeClassConstant::__CLONE;
$propertyAccess = $obj->__clone;
BadPracticeClassProp::$__CLONE;
$notclone = $obj->_clone();
$notclone = $obj->cloning();

class ABC {
    function __clone() {}
}

/*
 * These should be flagged.
 */
class DEF {
    function something()
    {
		$clone = self::__clone();
		$clone = static::__Clone();
		$clone = parent::__CLONE();
    }
}

$a = (new ABC()) ->  /* comment */ __clone();
StaticClass::__clone();
