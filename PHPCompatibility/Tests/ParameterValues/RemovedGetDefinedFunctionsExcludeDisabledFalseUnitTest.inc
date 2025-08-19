<?php
/*
 * Test get_defined_functions() PHP 8.0 change in accepted values.
 */

// OK.
get_defined_functions();
\get_defined_functions(exclude_disabled: $exclude_disabled);

// Not OK.
get_defined_functions(false);
\Get_Defined_Functions(exclude_disabled: false);

// Safeguard against false positives on method calls and namespaced function calls.
ClassName::get_defined_functions(false);
$obj->get_defined_functions(false);
$obj?->get_defined_functions(false);
namespace\get_defined_functions(false);
\Fully\Qualified\get_defined_functions(false);
Partially\Qualified\get_defined_functions(false);

// Handle fully qualified and uppercase false, not OK.
get_defined_functions(FALSE);
\get_defined_functions(\false);
