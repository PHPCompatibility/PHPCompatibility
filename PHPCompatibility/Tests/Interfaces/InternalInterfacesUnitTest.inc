<?php

class MyTraversable implements Traversable {}
class MyDateTimeClass implements DateTimeInterface {}
class MyThrowable implements Throwable {}

class MyMultiple implements SomeInterface, Throwable, AnotherInterface, \Traversable {} // Test multiple interfaces.

class MyUppercase implements DATETIMEINTERFACE {} // Test case-insensitivity.
class MyLowercase implements datetimeinterface {} // Test case-insensitivity.

// These shouldn't throw errors.
class MyTraversable implements TraversableSomething {}
class MyTraversable implements myNameSpace\Traversable {}

// Internal interfaces with anonymous classes.
$a = new class implements Traversable {}
$b = new class implements DateTimeInterface {}
$c = new class implements \Throwable {}
$d = new class implements SomeInterface, Throwable, AnotherInterface, Traversable {} // Test multiple interfaces.

// Safeguard handling of classes which don't implement.
class ImplementsNothing {}
class ImplementsNothingEither extends Foo {}

// DateTimeInterface cannot be extended by userland interfaces. The others can.
interface IDontExtend {} // OK.
interface MyDateTimeInterface extends MyNs\DateTimeInterface, Traversable, Throwable, ArrayAccess {} // OK.
interface MyDateTimeInterface extends ArrayAccess, DateTimeInterface, SomeInterface {} // Error.
