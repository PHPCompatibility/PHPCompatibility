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

// Safeguard handling of PHP 8.1+ enums implementing internal interfaces.
enum ImplementsNothing {} // OK.
enum MyOkayEnum implements SomeInterface, AnotherInterface {} // OK.
enum MyTraversableEnum implements Traversable {} // Error.
enum MyDateTimeEnum implements DateTimeInterface {} // Error.
enum MyThrowableEnum implements \Throwable {} // Error.
enum MyMultipleEnum implements SomeInterface, Throwable, AnotherInterface, Traversable {} // Test multiple interfaces.

// New PHP 8.1 interfaces which cannot be implemented or extended. Only for use in type declarations.
enum MyEnum implements UnitEnum {}
interface MyUnitEnum extends UnitEnum {}
class MyEnumUser implements BackedEnum {}
interface MyBackedEnum extends BackedEnum {}
