<?php

/*
 * OK.
 */
// Not our target.
function __debugInfo() {}

class DebuggableClass {
    public function __debugInfo() {}
}

interface DebuggableInterface {
    public function __debugInfo();
}

trait DebuggableTrait {
    public function __debugInfo() {}
}

$anonClass = new class {
    public function __debugInfo() {}
};

enum NoMethods {
    case Foo;
    case Bar;
}

// Prior to PHP 8.6, the `__call()`, `__callStatic()`, and `__invoke()` methods were already allowed on enums.
enum NonDebuggableEnum
{
    case Foo;
    case Bar;

    public function __call($name, $arguments) {}
    public static function __callStatic($name, $arguments) {}
    public function __invoke($x) {}
}


/*
 * PHP 8.6: Debuggable enums.
 */
enum DebuggableEnum {
    case Bar = "Baz";

    public function __debugInfo() {
        return [__CLASS__ . '::' . $this->name . ' = ' . $this->value];
    }
}

enum BackedDebuggableEnum: string {
    case Bar = "Baz";

    public function __debugINFO() {}
}
