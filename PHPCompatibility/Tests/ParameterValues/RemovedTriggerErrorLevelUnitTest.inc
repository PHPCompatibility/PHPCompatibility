<?php

// Not our target.
$obj?->trigger_error($message, E_USER_ERROR);
MyClass::trigger_error($message, E_USER_ERROR);
My\Ns\trigger_error($message, E_USER_ERROR);
$class = new trigger_error($message, E_USER_ERROR);

// Ignore, optional target param not passed.
trigger_error($message);
trigger_error($message, level: false); // Typo in param name.

// OK.
trigger_error($message, E_USER_WARNING);
trigger_error($message, /*comment*/ E_USER_NOTICE /*comment*/);
trigger_error($message, \E_USER_DEPRECATED);
trigger_error($message, 1024);

// Invalid, but not our concern.
trigger_error($message, e_user_error); // Invalid, undefined constant.
trigger_error($message, 'invalid, error_level must be an integer'); // Invalid, type error.

// Undetermined. Ignore.
trigger_error($message, $error_level);
trigger_error($message, MyClass::E_USER_ERROR);
trigger_error($message, \get_error_level());

// Not OK - deprecated in PHP >= 8.4.
trigger_error($message, E_USER_ERROR);
trigger_error($message, 256);
trigger_error(
    $message,
    // Comment.
    \E_USER_ERROR // phpcs:ignore Stnd.Cat.Sniff -- for (testing) reasons.
);
trigger_error(error_level: E_USER_ERROR, message: $message);
