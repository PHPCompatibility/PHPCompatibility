<?php

// Invalid, but not the concern of this sniff.
echo parent::class;

function test() {
    echo parent::class;
}

// Valid use of the parent keyword.
class ExtendedClass extends ParentClass
{
    public function test(parent $param): parent {
        echo parent::class;
        $this->foo = parent::$foo;
        return parent::test();
    }
}

class PlainClass {
    public function create_anon_class() {
        $anon = new class() extends ParentClass {
            public function test(parent $param): parent {
                echo parent::class;
                $this->foo = parent::$foo;
                return parent::test();
            }
        };
    }
}

// PHP 7.4: Deprecated parent in class without parent.
class ParentClass
{
    public function test(parent $param) {
        echo parent::class;
        $this->foo = parent::$foo;
        return parent::test();
    }
}

class ImplementedClass implements SomeInterface
{
    public function test(): parent {
        echo parent::class;
        $this->foo = parent::$foo;
        return parent::test();
    }
}

// Test correct handling of nested classes.
class NestingStuff extends Nested {
    public function create_anon_class() {
        return new class() {
            public function test(parent|SomeInterface $param) {
                echo parent::class;
                $this->foo = parent::$foo;
                return parent::test();
            }
        };
    }
}

// Test handling of incorrectly tokenized parent keywords. See PHPCS #3797.
class PHP4DOMTree {
    function &parent() {
        $parent =& PHP4DOMTree::from_DOMDocument($this->_element->parent());
        $global_parent = parent($var);
    }

    function flagMe() {
        $obj = new parent;
        return new parent();
    }
}

// PHP 7.4 Deprecated parent in class without parent also applies to interfaces, with and without parent.
// See: https://3v4l.org/7OT7b
interface NoParent {
    public function testA(parent $obj);
    public function testB(): parent;
    public function parent();
}

interface HasParent extends NoParent {
    public function testA(parent $obj);
    public function testB(): parent;
}

// Intentional parse error. This has to be the last test in the file.
class SomeClass extends Something
    public function test() {
        return parent::test();
    }
