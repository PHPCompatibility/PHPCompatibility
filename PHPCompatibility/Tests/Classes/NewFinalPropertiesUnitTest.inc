<?php

/**
 * Valid cross-version/not our targets.
 */

// Variables defined in the global namespace can not have the final modifier,
// but this is outside the scope of this library. Would cause a parse error anyway.
final $finalOutsideOOScope = 'not valid';

// The sniff should not trigger on uses of the "final" keyword other than for properties.
// The sniff should also not trigger on non-final properties or on variables which are not properties.
final class ClassWithoutFinalProperties
{
    final const MY_CONST = 10;

    protected $notFinal;

    final function bar($notFinal) {}
}

interface InterfaceWithoutFinalProperties
{
    final public const MY_CONST = 10;

    public $notFinal { get; }

    public function bar($notFinal);
}

trait TraitWithoutFinalProperties
{
    final const MY_CONST = 10;

    private $notFinal;

    final protected function bar($notFinal) {}
}

$anonClass = new class
{
    final const MY_CONST = 10;

    readonly int $notFinal;

    protected final static function bar($notFinal) {}
};

enum EnumWithFinalProperties
{
    final public const MY_CONST = 10;

    // Properties are not allowed in enums. Ignore.
    // Fatal error: Enum EnumWithFinalProperties cannot include properties.
    final $illegal = 10;

    final public function bar($notFinal) {}
}


/*
 * PHP 8.4: Detect final properties.
 */
class ClassWithFinalProperties
{
    final $onlyFinal = 1;
    final public $finalPublic = 2;
    public /*comment*/ final int $publicFinalTyped = 3;
    final protected /*comment*/ MyType|false $protectedFinalTyped = false;
    static final $finalStatic = 5;
    final
     readonly
      ?int
       $finalReadonlyErrorLineCheck;

    final $multiPropA, $multiPropB, $multiPropC;

    // Fatal error as final with private is an oxymoron, but that's not the concern of this sniff.
    final private $finalPrivate = 7;
    private final $privateFinal = 8;
}

trait TraitWithFinalProperties
{
    protected static final $protectedStaticFinal;
}

$anonClass = new class
{
    readonly final string|false $readonlyFinalTyped;
};

interface InterfaceWithFinalProperties
{
    // Illegal, but that's not the concern of this sniff.
    // Fatal error: Property in interface cannot be final.
    final public $finalPublic { get; }
}

// PHP 8.4: safeguard the sniff still functions correctly when asymmetric visibility is used.
class AsymVisibility {
    final public private(set) $asymExplicit;
    protected(set) $asymImplicit;
}

class ClassWithoutAnyProperties
{
    final const MY_CONST = 10;
    final function bar($notFinal) {}
}
