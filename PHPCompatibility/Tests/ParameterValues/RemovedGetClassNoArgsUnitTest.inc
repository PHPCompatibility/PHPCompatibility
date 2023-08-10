<?php
/*
 * Test get_[parent_]class() should no longer be called without arguments since PHP 8.3.
 */

/*
 * OK.
 */
// Not our target.
echo GET_CLASS;
$obj->get_called_class;
$obj->get_class();
$obj?->get_called_class();
MyClass::get_class();
My\Vendor\get_called_class();
#[Get_Class()]
function do_something() {}

get_class($object);
get_parent_class($object_or_class);
\get_parent_class(ClassName::class);
Get_Parent_Class('ClassName');

// Not allowed since PHP 7.2, but not the concern of this sniff.
get_class(null);

/*
 * PHP 8.3: calling the functions without arguments.
 */
get_class();
\get_parent_class();
Get_Class( /* comment */ );
get_parent_class(
    /* comment */
);

// Also not OK: wrong parameter name used.
\get_class(something_else: $object);
get_parent_class(something_else: $object_or_class);
