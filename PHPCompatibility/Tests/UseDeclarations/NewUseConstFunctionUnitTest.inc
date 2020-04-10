<?php

/*
 * Use statements which are ok pre-PHP 5.6.
 */
namespace FooBar;
    use Foo\Bar;
    use Foobar as Baz;
    use Foobar as Baz, Bay as BarFoo;

/*
 * Not import use statements.
 */
class Foobar {
    use Baz;
}

class Foobar {
    use BazTrait {
        oldfunction as Baz;
    }
}

$closure = function($a) use ($b) {};

/*
 * PHP 5.6: Use statements using `const` and `function`
 */
use const Baz;
use Const FOOBAR as Baz;
use function Baz;
use FUNCTION FooBar as Baz;

/*
 * Incorrect use, but covered by ForbiddenNames sniff, should not be reported here.
 */
use const as Baz;
use function as Baz;
use const, function, somethingElse;

/*
 * Not supported by PHP, so the sniff should ignore these.
 */
class Foobar {
    use const Baz;
    use function Bar;
}

// Live coding.
// Intentional parse error. This should be the last test in the file.
use function
