<?php

// OK.
$str = strip_tags($str);
$str = strip_tags($input, '<br>');
$str = strip_tags($input, allowed_tags: '<img><br><meta><input>');

// Undetermined. Ignore.
$str = strip_tags($str, $allowable_tags);
$str = strip_tags($str, self::ALLOWABLE_TAGS);
$str = strip_tags($str, allowed_tags: MyClass::get_allowable_tags('<br/>'));

// Not OK - warning.
$str = strip_tags($input, '<br/>');
$str = strip_tags(allowed_tags: '<img/><br/>' . '<meta/><input/>', string: $input);

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

// Safeguard against false positives on method calls.
ClassName::strip_tags($input, '<br/>');
$obj->strip_tags($input, '<br/>');
$obj?->strip_tags($input, '<br/>'); // PHP 8.0+.
