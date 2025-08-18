<?php

// OK, no second param.
assert('mysql_query("")');

// OK, second param string.
assert("2 < 1", 'Two is less than one');
\assert(false, 'Unexpected condition');

// Undetermined. Ignore.
assert($assertion, $errorMsg);

// Ok: Custom error message via __toString()
assert(false, (string) new CustomError('True is not false!'));

// PHP 7.0+ custom exception.
class CustomError extends AssertionError {}
\assert(false, new CustomError('True is not false!'));
Assert(false, new CustomError((string) $otherObj));

// Safeguard against false positives on method calls and namespaced function calls.
ClassName::assert(false, new CustomError('True is not false!'));
$obj->assert(false, new CustomError('True is not false!'));
$obj?->assert(false, new CustomError('True is not false!'));
// (Re)defining a custom assert function is not allowed in PHP, but that's not the concern of this sniff.
namespace\assert(false, new CustomError('True is not false!'));
\Fully\Qualified\assert(false, new CustomError('True is not false!'));
Partially\Qualified\assert(false, new CustomError('True is not false!'));
