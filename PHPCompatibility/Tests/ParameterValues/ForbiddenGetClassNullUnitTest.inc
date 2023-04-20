<?php
/*
 * Test get_class() PHP 7.2 change in accepted values.
 */

//OK.
get_class($object);
get_class();

// Not OK.
get_class(null);
get_class(
    null // Comment.
);
get_class(object:null);

// Safeguard against false positives when target param not found.
get_class(instance: null); // Wrong param name.
