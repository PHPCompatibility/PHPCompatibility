<?php

class MyCountable implements Countable {}
class MyOuterIterator implements OuterIterator {}
class MyRecursiveIterator implements RecursiveIterator {}
class MySeekableIterator implements SeekableIterator {}
class MySerializable implements Serializable {
	public function __sleep() {}
	public function __wakeup() {}
}
class MySplObserver implements SplObserver {}
class MySplSubject implements SplSubject {}
class MyJsonSerializable implements JsonSerializable {}
class MySessionHandlerInterface implements SessionHandlerInterface {}

// Test multiple interfaces
class MyMultiple implements SplSubject, SeekableIterator, Countable {}

// Test case-insensitive matching
class MyUppercase implements COUNTABLE {}
class MyLowercase implements countable {}

// These shouldn't throw errors.
class MyJsonSerializable implements JsonSerializableSomething {}
class MyJsonSerializable implements myNameSpace\JsonSerializable {}
