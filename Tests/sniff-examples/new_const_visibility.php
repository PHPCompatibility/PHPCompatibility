<?php

const NONCLASSCONST = 'foo';

class ConstDemo
{
    const PUBLIC_CONST_A = 1;

    // PHP 7.1+
    public const PUBLIC_CONST_B = 2;
    protected const PROTECTED_CONST = 3;
    private const PRIVATE_CONST = 4;
}

interface InterfaceDemo
{
    const PUBLIC_CONST_A = 1;

    // PHP 7.1+
    public const PUBLIC_CONST_B = 2;

    // Invalid, but the check for which visibility indicator is used is outside the scope of this library.
    protected const PROTECTED_CONST = 3;
    private const PRIVATE_CONST = 4;
}
