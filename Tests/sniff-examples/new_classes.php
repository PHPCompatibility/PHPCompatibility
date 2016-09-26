<?php

/*
 * These should not be flagged.
 */
$okay = new StdClass();
$okay = new \myNamespace\DateTime();
$okay = \myNamespace\DateTime::static_method();
$okay = namespace\DateTime::static_method();
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


/**
 * These should all be flagged too as classnames are case-insensitive.
 */
$test = new DATETIME(); // Uppercase.
class MyDateTime extends datetime {} // Lowercase.
dATeTiMe::static_method(); // Mixed case.
