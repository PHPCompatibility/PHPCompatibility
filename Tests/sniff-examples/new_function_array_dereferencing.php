<?php

echo test()[0]; // Error.

echo test()->property[2]; // Ok.

// Don't throw errors during live code review.
echo test(
