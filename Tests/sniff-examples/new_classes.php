<?php

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
$test = new JsonSerializable();
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

$okay = new StdClass();

$test = new DateTime;

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
