<?php
/*
 * Test calling get_class() without arg outside a class will throw a fatal error since PHP 8.0.
 */

/*
 * OK.
 */
// Not our target.
echo GET_CLASS;
$obj->get_called_class;
$obj->get_class();
$obj?->get_called_class();
MyClass::get_class();
My\Vendor\get_called_class();
#[Get_Class()]
function do_something() {}

// Passes parameter.
get_class( $obj );

function globalFunction() {
    get_class($obj);
}

$closure = function() {
    \Get_Class($obj);
};

$arrow = fn() => get_class($obj);

// Will be executed within class context.
class NestedFunctionsWillBeExecutedInGlobalScopeC {
    public function inMethod() {
        get_class();
        get_called_class();
    }
}

trait NestedFunctionsWillBeExecutedInGlobalScopeT {
    public function inMethod() {
        get_class();
        \get_called_class();
    }
}

$anon = new class {
    public function inMethod() {
        get_class();
        get_called_class();
    }
};

enum NestedFunctionsWillBeExecutedInGlobalScopeE {
    public function inMethod() {
        \get_class();
        get_called_class();
    }
}

class CallablesWillBeExecutedInsideClassContextWhenCalledFromOutside {
    public function inClosure() {
        $callable = function() {
            echo get_called_class();
            return get_class();
        };
        return $callable;
    }

    public function inArrow() {
        $callable = fn() => get_called_class();
        return fn() => get_class();
    }
}

// Not allowed since PHP 7.2, but not the concern of this sniff.
get_class(null);

/*
 * PHP 8.0: calling the functions without args from outside OO scope.
 */
get_class();
get_called_class();

function anotherGlobalFunction() {
    \get_class();
    \get_called_class();

    $callable = function() {
         return get_class();
    };

    return fn() => Get_Called_Class();
}

$closure = function() {
    get_class();
    get_called_class();
};

$arrow = fn() => get_class();
$arrow = fn() => get_called_class();

class NestedFunctionsWillBeExecutedInGlobalScopeC {
    public function method() {
        function globalFunctionNestedInMethodC() {
            GET_CLASS();
            GET_called_CLASS();
        }
    }
}

$anon = new class {
    public function method() {
        function globalFunctionNestedInMethodA() {
            \get_class();
            \get_called_class();
        }
    }
};

trait NestedFunctionsWillBeExecutedInGlobalScopeT {
    public function method() {
        function globalFunctionNestedInMethodT() {
            \get_class();
            \get_called_class();
        }
    }
}

enum NestedFunctionsWillBeExecutedInGlobalScopeE {
    public function method() {
        function globalFunctionNestedInMethodE() {
            get_class();
            get_called_class();
        }
    }
}
