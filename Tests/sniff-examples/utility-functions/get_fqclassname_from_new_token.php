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
