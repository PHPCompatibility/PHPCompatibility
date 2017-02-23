<?php
namespace MyTesting;

new namespace\DateTime();
new DateTime;
new \DateTime();
new anotherNS\DateTime();
new \FQNS\DateTime();

namespace AnotherTesting {
	new namespace\DateTime();
	new DateTime;
	new \DateTime();
	new anotherNS\DateTime();
	new \FQNS\DateTime();
}

new DateTime;
new \DateTime;
new \AnotherTesting\DateTime();

// Variant on issue #205.
$className = 'DateTime';
new $className;

// Issue #338 - no infinite loop on unfinished code.
$var = new
