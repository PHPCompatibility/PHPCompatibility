<?php

/*
 * OK: Non-readonly properties.
 */
class NonReadonly {
    public $public;
    protected /* comment */ $protected;
    private static $private;
    static public $publicStatic;
    var $oldStyle;

    public int $scalarType;
    protected ClassName $classType;
    private ?ClassName $nullableClassType;
    public static Iterable $staticProp;
    var bool $flag;

    public string $str = "foo";
    public ?STRING $nullableStr = null;

	// Multi-property declarations.
    public float $a, $b;
    public int $x,
        /**
         * Docblock
         */
        $y,
        // comment.
        $z = 15;

    protected \MyNamespace\InterfaceName $namespacedInterfaceType;
    static bool $bool = true;
    static public inT $int = 0;

    // Intentional parse error.
    $invalidProperty;


	// Constructor property promotion.
    public function __construct(
        protected float|int $promotedProtected,
        public ?string &$promotedPublic = 'test',
        private mixed $promotedPrivate,
        callable $normalParamIgnore1,
        mixed $normalParamIgnore2,
    ) {}

    // Function parameter and function local var, not property.
    public function method(?int $param) {
        $localVar = 'abc';
    }
}

// Ignore. Not a constructor, so no property promotion.
function __construct(float|int $x, callable $callMe) {}


/*
 * PHP 8.1: readonly properies.
 */
class PHP81Example {
    readonly protected ClassName $classType;
    private readonly ?ClassName $nullableClassType;

    // Readonly properties do not need to explicitly declare their visibility.
    readonly bool $flag;

	// Readonly properties cannot have a default value, but that's not the concern of this sniff.
    public readonly int $scalarType = 0;

    // Readonly can only be applied to typed properties, but that's not the concern of this sniff.
    public readonly $prop;

    // Readonly is not allowed on static properties, but that's not the concern of this sniff.
    public static readonly Iterable $staticProp;

    // Readonly can not be applied to old-style "var" properties, but that's not the concern of this sniff.
    var readonly bool $flag;

	// Multi-property declarations.
    public readonly float $a, $b;
    readonly public int $x,
        /**
         * Docblock
         */
        $y,
        // comment.
        $z;

	// Constructor property promotion.
    public function __construct(
        protected readonly float|int $promotedProtected,
        // Default values are allowed when combined with constructor property promotion.
        readonly public ?string &$promotedPublic = 'test',
        private readonly mixed $promotedPrivate,
        callable $normalParamIgnore1,
        mixed $normalParamIgnore2,
    ) {}
}

// Parse error. This has to be the last test in the file.
class ParseError {
	public readonly $propertyWithoutSemiColon
}
