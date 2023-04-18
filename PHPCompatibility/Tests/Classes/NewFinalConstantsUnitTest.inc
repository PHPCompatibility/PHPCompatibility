<?php

const NONCLASSCONST = 'foo';

class ConstDemo
{
    const MY_CONST = 1;

    // PHP 8.1+.
    final const FINAL_A = 2;
    final public const FINAL_PUBLIC_CONST_A = 3;
    public final const FINAL_PUBLIC_CONST_B = 4;
    final protected const FINAL_PROTECTED_CONST_A = 5;
    protected final const FINAL_PROTECTED_CONST_B = 6;

    // Fatal error as final with private is an oxymoron.
    final private const FINAL_PRIVATE_CONST_A = 7;
    private final const FINAL_PRIVATE_CONST_B = 8;
}

interface InterfaceDemo
{
    const MY_CONST = 1;

    // PHP 8.1+.
    final const FINAL_A = 2;
    final public /*comment*/ const FINAL_PUBLIC_CONST_A = 3;
    public final const FINAL_PUBLIC_CONST_B = 4;

    // Fatal error as interface constants must be public, but the check for which visibility indicator is used is outside the scope of this sniff.
    final /*comment*/ protected const FINAL_PROTECTED_CONST_A = 5;
    protected final const FINAL_PROTECTED_CONST_B = 6;

    // Fatal error as final with private is an oxymoron.
    final private const FINAL_PRIVATE_CONST_A = 7;
    private final const FINAL_PRIVATE_CONST_B = 8;
}

// Test anonymous classes.
$a = new class
{
    const MY_CONST = 1;

    // PHP 8.1+.
    final const FINAL_A = 2;
    final public const FINAL_PUBLIC_CONST_A = 3;
    public final const FINAL_PUBLIC_CONST_B = 4;
    final protected const FINAL_PROTECTED_CONST_A = 5;
    protected final const FINAL_PROTECTED_CONST_B = 6;

    // Fatal error as final with private is an oxymoron.
    final private const FINAL_PRIVATE_CONST_A = 7;
    private final const FINAL_PRIVATE_CONST_B = 8;
};

/*
 * Test against some false positives.
 *
 * Constants defined in the global namespace can not have the final modifier,
 * but this is outside the scope of this library. Would cause a parse error anyway.
 */
final const GLOBAL_CONSTANT = 'not valid';

class NotAClassConstant {
    public function something() {
        final const GLOBAL_CONSTANT = 'not valid';
    }
}
