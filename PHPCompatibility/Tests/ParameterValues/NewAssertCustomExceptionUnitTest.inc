<?php

// OK, no second param.
assert('mysql_query("")');

// OK, second param string.
assert("2 < 1", 'Two is less than one');
assert(false, 'Unexpected condition');

// Undetermined. Ignore.
assert($assertion, $errorMsg);

// Ok: Custom error message via __toString()
assert(false, (string) new CustomError('True is not false!'));

// PHP 7.0+ custom exception.
class CustomError extends AssertionError {}
assert(false, new CustomError('True is not false!'));
assert(false, new CustomError((string) $otherObj));
