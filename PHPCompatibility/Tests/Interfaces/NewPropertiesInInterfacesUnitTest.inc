<?php

/**
 * Valid cross-version/not our targets.
 */
$variableOutsideOoScope = 'foo';

class ClassNotInterface
{
    public $prop = 1;
}

trait TraitNotInterface
{
    public $prop = 1;
}

$a = new class
{
    public $prop = 1;
};

enum EnumNotInterface
{
    public $prop = 1;
}

interface InterfaceDemo
{
    public function __construct($parameterNotProperty);
    public function method($parameterNotProperty);
}


/**
 * PHP 8.4+: (hooked) properties in interfaces.
 */
interface PropertiesInInterface
{
    // Only public properties are supported in interfaces. A type is not required.
    public $noType { get; set; }

    // The old-style "var" keyword (alias for "public") is also supported.
    var $alsoNoType { get; set; }

    public string $readable { get; }
    public int $writeable { set; }
    public ?bool $both { get; set; }
}

/*
 * All of the following are not supported, but that's an implementation detail of PHP
 * and not the concern of this sniff (unless any of this would change in the future).
 * The sniff should still throw an error for these, as PHPCompatibility only concerns
 * itself with cross-version compat issues, not incorrect use of PHP.
 */
interface UnsupportedPropertiesInInterface
{
    // Due to the nature of interfaces, all declared properties must be public.
    // Fatal error: Property in interface cannot be protected or private.
    protected $protected { get; set; }
    private $private { get; set; }

    // Non-hooked properties are not supported in interfaces.
    // Fatal error: Interfaces may only include hooked properties.
    public $notHooked;

    // Constructor property promotion is not supported.
    // Fatal error: Cannot declare promoted property in an abstract constructor.
    public function __construct(
        public string $promoted { set; }
    );

    // Readonly properties are not supported, as property hooks are not supported for readonly properties.
    // Fatal error: Hooked properties cannot be readonly.
    readonly $readonlyProp { get; }

    // Static properties are not supported, as property hooks are not supported for static properties.
    // Parse error: syntax error, unexpected token ";", expecting "function".
    static $staticProp { get; }

    // Multi-property declarations are not supported, as property hooks are not supported for multi-property declarations.
    // Parse error: syntax error, unexpected token "{", expecting "," or ";".
    public int $readableA, $readableB {get;}
}
