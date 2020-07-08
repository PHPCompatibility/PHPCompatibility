<?php

// OK: Non-typed properties.
class PHP73Example {
    public $public;
    protected /* comment */ $protected;
    private static $private;
    static public $publicStatic;
    var $oldStyle;

    // Intentional parse error.
    $invalidProperty;

    // Typed function parameter, not property.
    public function method(?int $param) {
        $localVar = 'abc';
    }
}

// PHP 7.4 typed properies.
class PHP74Example {
    // All types with the exception of "void" and "callable" are supported
    public int $scalarType;
    protected ClassName $classType;
    private ?ClassName $nullableClassType;

    // Types are also legal on static properties
    public static Iterable $staticProp;

    // Types can also be used with the "var" notation
    var bool $flag;

    // Typed properties may have default values (more below)
    public string $str = "foo";
    public ?STRING $nullableStr = null;

    // The type applies to all properties in one declaration
    public float $a, $b;

    // Additional tests not from the RFC.
    public int $x = 10,
        /**
         * Docblock
         */
        $y = 5,
        // comment.
        $z = 15;

    protected \MyNamespace\InterfaceName $namespacedInterfaceType;

    private static self $instance;

    // Note: The RFC does not explicitely mention this syntax.
    static bool $bool = true;

    // Test reversed keyword order.
    static public inT $int = 0;
}

// Invalid property types.
class InvalidExample {
    public void $invalidType;
    protected Callable $callable = 'strlen';
    private ?callable $nullableCallable = null;
    public boolean $booleanType;
    protected integer $integerType = 123;
}

// Mixed type declaration - PHP 8.0+.
class PHP8Mixed {
    public static mixed $mixed;

    // Invalid: Nullable mixed type declaration.
    private ?miXed $nullableMixed;
}

// PHP 8.0 union types.
$anon = class() {
    public int|float $unionTypeSimple;
    private MyClassA|\Package\MyClassB $unionTypesTwoClasses;
    protected array|bool|int|float|NULL|object|string $unionTypesAllBaseTypes;

    // Intentional fatal error - mixing types which cannot be combined, but that's not the concern of the sniff.
    var false|mixed|self|parent|iterable|Resource $unionTypesAllPseudoTypes;

    // Intentional fatal error - types which are not allowed for properties, but that's not the concern of the sniff.
    public callable|static|void $unionTypesIllegalTypes;

    // Intentional fatal error - nullability is not allowed with union types, but that's not the concern of the sniff.
    public ?int|float $unionTypesNullable;

    // Intentional fatal error - null pseudotype is only allowed in union types.
    public null $pseudoTypeNull;

    // Intentional fatal error - false pseudotype is only allowed in union types.
    public false $pseudoTypeFalse;

    // Intentional fatal error - false pseudotype is not allowed in combination with bool, but that's not the concern of the sniff.
    public bool|FALSE $pseudoTypeFalseAndBool;

    // Intentional fatal error - object is not allowed in combination with class name, but that's not the concern of the sniff.
    public object|ClassName $objectAndClass;

    // Intentional fatal error - iterable pseudotype is not allowed in combination with array or Traversable, but that's not the concern of the sniff.
    public iterable|array|Traversable $pseudoTypeIterableAndArray;

    // Intentional fatal error - duplicate types are not allowed in union types, but that's not the concern of the sniff.
    public int|string|INT $duplicateTypeInUnion;
};
