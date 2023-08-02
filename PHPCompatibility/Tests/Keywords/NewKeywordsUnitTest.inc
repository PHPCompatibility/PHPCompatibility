<?php

$dir = __DIR__;

try {
    // something
} catch (Exception $e) {
    // something else
} finally {
    // finally something
}

class Talker {
    use A, B {
        B::smallTalk insteadof A;
        A::bigTalk insteadof B;
    }
}

namespace Foobar;

$namespace = __NAMESPACE__;

trait FoobarTrait {
    public function foobar() {
        $name = __TRAIT__;
    }
}

function gen_one_to_three() {
    for ($i = 1; $i <= 3; $i++) {
        // Note that $i is preserved between yields.
        yield $i;
    }
}

const TEST = 'Hello';

class testing {
    const TEST = 'Hello';
    const ok = 'a';

    public function something() {
        const TEST = 'This is not a class constant';
    }
}

interface testing {
    const TEST = 'Hello';
    const ok = 'a';

    public function something() {
        const TEST = 'This is not an interface constant';
    }
}

$a = new class {
    const TEST = 'Hello';
    const ok = 'a';

    public function something() {
        const TEST = 'This is not a class constant';
    }
}


function myTest(callable $callableMethod) {}

goto end;

end:
echo 'something';

function testYieldFrom() {
    yield from [3, 4];
    yield
		from [3, 4]; // This is yield from, but tokenized as two T_YIELD_FROM tokens.
    yield /*something*/ From [3, 4]; // Test against false positive. Tokenized as T_YIELD + T_STRING prior to PHP 8.3.
}

// Normal Heredoc is ok.
$str = <<<EOD
Example of string
spanning multiple lines
using nowdoc syntax.
EOD;

// PHP 5.3 Nowdoc.
$str = <<<'LABEL'
Example of string
spanning multiple lines
using nowdoc syntax.
LABEL;

// PHP 5.3 quoted heredoc.
$str = <<<"LABEL"
Example of string
spanning multiple lines
using nowdoc syntax.
LABEL;

/*
 * Test case-insensitive matching of the PHPCS cross-version compat layer.
 */
TRAIT MyFoobarTrait {}

try {
} FINALLY {
}

/*
 * Check against false positives/correct PHPCS cross-version compat layer.
 * The fact that these keywords are reserved, is not our concern. That is handled by the forbiddenNames sniff.
 */

// "namespace" is a property (allowed use, though confusing), not the keyword.
$response->header( 'Location', rest_url( sprintf( '%s/%s/%d', $this->namespace, $this->rest_base, $id ) ) );

// yield used as a class constant.
echo MyClass::yield;

echo __dir__; // Magic constants are also case-insensitive.

// Prevent false positives on PHP 8.0+ nullsafe property access.
echo $obj?->finally;

/*
 * PHP 7.4: arrow functions.
 */
// OK: fn, but not an arrow function.
$a = Foo::fn($param);
$a = MyClass::FN;
$a = MyClass::Fn[$a];
$a = $obj->fn($param);
$a = $obj?->FN($param);
$a = MyNS\Sub\Fn($param);
$a = namespace\fn($param);

class Fn {
    const FN = 'a';

    public static function fn($param) {}

    public function bar() {
        $this->fn = 'a';
    }
}

// PHP 7.4 arrow functions.
$nums = array_map(fn($n) => $n * $factor, [1, 2, 3, 4]);

$fn1 = Fn($x) => $x + $y;

$fn = fn($c) => $callable($factory($c), $c);

$result = Collection::from([1, 2])
    ->map(fn($v) => $v * 2)
    ->reduce(fn($tmp, $v) => $tmp + $v, 0);

$result = array_map(
    static fn(int $number) : ?int => $number + 1,
    $numbers
);

/*
 * PHP 8.0: match expressions.
 */
// OK: match, but not an match expression.
$a = Foo::match($param);
$a = MyClass::match;
$a = MyClass::match[$a];
$a = $obj->match($param);
$a = $obj->match->chain($param);
$a = $obj?->match;
$a = MyNS\Sub\match($param);
$a = namespace\match($param);

class Match {
    const match = 'a';

    public static function match($param) {}

    public function bar() {
        $this->match = 'a';
    }
}

// PHP 8.0 match expressions.
$statement = match ($this->lexer->lookahead['type']) {
    Lexer::T_SELECT => $this->SelectStatement(),
    Lexer::T_UPDATE => $this->UpdateStatement(),
    Lexer::T_DELETE => $this->DeleteStatement(),
    default => $this->syntaxError('SELECT, UPDATE or DELETE'),
};

echo match (1) {
    0 => 'Foo',
    1 => 'Bar',
    2 => 'Baz',
};

MATCH ($pressedKey) {
    Key::RETURN_ => save(),
    Key::DELETE => delete(),
};

echo Match ($x) {
    1, 2 => 'Same for 1 and 2',
    3, 4 => 'Same for 3 and 4',
};

$result = match ($x) {
    foo() => ...,
    $this->bar() => ..., // bar() isn't called if foo() matched with $x
    $this->baz => ...,
    // etc.
};

$x = match ($y) {
    default => {
        // This should work fine
        (match ($z) { ... })
    },
};

// OK: enum, but not an enum declaration.
$a = Foo::enum($param);
$a = MyClass::enum;
$a = MyClass::enum[$a];
$a = $obj->enum($param);
$a = $obj->enum->chain($param);
$a = $obj?->enum;
$a = MyNS\Sub\enum($param);
$a = namespace\enum($param);

class Enum {
    const enum = 'a';

    public static function enum($param) {}

    public function bar() {
        $this->enum = 'a';
    }
}

// PHP 8.1 enum declarations.
enum SuitA {
  case Hearts;
  case Diamonds;
}

enum SuitB: string implements Colorful {
  case Clubs = 'C';
  case Spades = 'S';
}

__halt_compiler();

bla();
const ok = 'a';
