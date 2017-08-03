<?php

// Some other type casts.
(string) 1234;
(real) '1.5';

// Deprecated introduced type casts.
(unset) $a;

// Verify space & case independency.
(	unset	) $a;
( Unset ) $a;
