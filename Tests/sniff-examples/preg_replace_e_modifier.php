<?php

$Source = "anything";
$Replace = "anything";

///////////// No warning generated:

// Different quote styles.
preg_replace("/double-quoted/", $Replace, $Source);
preg_replace('/single-quoted/', $Replace, $Source);

// Different regex markers.
preg_replace('#hash-chars (common)#', $Replace, $Source);
preg_replace('!exclamations (why not?!', $Replace, $Source);

// Safe modifiers
preg_replace('/some text/mS', $Replace, $Source);
preg_replace('#some text#gi', $Replace, $Source);

// E modifier doesn't exist, but should not trigger error.
preg_replace('//E', $Replace, $Source);

// Multi-line example (issue #83)
$text = preg_replace(
    '/(?<!\\\\)     # not preceded by a backslash
      <             # an open bracket
      (             # start capture
        \/?         # optional backslash
        collapse    # the string collapse
        [^>]*       # everything up to the closing angle bracket; note that you cannot use one inside the tag!
      )             # stop capture
      >             # close bracket
    /ix',
    '[$1]',
    $text
  );

// Multi-line with /e in comments.
preg_replace(
        '/.*     # /e in a comment
        /x',
    $Replace, $Source);

// Escaped /e
preg_replace('/\/e/', $Replace, $Source);

///////////// Warning generated:

// Different quote styles.
preg_replace("/double-quoted/e", $Replace, $Source);
preg_replace('/single-quoted/e', $Replace, $Source);

// Different regex markers.
preg_replace('#hash-chars (common)#e', $Replace, $Source);
preg_replace('!exclamations (why not?!e', $Replace, $Source);

// Other modifiers with /e
preg_replace('/some text/emS', $Replace, $Source);
preg_replace('/some text/meS', $Replace, $Source);
preg_replace('/some text/mSe', $Replace, $Source);

// Multi-line example (issue #83)
$text = preg_replace(
    '/(?<!\\\\)     # not preceded by a backslash
      <             # an open bracket
      (             # start capture
        \/?         # optional backslash
        collapse    # the string collapse
        [^>]*       # everything up to the closing angle bracket; note that you cannot use one inside the tag!
      )             # stop capture
      >             # close bracket
    /iex',
    '[$1]',
    $text
  );

// Multi-line with /e in comments.
preg_replace(
        '/.*     # /e in a comment
        /xe',
    $Replace, $Source);

// Escaped /e
preg_replace('/\/e/e', $Replace, $Source);

///////////// Untestable - should not generate an error.

$Regex = "/anything/";
define("X_REGEX_Xe", "/anything/");
function XRegeXe() {
    return "/anything/";
}

preg_replace($Regex, $Replace, $Source);
preg_replace(XRegeXe(), $Replace, $Source);
preg_replace(X_REGEX_Xe, $Replace, $Source);

///////////// Using bracket delimiters
preg_replace('{\d}e', $Replace, $Source); //bad
preg_replace('{\d{2}}e', $Replace, $Source); //bad
preg_replace('{\d{2}e}', $Replace, $Source); //good
preg_replace('`\d{2}e`', $Replace, $Source); // good
preg_replace('{^fopen(.*?): }', $Replace, $Source); //good - monolog example
preg_replace('[\d{2}]e', $Replace, $Source); //bad
preg_replace('[\d{2}e]', $Replace, $Source); //good
preg_replace('(\d{2})e', $Replace, $Source); //bad
preg_replace('(\d{2}e)', $Replace, $Source); //good
preg_replace('<\d{2}>e', $Replace, $Source); //bad
preg_replace('<\d{2}e>', $Replace, $Source); //good

///////////// Using preg_filter

// Different quote styles.
preg_filter("/double-quoted/e", $Replace, $Source);
preg_filter('/single-quoted/e', $Replace, $Source);

// Different regex markers.
preg_filter('#hash-chars (common)#e', $Replace, $Source);
preg_filter('!exclamations (why not?!e', $Replace, $Source);

// Other modifiers with /e
preg_filter('/some text/emS', $Replace, $Source);
preg_filter('/some text/meS', $Replace, $Source);
preg_filter('/some text/mSe', $Replace, $Source);

// Multi-line example (issue #83)
$text = preg_filter(
    '/(?<!\\\\)     # not preceded by a backslash
      <             # an open bracket
      (             # start capture
        \/?         # optional backslash
        collapse    # the string collapse
        [^>]*       # everything up to the closing angle bracket; note that you cannot use one inside the tag!
      )             # stop capture
      >             # close bracket
    /iex',
    '[$1]',
    $text
  );

// Multi-line with /e in comments.
preg_filter(
        '/.*     # /e in a comment
        /xe',
    $Replace, $Source);

// Escaped /e
preg_filter('/\/e/e', $Replace, $Source);

///////////// More warning generated:

// Regex build up of strings combined with variables/function calls.
preg_replace('/something' . $variable . 'something else/e', $Replace, $Source);
preg_replace('/something' . preg_quote($variable) . 'something else/e', $Replace, $Source);
