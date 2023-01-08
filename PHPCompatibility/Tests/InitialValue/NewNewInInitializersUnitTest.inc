<?php

/*
 * Valid cross-version.
 */
const NON_OO_CONST = Foo::CONST_NAME;

function containsStaticVars() {
	static $var = Foo::class, $bar = 'static value';
	static $boo = [];
}

function bar($a, $b = (10 + 5)) {}
$cl = function ($a = true) {};
$ar = fn (Foo $a = null) => $a;


/*
 * Not our targets.
 */
$closure = static function() { return new Foo; };
$fn = static fn() => new Foo;

class Something {
	public function foo() {
		$bool = new Foo() instanceof static;
	}
}

$var = new Foo();


/*
 * PHP 8.1 new in initializers.
 */

// Non-OO constants declared using the `const` keyword.
const NON_OO_CONST = new Foo();
const NON_OO_CONST_LONG_ARRAY = [new Foo()];
const NON_OO_CONST_SHORT_ARRAY = array(new Foo());

// Static variable declarations.
function containsStaticVars() {
	static $var = new Foo(1), $bar = new \Fully\Qualified('static value'); // x2.
	static $boo = [new Partially\Qualified()];
}

// Default values for function declarations in all forms.
function bar($a, $b = new Foo()) {}
$array = array(
	'k1' => function ($a, $b = new Foo()) {},
	'k2' => function ($a, $b = array(new Foo())) {},
	'k3' => function ($a, $b = [new Foo()]) {},
	'k4' => function ($a, $b = array(array(new Foo()))) {},
);
$ar = fn (Foo $a = new Foo()) => $a;

class ClassMethodsCanUseNew extends Something {
    public function __construct(
		public Foo $constructorProp = new Foo(),
		$normalVar = new self(x: 2),
	) {}

    private function bar($a = new parent()) {}
}

$anon = new class {
    public function __construct(
		public Foo $constructorProp = new Foo(),
		$normalVar = new Bar(),
	) {}

    private function bar($a = new Foo()) {}
};

interface InterfaceMethodsCanUseNew {
    public function __construct(
		$normalVar = new Bar,
	);

    public function bar($a = new Foo());
}

trait TraitMethodsCanUseNew {
    public function __construct(
		public Foo $constructorProp = new Foo,
		$normalVar = new Bar(),
	) {}

    private function bar($a = new Foo()) {}
}

enum EnumMethodsCanUseNew: string {
	case Example = 'test';
    private function bar($a = new Foo) {}
}


/*
 * Still not supported. Flag anyway.
 */
function bar($a, $b = new Foo($bar)) {}
$cl = function ($a = new namespace\Foo(function_call())) {};

function NewUsingUnsupportedArguments(
    $c = new A(...[]), // argument unpacking
    $d = new B($abc), // unsupported constant expression
) {}


/*
 * Still not supported. Ignore.
 */
// `new` is not supported in OO constants or properties.
class StillNotSupported {
    const MINE = new Foo();
    public $prop = new Foo();
}

$anon = new class {
    const MINE = new Foo();
    public $prop = new Foo();
};

interface InterfaceStillNotSupported {
    const MINE = new Foo();
}

trait TraitStillNotSupported {
    const MINE = new Foo;
    public $prop = new Foo();
}

enum EnumStillNotSupported: string {
    const MINE = new Foo;
}

// `new` using a dynamic of non-string class name or anonymous class is not allowed.
function DynamicClassNameNotAllowed(
    $a = new (CLASS_NAME_CONSTANT)(), // dynamic class name
    $b = new $className(), // dynamic class name
    $c = new class {}, // anonymous class
    $d = new static() {}, // dynamic class name
) {}

// New at wrong nesting level, but function calls are not supported, so ignore.
$cl = function ($a = function_call(new namespace\Foo)) {};
