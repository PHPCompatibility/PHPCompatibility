<?php

// OK.
$str = strip_tags($str);
$str = strip_tags($input, '<br>');
$str = \strip_tags($input, allowed_tags: '<img><br><meta><input>');

// Undetermined. Ignore.
$str = strip_tags($str, $allowable_tags);
$str = strip_tags($str, self::ALLOWABLE_TAGS);
$str = strip_tags($str, allowed_tags: MyClass::get_allowable_tags('<br/>'));

// Not OK - warning.
$str = \strip_tags($input, '<br/>');
$str = Strip_Tags(allowed_tags: '<img/><br/>' . '<meta/><input/>', string: $input);

// Safeguard handling heredocs/nowdocs, including PHP 7.3 indented variants.
// Okay.
strip_tags($input, <<<"EOD"
<img><br><meta><input>
EOD
);

// Not okay.
strip_tags($input,
<<<EOD
<img/><br/>$extraTags
EOD
);

// Safeguard against false positives on method calls and namespaced function calls.
ClassName::strip_tags($input, '<br/>');
$obj->strip_tags($input, '<br/>');
$obj?->strip_tags($input, '<br/>'); // PHP 8.0+.
namespace\strip_tags($input, '<br/>');
\Fully\Qualified\strip_tags($input, '<br/>');
Partially\Qualified\strip_tags($input, '<br/>');

// More undetermined. Ignore.
$str = strip_tags($str, namespace\get_allowed_tags('<br/>'));
$str = strip_tags($str, \Fully\Qualified\get_allowed_tags('<br/>'));
$str = strip_tags($str, Partially\Qualified\get_allowed_tags('<br/>'));
