<?php
/*
 * Test get_class() PHP 7.2 change in accepted values.
 */

//OK.
get_class($object);
\get_class();

// Not OK.
\get_class(null);
Get_class(
    null // Comment.
);
get_class(object:null);

// Safeguard against false positives when target param not found.
get_class(instance: null); // Wrong param name.

// Safeguard against false positives on method calls and namespaced function calls.
ClassName::get_class(null);
$obj->get_class(null);
$obj?->get_class(null);
namespace\get_class(null);
\Fully\Qualified\get_class(null);
Partially\Qualified\get_class(null);

// Handle fully qualified and uppercase null, not OK.
get_class(NULL);
\get_class(\null);
