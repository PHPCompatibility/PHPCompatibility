<?php

// OK: non-OO constants can still not be typed.
const GLOBAL_CONSTANT = 10;

// OK: Non-typed constants.
class PHP82Example {
    public const PUBLIC_CONST = 'public';
    protected /* comment */ const PROTECTED_CONST = 'protected';
    private final const PRIVATE_FINAL = 'private';
    final public const FINAL_PUBLIC = 'final public';

    // Intentional parse error.
    const NAME_WITHOUT_VALUE;
}

// OK: constant names mirroring reserved keywords which can be used in types, but are not types.
interface NotTypedConstants {
    const TRUE = true;
    const array = array();
}

// PHP 8.3 typed constants.
class PHP83Example {
    // All types with the exception of "void", "never" and "callable" are supported.
    public const int SCALAR_TYPE = 10;
    protected const ClassName CLASS_TYPE = new ClassName();
    private const ?ClassName NULLABLE_CLASS_TYPE = null;
    public const mixed MIXED_TYPE = true;
    protected const \MyNamespace\InterfaceName FQN_INTERFACE = new ClassName;

    // This also means that the below are all valid...
    public const ?array ARRAY = array(1, 2, 3);
    public const true TRUE = true;
    public const false FALSE = false;
    public const NULL NULL = null;

    // Union types are supported.
    final const int|float UNION_SIMPLE = 5;
    private final const MyClassA|\Package\MyClassB UNION_TWO_CLASSES = new MyClassA;
    protected const array|bool|int|float|NULL|object|String|iterable|parent UNION_ALL_BASE_TYPES = null;

    // Intersection types are supported.
    public const MyClassA&\Package\MyClassB INTERSECTION_TYPE = new MyClassA;
    final const Traversable&\Countable COUNTABLE_ITERATOR = new MyCountableArray([1,2,3]);

    // DNF types are supported.
    public const (MyClassA&\Package\MyClassB)|null DNF_TYPE = new MyClassA;
    final const Array|(Traversable&\Countable) ARRAY_LIKE = new MyCountableArray([1,2,3]);

    // The type applies to all constants in one declaration
    public const FLOAT FIRST_CONST = 10.5, SECOND_CONST = 3.2;
    public const bool FIRST = true,
        /**
         * Docblock
         */
        SECOND = false,
        // comment.
        THIRD = true;
}

enum PHP83Enum {
    // The self and static types are only allowed in enums.
    public const self MY_SELF = new self;
    public const static MY_STATIC = new static;
}

// Invalid constant types.
trait InvalidExample {
    public const void INVALID_VOID = null;
    public const never INVALID_NEVER = null;
    protected const Callable INVALID_CALLABLE = 'strlen';
    private const ?callable INVALID_NULLABLE_CALLABLE = null;
    final const Boolean LONG_BOOL = false;
    protected final const integer LONG_INT = 123;
}
