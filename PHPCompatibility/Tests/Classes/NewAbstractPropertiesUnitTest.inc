<?php

/**
 * Valid cross-version/not our targets.
 */

// Variables defined in the global namespace can not have the abstract modifier,
// but this is outside the scope of this library. Would cause a parse error anyway.
abstract $abstractOutsideOOScope = 'not valid';

class ClassWithoutProperties {}


// The sniff should not trigger on uses of the "abstract" keyword other than for properties.
// The sniff should also not trigger on non-abstract properties or on variables which are not properties.
abstract class ClassWithoutAbstractProperties
{
    protected $notAbstract, $alsoNotAbstract;

    abstract function bar($notAbstract);

    public function __construct(
        public $promotedPropA,
        protected $promotedPropB,
    ) {}
}

interface InterfaceWithoutAbstractProperties
{
    public $notAbstract { get; }

    public function bar($notAbstract);
}

trait TraitWithoutAbstractProperties
{
    private $notAbstract { set; }

    abstract protected function bar($notAbstract);
}

// Note: Anonymous classes cannot be declared as abstract.
$anonClass = new class
{
    readonly int $notAbstract;

    protected static function bar($notAbstract) {}
};

enum EnumWithAbstractProperties
{
    // Properties are not allowed in enums. Ignore.
    // Fatal error: Enum EnumWithAbstractProperties cannot include properties.
    abstract $illegal { get; }

    // Abstract methods are also not supported as enums cannot be extended, but that's not the point of this test.
    // Fatal error: Enum EnumWithAbstractProperties must implement 1 abstract private method.
    abstract public function bar($notAbstract);
}


/*
 * PHP 8.4: Detect abstract properties.
 *
 * Note: hooks are not supported on multi-property declarations. That also means they cannot abstract.
 */
abstract class ClassWithAbstractProperties
{
    abstract $onlyAbstract { get; }
    abstract public $abstractPublic { set; }
    public /*comment*/ abstract int $publicAbstractTyped { get; }
    abstract protected /*comment*/ MyType|false $protectedAbstractTyped { set; }

    // Illegal, readonly and static properties cannot be abstract, but that's not the concern of this sniff.
    static abstract $abstractStatic { get; }
    abstract
     readonly
      ?int
       $abstractReadonlyErrorLineCheck { set; }

    // Fatal error as abstract with private is an oxymoron, but that's not the concern of this sniff.
    abstract private $abstractPrivate { get; }
    private abstract $privateAbstract { set; }
}

trait TraitWithAbstractProperties
{
    protected abstract $protectedAbstract { get; }
}

$anonClass = new class
{
    // Illegal, but that's not the concern of this sniff.
    // Fatal error: Class class@anonymous contains 1 abstract method and must therefore be declared abstract or implement the remaining methods.
    abstract string|false $abstractTyped { set; }
};

interface InterfaceWithAbstractProperties
{
    // Illegal, but that's not the concern of this sniff.
    // Fatal error: Property in interface cannot be explicitly abstract. All interface members are implicitly abstract.
    abstract public $abstractPublic { get; }
}
