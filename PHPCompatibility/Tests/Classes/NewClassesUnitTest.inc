<?php

/*
 * These should not be flagged.
 */
$okay = new StdClass();
$okay = new \myNamespace\DateTime();
$okay = \myNamespace\DateTime::static_method();
$okay = SomeNamespace\DateTime::static_method();
// Left empty for additional test cases to be added.

/*
 * 1. Verify instantiation without parameters is being flagged.
 * 2. + 3. Verify that instantion with spacing/comments between elements is being flagged.
 * 4. Verify that instation with global namespace indicator is being flagged.
 */
$test = new DateInterval;
$test = new DateInterval ();
$test = new /*comment*/ DateInterval();
$test = new \DateInterval();

/*
 * These should all be flagged.
 */
$test = new DateTime ();
$test = new DateTimeZone();
$test = new RegexIterator();
$test = new RecursiveRegexIterator();
$test = new DateInterval();
$test = new DatePeriod();
$test = new Phar();
$test = new PharData();
$test = new PharException();
$test = new PharFileInfo();
$test = new FilesystemIterator();
$test = new GlobIterator();
$test = new MultipleIterator();
$test = new RecursiveTreeIterator();
$test = new SplDoublyLinkedList();
$test = new SplFixedArray();
$test = new SplHeap();
$test = new SplMaxHeap();
$test = new SplMinHeap();
$test = new SplPriorityQueue();
$test = new SplQueue();
$test = new SplStack();
$test = new CallbackFilterIterator();
$test = new RecursiveCallbackFilterIterator();
$test = new ReflectionZendExtension();
$test = new SessionHandler();
$test = new SNMP();
$test = new Transliterator();
$test = new CURLFile();
$test = new DateTimeImmutable();
$test = new IntlCalendar();
$test = new IntlGregorianCalendar();
$test = new IntlTimeZone();
$test = new IntlBreakIterator();
$test = new IntlRuleBasedBreakIterator();
$test = new IntlCodePointBreakIterator();
$test = new libXMLError();



class MyDateTime extends DateTime {}
class MyDateTimeZone extends DateTimeZone {}
class MyRegexIterator extends RegexIterator {}
class MyRecursiveRegexIterator extends RecursiveRegexIterator {}
class MyDateInterval extends DateInterval {}
class MyDatePeriod extends DatePeriod {}
class MyPhar extends Phar {}
class MyPharData extends PharData {}
class MyPharException extends PharException {}
class MyPharFileInfo extends PharFileInfo {}
class MyFilesystemIterator extends FilesystemIterator {}
class MyGlobIterator extends GlobIterator {}
class MyMultipleIterator extends MultipleIterator {}
class MyRecursiveTreeIterator extends RecursiveTreeIterator {}
class MySplDoublyLinkedList extends SplDoublyLinkedList {}
class MySplFixedArray extends SplFixedArray {}
abstract class MySplHeap extends SplHeap {}
class MySplMaxHeap extends SplMaxHeap {}
class MySplMinHeap extends SplMinHeap {}
class MySplPriorityQueue extends SplPriorityQueue {}
class MySplQueue extends SplQueue {}
class MySplStack extends SplStack {}
class MyCallbackFilterIterator extends CallbackFilterIterator {}
class MyRecursiveCallbackFilterIterator extends RecursiveCallbackFilterIterator {}
class MyReflectionZendExtension extends ReflectionZendExtension {}
class MySessionHandler extends SessionHandler {}
class MySNMP extends SNMP {}
class MyTransliterator extends Transliterator {}
class MyCURLFile extends CURLFile {}
class MyDateTimeImmutable extends DateTimeImmutable {}
class MyIntlCalendar extends IntlCalendar {}
class MyIntlGregorianCalendar extends IntlGregorianCalendar {}
class MyIntlTimeZone extends IntlTimeZone {}
class MyIntlBreakIterator extends IntlBreakIterator {}
class MyIntlRuleBasedBreakIterator extends IntlRuleBasedBreakIterator {}
class MyIntlCodePointBreakIterator extends IntlCodePointBreakIterator {}
class MylibXMLError extends libXMLError {}



DateTime::static_method();
DateTimeZone::static_method();
RegexIterator::static_method();
RecursiveRegexIterator::static_method();
DateInterval::static_method();
DatePeriod::static_method();
Phar::static_method();
PharData::static_method();
PharException::static_method();
PharFileInfo::static_method();
FilesystemIterator::static_method();
GlobIterator::static_method();
MultipleIterator::static_method();
RecursiveTreeIterator::static_method();
SplDoublyLinkedList::static_method();
SplFixedArray::CLASS_CONSTANT;
SplHeap::CLASS_CONSTANT;
SplMaxHeap::CLASS_CONSTANT;
SplMinHeap::CLASS_CONSTANT;
SplPriorityQueue::CLASS_CONSTANT;
SplQueue::CLASS_CONSTANT;
SplStack::CLASS_CONSTANT;
CallbackFilterIterator::CLASS_CONSTANT;
RecursiveCallbackFilterIterator::CLASS_CONSTANT;
ReflectionZendExtension::CLASS_CONSTANT;
SessionHandler::$static_property;
SNMP::$static_property;
Transliterator::$static_property;
CURLFile::$static_property;
DateTimeImmutable::$static_property;
IntlCalendar::$static_property;
IntlGregorianCalendar::$static_property;
IntlTimeZone::$static_property;
IntlBreakIterator::$static_property;
IntlRuleBasedBreakIterator::$static_property;
IntlCodePointBreakIterator::$static_property;
libXMLError::$static_property;

/*
 * These should all be flagged too as classnames are case-insensitive.
 */
$test = new DATETIME(); // Uppercase.
class MyDateTime extends datetime {} // Lowercase.
dATeTiMe::static_method(); // Mixed case.

// Test anonymous classes extending a new class.
$snon = new class extends DateTime {};
new class extends \Phar {};
$snon = new class extends SplMinHeap {};
new class extends \Transliterator {};

// Check against false positives.
$snon = new class extends \My\IntlRuleBasedBreakIterator {};
$snon = new class extends My\IntlRuleBasedBreakIterator {};

class MyClass {
    // New Classes as typehints.
    function DateTimeZoneTypeHint( DateTimeZone $a ) {}
    function RegexIteratorTypeHint( RegexIterator $a ) {}
    function SplHeapTypeHint( SplHeap $a ) {}
    function IntlCalendarTypeHint( IntlCalendar $a ) {}

    // Namespaced classes as typehints.
    function GlobIteratorTypeHint( \GlobIterator $a ) {} // Error: global namespace.
    function SplQueueTypeHint( myNameSpace\SplQueue $a ) {} // Ok.
    function CURLFileTypeHint( \some\other\CURLFile $a ) {} // Ok.

    // New classes as nullable typehints (PHP 7.1+).
    function DatePeriodTypeHint( ?DatePeriod $a ) {}
    function FilesystemIteratorTypeHint( ?\FilesystemIterator $a ) {}
}

// New classes as type hints in anonymous functions.
function ( MultipleIterator $a ) {}
function(\RecursiveCallbackFilterIterator $a) {}
function ( ?SNMP $a ) {}
function(myNameSpace\IntlTimeZone $a) {} // Ok.

/*
 * Exception classes should be caught too.
 */
throw new DomainException($msg);
throw new ReflectionException($msg);


class MyException extends Exception {}
class MyException extends UnexpectedValueException {}


ErrorException::static_method();
LengthException::static_method();
OverflowException::CLASS_CONSTANT;
UnderflowException::CLASS_CONSTANT;
PDOException::$static_property;


$snon = new class extends BadFunctionCallException {};
$snon = new class extends mysqli_sql_exception {};
$snon = new class extends DivisionByZeroError {};

class MyExceptionHandler {
    // New Exceptions as typehints.
    function ExceptionTypeHint( BadMethodCallException $e ) {}
    function ExceptionTypeHint( RangeException $e ) {}
    function ExceptionTypeHint( ArithmeticError $e ) {}

}

// New exceptions as type hints in anonymous functions.
function ( Error $e ) {}

try {
} catch (Exception $e) {
} catch (ErrorException $e) {
} catch (BadFunctionCallException $e) {
} catch (BadMethodCallException $e) {
} catch (DomainException $e) {
} catch (InvalidArgumentException $e) {
} catch (LengthException $e) {
} catch (LogicException $e) {
} catch (OutOfBoundsException $e) {
} catch (OutOfRangeException $e) {
} catch (OverflowException $e) {
} catch (RangeException $e) {
} catch (RuntimeException $e) {
} catch (UnderflowException $e) {
} catch (UnexpectedValueException $e) {
} catch (DOMException $e) {
} catch (mysqli_sql_exception $e) {
} catch (PDOException $e) {
} catch (ReflectionException $e) {
} catch (SoapFault $e) {
} catch (PharException $e) {
} catch (SNMPException $e) {
} catch (IntlException $e) {
} catch (Error $e) {
} catch (ArithmeticError $e) {
} catch (AssertionError $e) {
} catch (DivisionByZeroError $e) {
} catch (ParseError $e) {
} catch (TypeError $e) {


} catch (ArgumentCountError $e) {
} catch (CompileError $e) {
} catch (JsonException $e) {
}

// Multi-catch.
try {
} catch (InvalidArgumentException | \LogicException | OutOfBoundsException | \OutOfRangeException | RuntimeException $e) {
}

// Global namespace, should throw error.
try {
} catch (\DOMException $e) {
}

// Namespaced, should be ignored.
try {
} catch (\My\Except\DOMException $e) {
}

$test = new ZipArchive();
class MyCollator extends Collator {}
NumberFormatter::static_method();
$snon = new class extends Locale {};
function NormalizerTypeHint( \Normalizer $a ) {}
function MessageFormatterTypeHint( \MessageFormatter $a ) {}
$test = new IntlDateFormatter();
class MyResourceBundle extends ResourceBundle {}
UConverter::$static_property;
function IntlCharTypeHint( IntlChar $a ) {}
$snon = new class extends finfo {};
function ClosureTypeHint( Closure $a ) {}
class MyGenerator extends Generator {}
$snon = new class extends GMP {};
class MySplObjectStorage extends SplObjectStorage {}
$test = new ArrayIterator();
$test = new CachingIterator();
$test = new DirectoryIterator();
$test = new RecursiveDirectoryIterator();
$test = new RecursiveIteratorIterator();
class MyAppendIterator extends AppendIterator {}
class MyEmptyIterator extends EmptyIterator {}
$snon = new class extends FilterIterator {};
$snon = new class extends InfiniteIterator {};
IteratorIterator::static_method();
LimitIterator::$static_property;
function NoRewindIteratorTypeHint( \NoRewindIterator $a ) {}
function ParentIteratorTypeHint( \ParentIterator $a ) {}
function RecursiveArrayIteratorTypeHint( \RecursiveArrayIterator $a ) {}
function RecursiveCachingIteratorTypeHint(RecursiveCachingIterator $a) {}
function RecursiveFilterIteratorTypeHint( \RecursiveFilterIterator $a ) {}
$test = new php_user_filter();
class Mytidy extends tidy {}
tidyNode::CLASS_CONSTANT;
$test = new SplFileObject();
$test = new SplFileInfo();
$test = new SplTempFileObject();
class MyArrayObject extends ArrayObject {}
$snon = new class extends ReflectionClassConstant {};
ReflectionFunctionAbstract::CLASS_CONSTANT;
function ReflectionTypeTypeHint( \ReflectionType $a ) {}
function ReflectionGeneratorTypeHint( ReflectionGenerator $a ) {}
class MySimpleXMLElement extends SimpleXMLElement {}
$test = new SimpleXMLIterator();
$test = new XMLReader();
$test = new XMLWriter();
$test = new PDO();
class MyPDOStatement extends PDOStatement {}

// Test recognition of return type declarations.
function DateTimeReturnTypeHint( $a ) : DateTime {}
function DateTimeNullableReturnTypeHint( $a ) : ?DateTime {}
function GlobalNSLcDateTimeReturnTypeHint( $a ) : \datetime {}
function GlobalNSDateTimeNullableReturnTypeHint( $a ) : ?\DateTime {}


// Test against false positives for return type declarations.
function NsDateTimeReturnTypeHint( $a ) : \SomeNamespace\DateTime {}
function NsNullDateTimeReturnTypeHint( $a ) : ?SomeNamespace\DateTime {}
function NoDateTimeReturnTypeHint( $a ) : \SomeNamespace\DateTime\RealClass {}

// Test abstract method declaration recognition.
class TestThis {
	abstract protected function AbstractFunctionParamAndReturnTypeHint( \DateTimeZone $a ) : DateTime;
}

// Test interface method declaration recognition.
interface TestThat {
	public function InterfaceMethodParamAndReturnTypeHint( XMLReader $a ) : \SplFileObject;
}

throw new JsonException();
$test = new SQLiteException();
class MyClosedGeneratorException extends ClosedGeneratorException {}
function SodiumExceptionTypeHint( SodiumException $a ) {}
$test = new com_exception();
$test = new ReflectionReference;
function DateTimeReturnTypeHint( $a ) : WeakReference {}
FFI::load(__DIR__ . "/dummy.h");
function FFITypeHints( FFI\CData $a, FFI\CType $b );
try {
} catch ( FFI\Exception | FFI\ParserException $e ) {}
$hash = new class extends HashContext {};
class MyIntl extends IntlPartsIterator {}
$iterator = new IntlIterator;

$refl = new Reflection();
class MyReflection extends ReflectionClass {}
$anon = new class extends ReflectionExtension {};
$closure = function (ReflectionFunction $fn ) {};
function &MyReflMethod( ReflectionMethod $method ) {}
function MyReflectionType () : ReflectionNamedType {}
function MyReflectionObject () : \ReflectionObject {}
$param = new ReflectionParameter;
echo ReflectionProperty::$prop;

$soap = new SoapClient();
class MySoapServer extends SoapServer() {}
function processA(
    SoapHeader $header) {}
function processB(SoapParam $param) {}
function processC() : SoapVar {}

$com = new COMPersistHelper;

$a = new DOMAttr();
$a = new DOMCdataSection;
$a = new DOMCharacterData();
$a = new DOMComment;
$a = new DOMDocument();
$a = new DOMDocumentFragment;
$a = new DOMDocumentType();
$a = new DOMElement;
$a = new DOMEntity();
$a = new DOMEntityReference;
$a = new DOMImplementation();
$a = new DOMNamedNodeMap;
$a = new DOMNode();
$a = new DOMNodeList;
$a = new DOMNotation();
$a = new DOMProcessingInstruction;
$a = new DOMText();
$a = new DOMXPath;

$anon = new class() extends XSLTProcessor {};

$anon = new class extends SQLiteDatabase {};
echo SQLiteResult::numFields();
function SqlLiteTypeHint(SQLiteUnbuffered $param) {}

$mysqli = new mysqli();
class MyMysqliStmt extends mysqli_stmt {}
function MySqliResultTypeHint( mysqli_result $result){}
$anon = class MyMysqliDriver extends mysqli_driver {};
if (is_a($var, mysqli_warning::class)) {}

//$oci8 = new OCI-Collection();
//$closure = function(OCI-Lob $lob) {};

$conn = new sqlite3();
Class mySqlite3Stmt extends Sqlite3Stmt {}
function MySqlite3ResultTypeHint(SQLite3Result $result) {]

class FooBar {
    public function foo(): WeakMap {};
}

function processToken(PhpToken $stackPtr) {}

try {
    throw new ValueError('Unknown type');
} catch (\ValueError $e) {
}

$type = new ReflectionUnionType();

$oci8 = new OCICollection();
$closure = function(OCILob $lob) {};

try {
} catch (UnhandledMatchError $e) {
}

class MyAttribute extends \Attribute {}

$generator = new IntlDatePatternGenerator();
try {
	$fiber = new Fiber();
	$reflect = new ReflectionFiber($fiber);
} catch( FiberError $e ) {}
$file = new CURLStringFile($data, 'filename.txt', 'text/plain');

// Test support for PHP 7.4 property types.
public WeakMap $var; // Ignore, not a property, parse error.

$anon = class() {
    public Attribute $property;
};

// Test support for PHP 7.4 arrow functions.
$fn = fn(IntlChar $param) => $param;
$fn = fn($param): Fiber => $param;

// Test support for PHP 8.0 union types.
class UnionTypes {
    public NotATarget|AlsoNotATarget $okay;

    public ReflectionReference|WeakReference $property;
    public function paramType(OCICollection|OCILob $param) {}
    public function returnType($param) : Sqlite3Stmt|SQLite3Result {}
}

// Test support for PHP 8.1 intersection types.
class IntersectionTypes {
    public function paramType(NotATarget&AlsoNotATarget $okay) {}

    public IntlPartsIterator&IntlIterator $property;
    public function paramType(\ReflectionClassConstant&\ReflectionNamedType $param) {}
    public function returnType($param) : \FFI\CData&\FFI\CType {}
}

$type = new ReflectionIntersectionType();
