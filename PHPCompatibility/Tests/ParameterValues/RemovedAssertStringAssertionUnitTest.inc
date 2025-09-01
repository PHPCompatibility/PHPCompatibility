<?php

// Incorrect call. Ignore.
assert();

// OK in all versions.
assert(false);
\assert(true);

// Undetermined. Ignore.
assert($assertion);

// Expression, PHP 7+, not our concern.
assert(true == false);
assert((bool) ($input));

// Deprecated as of PHP 7.2.
assert('mysql_query("")');
\assert("2 < 1" /* just a demo */, 'Two is less than one');
assert(<<<'EOT'
    is_string($result) &&
    (strlen($result) > 0);
EOT
);
Assert(
    'is_int($int)'
    . '&& $int > 10'
);

// Safeguard against false positives on method calls and namespaced function calls.
ClassName::assert('mysql_query("")');
$obj->assert('mysql_query("")');
$obj?->assert('mysql_query("")');
// (Re)defining a custom assert function is not allowed in PHP, but that's not the concern of this sniff.
namespace\assert('mysql_query("")');
\Fully\Qualified\assert('mysql_query("")');
Partially\Qualified\assert('mysql_query("")');
