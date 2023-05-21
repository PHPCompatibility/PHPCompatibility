<?php

class MyCountable implements Countable {}
class MyOuterIterator implements OuterIterator {}
class MyRecursiveIterator implements RecursiveIterator {}
class MySeekableIterator implements \SeekableIterator {}
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

// Test anonymous class support.
$a = new class implements SeekableIterator {};
$b = new class implements \Serializable {
    public function __sleep() {}
    public function __wakeup() {}
};

// Additional new interfaces.
class MyTraversable implements Traversable {}
class MyDateTimeInterface implements DateTimeInterface {}
class MyThrowable implements Throwable {}

class MyClass {
    // Interfaces as typehints.
    function CountableTypeHint( Countable $a ) {}
    function OuterIteratorTypeHint( OuterIterator $a ) {}
    function RecursiveIteratorTypeHint( RecursiveIterator $a ) {}
    function SeekableIteratorTypeHint( SeekableIterator $a ) {}
    function SerializableTypeHint( Serializable $a ) {}
    function SplObserverTypeHint( SplObserver $a ) {}
    function SplSubjectTypeHint( SplSubject $a ) {}
    function JsonSerializableTypeHint( JsonSerializable $a ) {}
    function SessionHandlerInterfaceTypeHint( SessionHandlerInterface $a ) {}
    function TraversableTypeHint( Traversable $a ) {}
    function DateTimeInterfaceTypeHint( DateTimeInterface $a ) {}
    function ThrowableTypeHint( Throwable $a ) {}

    // Namespaced interfaces as typehints
    function SerializableTypeHint( \Serializable $a ) {} // Error: global namespace.
    function SplObserverTypeHint( myNameSpace\SplObserver $a ) {} // Ok.
    function SplSubjectTypeHint( \some\other\SplSubject $a ) {} // Ok.

    // Interfaces as nullable typehints (PHP 7.1+).
    function TraversableTypeHint( ?Traversable $a ) {}
    function DateTimeInterfaceTypeHint( ?\DateTimeInterface $a ) {}
    function ThrowableTypeHint( ?Throwable $a ) {}

    // Function with multiple typehinted parameters.
    function MultipleTypeHints (OuterIterator $a, ?int $b, SplObserver $c, ?\RecursiveIterator $d) {}
}

// Interfaces in type hints in anonymous functions.
function ( SplSubject $a ) {}
function(\Serializable $a) {}
function ( ?Traversable $a ) {}
function(myNameSpace\SplObserver $a) {} // Ok.

// Additional new interfaces.
class MyReflector implements Reflector {}

// Test recognition of return type declarations.
function CountableReturnTypeHint( $a ) : COUNTABLE {}
function TraversableReturnTypeHint( $a ) : ?Traversable {}
function DateTimeInterfaceReturnTypeHint( $a ) : \DateTimeInterface {}
function($a):throwable {}

// Test against false positives for return type declarations.
function SeekableIteratorReturnTypeHint( $a ) : SomeNS\SeekableIterator {}
function SessionHandlerInterfaceReturnTypeHint( $a ) : ?\MyNS\SessionHandlerInterface {}
function SplSubjectReturnTypeHint( $a ) : \MyNS\SplSubject\AnotherInterface {}

// More new interfaces.
class MySessionIdInterface implements SessionIdInterface {}
class MySessionUpdateTimestampHandlerInterface implements SessionUpdateTimestampHandlerInterface {}

try {
} catch (Throwable $e) {
}

// Multi-catch.
try {
} catch (Throwable | \RuntimeException $e) {
}

// Global namespace, should throw error.
try {
} catch (\Throwable $e) {
}

// Namespaced, should be ignored.
try {
} catch (\My\Except\Throwable $e) {
}

// PHP 8.0 new interfaces.
function StringableTypeHint( Stringable $a ) {}

// Test interfaces extending interfaces.
interface DoesNotExtend {}
interface MyReflection extends Reflector {}
interface MySession extends SessionABC, SessionIdInterface {}

interface NewSerial extends \Serializable {
    public function __sleep();
    public function __wakeup();
}

// Function declarations nested in methods are not magic.
class Nested implements Serializable {
    public function declaredNested() {
        function __sleep() {}
        function __wakeup() {}
    }
}

// Test error line precision.
function errorLinePrecisionCheck(
    JsonSerializable $param
) : JsonSerializable {}

// Test support for PHP 7.4 property types.
public Throwable $var; // Ignore, not a property, parse error.

$anon = class() {
    public $untypedProperty;
    public SessionUpdateTimestampHandlerInterface $property;
};

// Test support for PHP 7.4 arrow functions.
$fn = fn(SessionIdInterface $param) => $param;
$fn = fn($param): SessionHandlerInterface => $param;

// Test support for PHP 8.0 union types.
class UnionTypes {
    public NotATarget|AlsoNotATarget $okay;

    public Countable|SplObserver $property;
    public function paramType(OuterIterator|RecursiveIterator $param) {}
    public function returnType($param) : SessionHandlerInterface|JsonSerializable {}
}

// Test support for PHP 8.1 intersection types.
class IntersectionTypes {
    public function paramType(NotATarget&AlsoNotATarget $okay) {}

    public Throwable&SessionUpdateTimestampHandlerInterface $property;
    public function paramType(SeekableIterator&SplSubject $param) {}
    public function returnType($param) : Reflector&Traversable {}
}

// Test very invalid type.
function InvalidType($param): ?\&\SomeType {}

// Test invalid catch.
try {
} catch ($e) {}

// Test support with PHP 8.0 constructor property promotion.
class ConstructorProp {
    public function __construct(
        protected $property,
        public Reflector | \ | ParseErrorMissingTypeBetweenUnion $reflProp,
        private Stringable $stringProp,
    ) {}
}

// Test support with PHP 8.0 non-capturing catch.
try {
} catch (SomeException) {}
} catch (MyException | Throwable | AnotherException) {}

// Test parse error/live coding.
// This MUST be the last test in the file!
try {
} catch (
