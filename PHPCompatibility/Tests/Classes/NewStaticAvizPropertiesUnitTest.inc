<?php

/*
 * OK: Non-static and/or non-asym visibility properties.
 */
class NoProperties {}

class NonStaticAviz {
    var $oldStyle;
    static $oldStyleStatic;

    static public $publicStatic;
    final protected static /* comment */ $protectedStatic;
    private static $privateStatic;

    public(set) int $scalarType;
    protected(set) ClassName $classType;
    private(set) ?ClassName $nullableClassType;
    public static Iterable $staticPropWithType;

    // Multi-property declarations.
    public static float $a, $b;
    final public(set) int $x,
        /**
         * Docblock
         */
        $y,
        // comment.
        $z = 15;

    protected(set) \MyNamespace\InterfaceName $namespacedInterfaceType;
    final static bool $bool = true;
    static public inT $int = 0;

    // Intentional parse error.
    $invalidProperty;

    // Constructor property promotion does not support static properties.
    public function __construct(
        protected(set) float|int $promotedProtected,
        public ?string &$promotedPublic,
        private(set) mixed $promotedPrivate,
        callable $normalParamIgnore1,
        mixed $normalParamIgnore2,
    ) {}

    // Function parameter and function local var, not property.
    public function method(?int $param) {
        $localVar = 'abc';
    }
}


/*
 * PHP 8.5: static properties with asymmetric visibility.
 */
class PHP85StaticAviz {
    static protected(set) ClassName $classType;
    private(set) static ?ClassName $nullableClassType;
    final public static public(set) int $typed = 10;

    // Readonly static aviz properties are not supported, but that's not the concern of this sniff.
    protected(set) readonly static bool $noVisibility;

    // Asymmetric visibility can only be applied to typed properties, but that's not the concern of this sniff.
    public(set) static $unTyped;

    // Multi-property declarations.
    protected(set) static float $a, $b;
    static private(set) int $x,
        /**
         * Docblock
         */
        $y,
        // comment.
        $z;
}
