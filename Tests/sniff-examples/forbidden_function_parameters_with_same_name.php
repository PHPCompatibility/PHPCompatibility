<?php

function foo($a, $b, $unused, $unused) { }

function foobaz() {} // No parameters = no error.

// Don't throw errors during live code review.
function foobar($a,$a
