<?php

/* test 1 */
class MyTest {}
/* test 2 */
class MyTestX extends DateTime {}


namespace MyTesting;

/* test 3 */
class MyTestA extends DateTime {}
/* test 4 */
class MyTestB extends \DateTime {}
/* test 5 */
class MyTestD extends anotherNS\DateTime {}
/* test 6 */
class MyTestE extends \FQNS\DateTime {}


namespace AnotherTesting {
    /* test 7 */
    class MyTestF extends DateTime {}
    /* test 8 */
    class MyTestG extends \DateTime {}
    /* test 9 */
    class MyTestI extends anotherNS\DateTime {}
    /* test 10 */
    class MyTestJ extends \FQNS\DateTime {}
}


/* test 11 */
class MyTestK extends DateTime {}
/* test 12 */
class MyTestL extends \DateTime {}


namespace Yet\More\Testing;

/* test 13 */
class MyTestN extends DateTime {}
/* test 14 */
class MyTestO extends anotherNS\DateTime {}
/* test 15 */
class MyTestP extends \FQNS\DateTime {}

/* test 16 */
interface MyInterface extends \SomeInterface {}
/* test 17 */
interface MyInterface extends SomeInterface {}
