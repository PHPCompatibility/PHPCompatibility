<?php

/*
 * This is fine pre-PHP 8.4.
 */
$a = new stdClass();
$b = $a->foo();

$a = new Bar;
$a = new \Bar();
$a = (new Bar());

// Dereferencing the expression when wrapped in parentheses is already supported since PHP 5.4 - see the ClassMemberAccess sniff.
$a = (new foo())->bar();
$a = (new $foo())->bar;
$a = (new namespace\foo)[0];

// This is already supported since PHP 7.0 - see the ClassMemberAccess sniff.
$b = ($a = new foo())->bar();

// Member access for anonymous classes with parentheses wrappers has been supported since their introduction in PHP 7.0.
var_dump(
    (new class { const CONSTANT = 'constant'; })::CONSTANT,
    (new class { public static $staticProperty = 'staticProperty'; })::$staticProperty,
    (new readonly class { public static function staticMethod() { return 'staticMethod'; } })::staticMethod(),
    (new class { public $property = 'property'; })->property,
    (new class { public function method() { return 'method'; } })->method(),
    (new class { public function __invoke() { return '__invoke'; } })(),
    (new readonly class (['value']) extends ArrayObject {})[0],
    isset((new #[SomeAttribute] #[AnotherAttribute] class (['value']) extends ArrayObject {})[0])
);

// Nullsafe object operator is also supported since its introduction in PHP 8.0.
var_dump((new Partially\Qualified\Bar)?->y);

// Some more variations of code the sniff could get confused over.
function_call( new Bar(), $b->something, $c->method(), $d['a']);
$container->register('foo', 'FooClass')->addArgument(new \stdClass())->setPublic(true);
$request = (new Request())->withMethod('GET')->withUri('/hello-world');
$a = $request->wantsJson() ? new JsonResponse($a, $b) : back()->with($status, trans($this->status));

// Omitting the wrapping parentheses is still not allowed when there are no constructor argument parentheses.
$a = new foo->bar();
$X = new foo->setX(10)->getX();
new A::test();

// The behaviour for the following syntaxes is unchanged. These should be ignored by the sniff.
$a = new MyClass::CONSTANT;         // will continue to throw a syntax error.
$a = new $myClass::CONSTANT;        // will continue to throw a syntax error.
$a = new MyClass::$staticProperty;  // will continue to work as `new (MyClass::$staticProperty)`.
$a = new $myClass::$staticProperty; // will continue to work as `new ($myClass::$staticProperty)`.
$a = new $myObject->property;       // will continue to work as `new ($myObject->property)`.
$a = new $myObject?->property;      // will continue to work as `new ($myObject?->property)`.
$a = new MyArrayConst['class'];     // will continue to throw a syntax error.
$a = new $myArray['class'];         // will continue to work as `new ($myArray['class'])`.
$a = new (Something()));            // will call the global function `Something()` and will create an instance
                                    // for the class name returned by `Something()`.

// Rightfully (still) not allowed:
new ArrayObject() = 1;
unset(new ArrayObject());


/*
 * PHP 8.4: class member access on new expression without wrapping parentheses (but with constructor argument parentheses).
 */
$a = [
    new \Fully\Qualified\MyClass()::CONSTANT,
    new MyClass()::{'CONSTANT'},
    new Partially\Qualified\MyClass()::$staticProperty,
    new MyClass()::staticMethod(),
    new MyClass()->property,
    new namespace\MyClass()->method(),
    new MyClass()?->property,
    new \MyClass()?->method(),
    new MyClass()(),
    $condition ? new MyClass()['key'] : false,
    new MyClass(['value'])[0],
];

var_dump(
    new $myClass()::CONSTANT,
    new ${$a}[1]()::{'CONSTANT'},
    new $myClass()::$staticProperty,
    new ${'myClass'}()::staticMethod(),
    new $myClass()->property,
    new $myClass()->method(),
    new ${$myClass[1]}()?->property,
    new $$myClass()?->method(),
    new $myClass()(),
    new ${$myClass}()['key'],
    new $myClass(['value'])[0],
);

$a = new (trim(' MyClass '))()::CONSTANT;
$a = new (trim(' MyClass '))()::{'CONSTANT'};
$a = new (trim(' MyClass '))()::$staticProperty;
$a = new (trim(' MyClass '))()::staticMethod();
$a = new (trim(' MyClass '))()->property;
$a = new (trim(' MyClass '))()->method();
$a = new (trim(' MyClass '))()?->property;
$a = new (trim(' MyClass '))()?->method();
$a = new (trim(' MyClass '))()();
$a = new (trim(' MyClass '))()['key'];
$a = new (trim(' MyClass '))(['value'])[0];

// For anonymous classes, the constructor argument parentheses are not required.
var_dump(
    new class { const CONSTANT = 'constant'; }::CONSTANT,
    new readonly class { public static $staticProperty = 'staticProperty'; }::$staticProperty,
    new class { public static function staticMethod() { return 'staticMethod'; } }::staticMethod(),
    new class { public $property = 'property'; }->property,
    new #[SomeAttribute] #[AnotherAttribute] class { public function method() { return 'method'; } }->method(),
    new class { public function __invoke() { return '__invoke'; } }(),
    new readonly class (['value']) extends ArrayObject {}['key'],
    isset(new class implements ArrayAccess {}[0])
);

// Parse error test.
$a = new (trim(' MyClass ');

// Parse error test.
// This must be the last test in the file.
$a = new
