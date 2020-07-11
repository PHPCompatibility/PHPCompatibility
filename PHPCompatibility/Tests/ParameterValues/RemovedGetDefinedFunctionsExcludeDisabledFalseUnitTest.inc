<?php
/*
 * Test get_defined_functions() PHP 8.0 change in accepted values.
 */

// OK.
get_defined_functions();
get_defined_functions($exclude_disabled);

// Not OK.
get_defined_functions(false);
