<?php

/*
 * Check use of incorrectly named constructs. These should be ignored by this sniff as they can't be fixed
 * until the declaration is fixed.
 */

use My\Foreach\Switch;
use function eval;
use const CLASS;

class Foo extends List implements Callable
{
    use Die;
    use Include {
        require as private;
        require_once as public;
        include_once as final;
    }

    public function bar(\For $obj1, Break $obj2, $static = parent::OR)
    {
        if (defined('DEFAULT')) {}

        $out = global($param);
        $out = My\InstanceOf\FunctionName(AS);
        $out = My\Function($param);
        $out = $My->exit($param);
        $out = $My::new($param);

        $count = self::CONST + 10;

        $obj = new Empty();

        try {
        } catch (\Throw $e) {
        }
    }
}

interface Bar extends Yield {}

$anon = new class extends Clone {};

// Live coding/parse error.
// This has to be the last test in the file.
// Testing T_STRING without anything after it.
someText
