<?php

/**
 * Valid cross-version/not our targets.
 */
const OUTSIDE_OO_SCOPE = 'foo';

class ConstDemo
{
    const CLASS_CONST = 1;
}

interface InterfaceDemo
{
    public const INTERFACE_CONST = 1;
}

$a = new class
{
    final const ANONYMOUS_CONST = 1;
};

enum EnumDemo
{
    protected final const ENUM_CONST = 1;
}

trait traitDemo
{
    public function method() {
        const NOT_TRAIT_CONST_C = 1;
    }
}


/**
 * PHP 8.2+: constants in traits.
 */
trait traitDemo
{
    const TRAIT_CONST_A = 1;
    public const TRAIT_CONST_B = 1, TRAIT_CONST_C = 2;
    final const TRAIT_CONST_D = false;
}
