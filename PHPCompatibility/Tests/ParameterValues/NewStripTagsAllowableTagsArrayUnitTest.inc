<?php

// OK.
$str = strip_tags($str);
$str = \strip_tags($input, allowed_tags: '<a><p>');

// Undetermined. Ignore.
$str = strip_tags($str, $allowable_tags);
$str = \strip_tags($str, self::ALLOWABLE_TAGS);
$str = strip_tags($str, MyClass::get_allowable_tags(['a', 'p']));

// PHP 7.4: passing $allowable_tags as an array.
$str = strip_tags($input, ['a', 'p']);
$str = \strip_tags(
    $input,
    array(
        'a',
        'p'
    )
);

// PHP 7.4: Warning. Incorrectly passing $allowable_tags as an array.
$str = strip_tags(allowed_tags: ['<a>', '<p>'], string: $input);
$str = Strip_TAGS(
    $input,
    array(
        '<a>',
        '<p>'
    ),
);

// Prevent false positives.
$str = strip_tags($input, ['br', ...function_call('<a>,<p>')]); // PHP 7.4 syntax: unpacking within an array.
$str = strip_tags($input, []);

$str = strip_tags(
    $input,
    array(
        'a', // <a href=...>
        'p', // <p> 
    ),
);

// Safeguard against false positives on method calls and namespaced function calls.
ClassName::strip_tags($input, ['a', 'p']);
$obj->strip_tags($input, ['a', 'p']);
$obj?->strip_tags($input, ['a', 'p']);
namespace\strip_tags($input, ['<a>', '<p>']);
\Fully\Qualified\strip_tags($input, ['a', 'p']);
Partially\Qualified\strip_tags($input, ['<a>', '<p>']);

// More prevent false positives for incorrect format. Ignore.
$str = strip_tags($str, [...namespace\get_allowed_tags(['<a>', '<p>'])]);
$str = \strip_tags($str, [...\Fully\Qualified\get_allowed_tags(['<a>', '<p>'])]);
$str = strip_tags($str, [...Partially\Qualified\get_allowed_tags(['<a>', '<p>'])]);
