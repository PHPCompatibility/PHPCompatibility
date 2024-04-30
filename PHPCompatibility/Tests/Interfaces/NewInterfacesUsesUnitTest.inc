<?php

use NothingHere\Traversable;
use NothingHere\Reflector;
use NothingHere\Countable;
use NothingHere\OUTERiterator;
use NothingHere\RecursiveIterator;
use NothingHere\SeekableIterator;
use NothingHere\Serializable;
use NothingHere\SplObserver;
use NothingHere\SplSubject;
use NothingHere\JsonSerializable;
use NothingHere\SessionHandlerInterface;
use NothingHere\DateTimeInterface;
use NothingHere\SessionIdInterface;
use NothingHere\Throwable;
use NothingHere\SessionUpdateTimestampHandlerInterface;
use NothingHere\Stringable;
use NothingHere\DOMChildNode;
use NothingHere\DOMParentNode;
use NothingHere\UnitEnum;
use NothingHere\BackedEnum;
use NothingHere\Random\ {Engine, CryptoSafeEngine};


class MyTraversable implements Traversable{}
class MyReflector implements Reflector, Countable{}
class MyOuterIterator implements OUTERiterator{}
class MySerializable implements Serializable{}
class MySplObserver implements SplObserver{}
class MySplSubject implements SplSubject{}
class MyJsonSerializable implements JsonSerializable{}
class MySessionHandlerInterface implements SessionHandlerInterface{}
class MyDateTimeInterface implements DateTimeInterface{}
class MySessionIdInterface implements SessionIdInterface{}
class MyThrowable implements Throwable{}
class MySessionUpdateTimestampHandlerInterface implements SessionUpdateTimestampHandlerInterface{}
class MyStringable implements Stringable{}
class MyDOMChildNode implements DOMChildNode{}
class MyDOMParentNode implements DOMParentNode{}
class MyUnitEnum implements UnitEnum{}
class MyRandomEngine implements Engine{}
class MyRandomCryptoSafeEngine implements CryptoSafeEngine{}

// Test anonymous class support.
$a = new class implements BackedEnum {
};

class MyClass
{
    private JsonSerializable $asd;

    // Interfaces as typehints.
    function CountableTypeHint(RecursiveIterator $a): RecursiveIterator{}
    // Interfaces as typehints.
    function CountableTypeHint(SeekableIterator $a): ?SeekableIterator{}
}

try {
} catch (Throwable $e) {
}
