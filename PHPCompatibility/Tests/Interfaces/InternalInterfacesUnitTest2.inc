<?php

use Foo\Bar\DateTimeInterface;
use Foo\Bar\SomeException as Throwable;
use Baz\{UnitEnum, BackedEnum, SomeInterface as Traversable};

// These don't implement internal interfaces and shouldn't throw errors.
class MyDateTimeClass implements DateTimeInterface {}
class MyExceptionClass implements Throwable {}
class MyUnitEnumClass implements UnitEnum {}
class MyBackedEnumClass implements BackedEnum {}
class MyTraversableClass implements Traversable {}
