<?php

class CrossVersionValid
{
    public function __toString() {
        return $this->foo;
    }

    public function someOtherFunction() {
        if ( is_int($this->foo) ) {
            throw new Exception('Foo is int');
        }
        return $this->foo;
    }
}

// Irrelevant, not the magic method.
function __toString() {
    if ( is_int($this->foo) ) {
        throw new Exception('Foo is int');
    }
    return $this->foo;
}

// Irrelevant, methods in interfaces don't have a body.
interface MyInterface {
    public function __toString();
}

// Irrelevant, abstract method doesn't have a body.
abstract class AbstractClass {
    abstract public function __toString();
}

// PHP 7.4: throwing exceptions from __toString().
class PHP74ValidClass {
    public function __toString() {
        if ( is_int($this->foo) ) {
            throw new Exception('Foo is int');
        }
        return $this->foo;
    }
}

trait PHP74ValidTrait {
    public function __toString() {
        if ( is_int($this->foo) ) {
            throw new Exception('Foo is int');
        }
        return $this->foo;
    }
}

$anon = new class() {
    public function __toString() {
        if ( is_int($this->foo) ) {
            throw new Exception('Foo is int');
        }
        return $this->foo;
    }
};

// Issue #863 Exception in try/catch.
class CaughtException {
    public function __toString() {
        try {
            throw new \LogicException('should not trigger the sniff');
        } catch (\Exception $e) {
            // ...
        }
    }
}

class CaughtAndUnCaughtException {
    public function __toString() {
        try {
            throw new \LogicException('should not trigger the sniff');
        } catch (\Exception $e) {
            // ...
            throw new \RuntimeException('should trigger the sniff');
        }

        throw $obj->methodThrowingException('should trigger the sniff');
    }
}

function IrrelevantTryCatchOutsideFunctionScope() {
    try {
        $anon = new class() {
            public function __toString() {
                if ( is_int($this->foo) ) {
                    throw new Exception('Foo is int');
                }
                return $this->foo;
            }
        }
    } catch (\Exception $e) {
        // ...
    }
}
