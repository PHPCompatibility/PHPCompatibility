<?php

$a = 1;

class MoneyBags
{
}

function abc(&$foobar)
{
    return $foobar;
}

$right = abc($a);
$wrong = abc(&$a);

$a = E_STRICT; // Sniffer checks strings, and returns if no left paren afterwards

abc($x, $y, $z, &$a);

// nested function call
preg_replace($a, $b, trim(&$a));

foobar(3 & $a); // LNUMBER + &
foobar($a & $b); // variable + &
foobar($b[0] & $a); // square bracket + &
foobar(($a) & $b); // parenthesis + &
foobar(intval(3) & $b); // function + &
foobar(& $b);

define('MY_CONST', 0);

class MoreRefs
{
    const MYCONST = 1;
    private $attribute = 2;

    public function bar($arg)
    {
        $a = sprintf(
            '%s %s %s'
            , self::MYCONST & $arg ? 1 : 2
            , $this->attribute & $arg ? 5 : 6
            , MY_CONST & $arg ? 7 : 8
        );
    }
}