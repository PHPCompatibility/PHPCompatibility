<?php
namespace MyTesting;

class MyTestA extends DateTime {}
class MyTestB extends \DateTime {}
class MyTestD extends anotherNS\DateTime {}
class MyTestE extends \FQNS\DateTime {}

namespace AnotherTesting {
	class MyTestF extends DateTime {}
	class MyTestG extends \DateTime {}
	class MyTestI extends anotherNS\DateTime {}
	class MyTestJ extends \FQNS\DateTime {}
}

class MyTestK extends DateTime {}
class MyTestL extends \DateTime {}

namespace Yet\More\Testing;

class MyTestN extends DateTime {}
class MyTestO extends anotherNS\DateTime {}
class MyTestP extends \FQNS\DateTime {}
