<?php

// OK.
mb_strrpos('abc abc abc', 'abc');
mb_strrpos('abc abc abc', 'abc', 0);
mb_strrpos(
    'abc abc abc',
    'abc',
    /* comment*/
    10 // phpcs:ignore Something
);
mb_strrpos('abc abc abc', 'abc', $offset, $encoding);
mb_strrpos('abc abc abc', 'abc', -5);
mb_strrpos('abc abc abc', 'abc', +1.2); // Float will be cast to int, so this is fine.
mb_strrpos('abc abc abc', 'abc', 'UTF-' + 8);

// Ignoring. Can not be reliably determined:
mb_strrpos('abc abc abc', 'abc', $offset);
mb_strrpos('abc abc abc', 'abc', $encoding);

// PHP 5.2/7.4: deprecated encoding as third param.
mb_strrpos('abc abc abc', 'abc', 'UTF-8');
mb_strrpos('abc abc abc', 'abc', "utf-$utfType");
mb_strrpos('abc abc abc', 'abc', 'utf-' . $utfType);

// Safeguard support for PHP 8 named parameters.
mb_strrpos(haystack: 'abc abc abc', needle: 'abc', encoding: 'UTF-8'); // OK.
mb_strrpos(offset: 'UTF-8', haystack: 'abc abc abc', needle: 'abc',); // Error, not that it would make sense to pass this.

// Issue #1721: ignore as undetermined.
mb_strrpos('abc abc abc', 'abc', $pos['offset']);
mb_strrpos('abc abc abc', 'abc', \get_offset_or_encoding('foo'));
mb_strrpos('abc abc abc', 'abc', fn($a = 'default') => get_offset_or_encoding() ?? $a);
mb_strrpos('abc abc abc', 'abc', function ($a = 'default') { return get_offset_or_encoding() ?? $a . 'text'; });

// ... but still flag this as we can be sure that the resulting param value is a string.
mb_strrpos('abc abc abc', 'abc', (string) $encoding);
mb_strrpos('abc abc abc', 'abc', $utf . $nr);
mb_strrpos(
    'abc abc abc',
    'abc',
    <<<EOD
$utf
EOD
);
