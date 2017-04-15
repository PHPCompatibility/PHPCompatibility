<?php

// Static variables.
function foo() {
    static $bar = <<<LABEL
Nothing in here...
LABEL;
}

// Class properties/constants.
class foo
{
    const BAR = <<<FOOBAR
Constant example
FOOBAR;

    // Double quoted heredoc only introduced in PHP 5.3 and will be caught by another sniff.
    // Will still throw an error here too if the sniff is run on PHP >= 5.3.
    private $baz = <<<"FOOBAR"
Property example
FOOBAR;
}

// Anonymous class properties/constants.
$class = new class
{
    const BAR = <<<FOOBAR
Constant example
FOOBAR;

    protected static $baz = <<<FOOBAR
Property example
FOOBAR;
}

// Interface constants - interfaces cannot declare properties.
interface FooBar
{
    const BAR = <<<FOOBAR
Constant example
FOOBAR;
}

// Trait properties - traits cannot declare constants.
trait FooBar
{
    public $baz = <<<FOOBAR
Property example
FOOBAR;
}


/*
 * Test against false positives.
 */
// Not a class constant.
const ONE = <<<FOOBAR
Property example
FOOBAR;

class SomeThing {
    function scoped {
        const ONE = <<<FOOBAR
Property example
FOOBAR;
    }
}

// Pre-PHP 5.3 ordinary variable initialization with heredoc was already ok.
$var = <<<FOOBAR
Constant example
FOOBAR;

// Nowdoc was only introduced in PHP 5.3 and is sniffed for in a separate sniff.
$var = <<<'FOOBAR'
Constant example
FOOBAR;

$array = array( 'key' => <<<FOOBAR
Constant example
FOOBAR
);
