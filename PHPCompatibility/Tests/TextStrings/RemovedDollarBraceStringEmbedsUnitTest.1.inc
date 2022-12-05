<?php

/*
 * Embedded variables which are supported cross-version.
 */

// Type 1: directly embedding variables.
echo "$foo";
echo "$$foo";
echo "$foo[bar]";
echo "$foo->bar";
$text = "some text $var some text";

$heredoc = <<<EOD
some text $var some text
EOD;

// Type 2: Braces around the variable/expression.
echo "{$foo}";
echo "{$$foo}";
echo "{$foo['bar']}";
echo "{$foo->bar}";
echo "{$foo->bar()}";
echo "{$foo['bar']->baz()()}";
echo "{${$bar}}";
echo "{$foo()}";
echo "{${$object->getMethod()}}"
$text = "some text {$var} some text";

$heredoc = <<<"EOD"
some text {$var} some text
EOD;

/*
 * Not our target.
 */

// Ordinary variable variables outside strings.
$foo = ${'bar'};

// Heredoc without embeds.
echo <<<EOD
Some text
EOD;

// Not actually interpolated - $ is escaped. The second $foo is to force T_DOUBLE_QUOTED_STRING tokenization.
echo "\${foo} and $foo";
echo "\${foo[\"bar\"]} and $foo";
echo "$\{foo} and $foo";


/*
 * PHP 8.2: deprecated forms of embedding variables.
 */

// Type 3: Braces after the dollar sign.
echo "${foo}";
echo "${foo['bar']}";
$text = "some text ${foo} some ${text}";

$heredoc = <<<EOD
some text ${foo} some text
EOD;

echo "\\${foo}"; // Not actually escaped, the backslash escapes the backslash, not the dollar sign.

// Type 4: Variable variables.
echo "${$bar}";
echo "${(foo)}";
echo "${foo->bar}";
echo "${$object->getMethod()}"
$text = "some text ${(foo)} some text";
echo "${substr('laruence', 0, 2)}";

echo "${foo["${bar}"]}";
echo "${foo["${bar['baz']}"]}";
echo "${foo->{$baz}}";
echo "${foo->{${'a'}}}";
echo "${foo->{"${'a'}"}}";

// Verify correct handling of stack pointers in multi-token code.
$text = "Line without embed
some text ${foo["${bar}"]} some text
some text ${foo["${bar['baz']}"]} some text
some text ${foo->{${'a'}}} some text
";

$heredoc = <<<"EOD"
some text ${(foo)} some text
EOD;
