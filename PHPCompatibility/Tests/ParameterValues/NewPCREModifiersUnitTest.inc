<?php

preg_match('/some text/mJ', $subject);
preg_grep('#some text#Ji', $input, $flags);

$text = preg_match_all(
    '/(?<!\\\\)     # not preceded by a backslash
      <             # an open bracket
      >             # close bracket
    /iJx',
    '[$1]',
    $text
  );

preg_split(
    [
        'be' => '/single-quoted/J',
        'ce' => '#hash-chars (common)#j',
        'de' => '!exclamations (why not?!eJs',
    ], $subject, 2
);

preg_replace_callback_array(
    [
        '~[a]+~J' => function ($match) {
            echo strlen($match[0]), ' matches for "a" found', PHP_EOL;
        },
        '~[b]+~i' => function ($match) {
            echo strlen($match[0]), ' matches for "b" found', PHP_EOL;
        }
    ],
    $subject
);

// Verify support for heredocs and nowdocs.
$text = preg_match_all(
    <<<'EOD'
/(?<!\\\\)     # not preceded by a backslash
  <            # an open bracket
  >            # close bracket
/iJx
EOD
    ,
    '[$1]',
    $text
  );

// Safeguard support for PHP 8 named parameters.
preg_grep(array: $input, pattern: '#some text#i'); // OK.
preg_grep(array: $input, pattern: '#some text#Ji', flags: $flags); // Error.

// Recognize use of the PHP 8.2 "n" modifier.
preg_match('/.(.)./n', 'abc', $m);
preg_match('`.(?P<test>.).`n', 'abc', $m);
preg_match('{.(?P<test>.).}n', 'abc', $m);

// Recognize use of the PHP 8.4 "r" modifier.
preg_match('/.(.)./ri', 'abc', $m);
preg_match('`.(?P<test>.).`r', 'abc', $m);
preg_match('{A\x{17f}\x{212a}Z}xr', 'abc', $m);
