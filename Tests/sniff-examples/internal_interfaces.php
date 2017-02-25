<?php

class MyTraversable implements Traversable {}
class MyDateTimeInterface implements DateTimeInterface {}
class MyThrowable implements Throwable {}

class MyMultiple implements SomeInterface, Throwable, AnotherInterface, Traversable {} // Test multiple interfaces.

class MyUppercase implements DATETIMEINTERFACE {} // Test case-insensitivity.
class MyLowercase implements datetimeinterface {} // Test case-insensitivity.

// These shouldn't throw errors.
class MyTraversable implements TraversableSomething {}
class MyTraversable implements myNameSpace\Traversable {}
