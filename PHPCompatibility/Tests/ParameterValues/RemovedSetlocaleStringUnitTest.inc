<?php

// OK.
setlocale();
setlocale(LC_ALL, 'nl_NL');
\setlocale($category, $lang); // Can't be determined.

// Not OK.
setlocale('LC_ALL', 'nl_NL');
\setlocale('LC_'.$category, $lang);

// Issue #1043 - ignore function calls, constants etc.
setlocale(getMyLocale('text'), 'nl_NL');
setlocale($array['LC_ALL'], 'nl_NL');

// Safeguard support for PHP 8 named parameters.
setlocale(locales: 'nl_NL', category: $category); // Can't be determined.
SETLOCALE(category: 'LC_ALL', locales: 'nl_NL'); // Error.

// Safeguard against false positives when target param not found.
setlocale(locales: 'nl_NL', cat: LC_ALL,); // OK, well not really, but using incorrect parameter name.

// Safeguard against false positives on method calls and namespaced function calls.
ClassName::setlocale('LC_'.$category, $lang);
$obj->setlocale('LC_'.$category, $lang);
$obj?->setlocale('LC_'.$category, $lang);
namespace\setlocale('LC_'.$category, $lang);
\Fully\Qualified\setlocale('LC_'.$category, $lang);
Partially\Qualified\setlocale('LC_'.$category, $lang);

// More issue #1043, undetermined. Ignore.
setlocale(namespace\getMyLocale('text'), 'nl_NL');
\SetLocale(\Fully\Qualified\getMyLocale('text'), 'nl_NL');
setlocale(Partially\Qualified\getMyLocale('text'), 'nl_NL');
