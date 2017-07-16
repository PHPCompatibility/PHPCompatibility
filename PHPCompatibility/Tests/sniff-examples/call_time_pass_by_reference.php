<?php

// Ok: Declare parameter by reference.
function abc(&$foobar)
{
    return $foobar;
}

$right = abc($a); // Ok: no reference.
$wrong = abc(&$a); // Bad: pass by reference.

$a = E_STRICT; // Sniffer checks strings, and returns if no left paren afterwards

abc($x, $y, $z, &$a);

// Nested function call.
preg_replace($a, $b, trim(&$a));

// Ok: Bitwise operations as parameter.
foobar(3 & $a); // LNUMBER + &
foobar($a & $b); // variable + &
foobar($b[0] & $a); // square bracket + &
foobar(($a) & $b); // parenthesis + &
foobar(intval(3) & $b); // function + &
foobar(& $b); // Bad: pass by reference with space.

define('MY_CONST', 0);

class MoreRefs
{
    const MYCONST = 1;
    private $attribute = 2;

    public function bar($arg)
    {
        // Ok: Bitwise operations as parameter.
        $a = sprintf(
            '%s %s %s'
            , self::MYCONST & $arg ? 1 : 2
            , $this->attribute & $arg ? 5 : 6
            , MY_CONST & $arg ? 7 : 8
        );

        $b = $this->foo(&$var); // Bad: pass by reference.
        $c = self::foobar(&$var); // Bad: pass by reference.
    }
}

abcd(&$x, &$y, $z, &$aa = false); // Bad: multiple pass by reference.

bcd(10, true, MYCONST); // OK: does not contain variables.

cde((&$abc)); // OK: outside of the scope of this sniff - will result in parse error.

// Issue https://github.com/wimg/PHPCompatibility/issues/68
$attr  = $this->doExtraAttributes("h$level", $dummy =& $matches[3]); // OK: assign by reference.
if (!is_null($done = &$this->cacheKey('autoPurgeCache'))) {} // OK: assign by reference.
def( $dummy .= &$b );
def( $dummy += &$b );
def( $dummy -= &$b );
def( $dummy *= &$b );
def( $dummy /= &$b );
def( $dummy %= &$b );
def( $dummy **= &$b );
def( $dummy &= &$b );
def( $dummy |= &$b );
def( $dummy ^= &$b );
def( $dummy <<= &$b );
def( $dummy >>= &$b );

def( &$dummy .= $b ); // Bad: pass by reference.

// Ok: Comparisons passed as function parameter.
efg( true == &$b );
efg( true === &$b );
