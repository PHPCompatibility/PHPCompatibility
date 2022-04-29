<?php

/*
 * OK on all versions.
 */
class NoParams {
    public function __construct() {}
}

class CrossVersion {
    public function __construct(int $a, $b = true, &...$c) {}
}

/*
 * Not valid, but not a class constructor either, so ignore.
 *
 * These will all generate a fatal error.
 */
function __construct(public $a) {}

function globalFunction(private $x) {}

class Invalid {
    protected function thisIsNotAConstructor(protected mixed $foo = null) {}
}

/*
 * PHP 8 Constructor Property Promotion.
 */
class ConstructorPropertyPromotionNoTypes {
    public function __construct(
        public $x = 0.0,
        protected $y = '',
        private $z = null,
    ) {}
}

class ConstructorPropertyPromotionWithTypes {
    public function __construct(protected float|int $x, public ?string &$y = 'test', private mixed $z) {}
}

class ConstructorPropertyPromotionAndNormalParams {
    public function __construct(
        public int $promotedProp,
        ?int $normalArg
    ) {}
}

abstract class ConstructorPropertyPromotionAbstractMethod {
    // Intentional fatal error.
    // 1. Property promotion not allowed in abstract method, but that's not the concern of this sniff.
    // 2. Variadic arguments not allowed in property promotion, but that's not the concern of this sniff.
    // 3. The callable type is not supported for properties, but that's not the concern of this sniff.
    abstract public function __construct(public callable $y, private ...$x);
}

// Safeguard handling of constructor prop promotion with PHP 8.1 intersection types,
// PHP 8.1 readonly properties and PHP 8.2 stand-alone true/false/null types.
class ConstructorPropertyPromotionWithReadonlyProperties {
    public function __construct(
        public readonly string $x,
        readonly ?int $noVisibility,
        readonly private Foo $modifierOrder,
        readonly Foo&Bar $intersectionType,
        public true $thisIsSilly,
        private null $thisIsAlsoSilly,
    ) {}
}
