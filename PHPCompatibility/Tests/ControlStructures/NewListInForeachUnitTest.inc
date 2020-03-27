<?php

/*
 * Valid cross-version.
 */
foreach ([1, 2, 3, 4, 5] as $user) {
	list($id, $name) = $user;
}


/*
 * PHP 5.5: support for unpacking nested arrays with list() in foreach.
 */
foreach ($data as list($id, $name)) {}

// Make sure it's also detected when using PHP 7.1 short list syntax.
foreach ( $data as [ $id, $name ] ) {}
foreach ($data as list("id" => $id, "name" => $name)) {}
foreach ($data as ['id' => $id, 'name' => $name]) {}

// Check correctly recognizing nested short lists in foreach.
foreach ($data as [$id, [$name, $address]]) {}
foreach ($data as $key => [$id, [$name, $address]]) {}

// Make sure there's no false positives on incorrectly tokenized short array tokens in older PHPCS versions.
foreach ( $data as $this->prop['key'] ) {}
