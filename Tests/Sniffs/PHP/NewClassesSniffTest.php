<?php
/**
 * New Classes Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New Classes Sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class NewClassesSniffTest extends BaseSniffTest
{
    /**
     * Sniffed file
     *
     * @var PHP_CodeSniffer_File
     */
    protected $_sniffFile;

    /**
     * Test http post vars
     *
     * @return void
     */
    public function testDateTime()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.1');
        $this->assertError($file, 3, "The built-in class DateTime is not present in PHP version 5.1 or earlier");

        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertNoViolation($file, 3);
    }

    /**
     * Test date time zone
     *
     * @return void
     */
    public function testDateTimeZone()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.1');
        $this->assertError($file, 4, "The built-in class DateTimeZone is not present in PHP version 5.1 or earlier");
    }

    /**
     * Test RegexIterator
     *
     * @return void
     */
    public function testRegexIterator()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.1');
        $this->assertError($file, 5, "The built-in class RegexIterator is not present in PHP version 5.1 or earlier");
    }

    /**
     * Test RecursiveRegexIterator
     *
     * @return void
     */
    public function testRecursiveRegexIterator()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.1');
        $this->assertError($file, 6, "The built-in class RecursiveRegexIterator is not present in PHP version 5.1 or earlier");
    }

    /**
     * Test DateInterval
     *
     * @return void
     */
    public function testDateInterval()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 7, "The built-in class DateInterval is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test DatePeriod
     *
     * @return void
     */
    public function testDatePeriod()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 8, "The built-in class DatePeriod is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test Phar
     *
     * @return void
     */
    public function testPhar()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 9, "The built-in class Phar is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test PharData
     *
     * @return void
     */
    public function testPharData()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 10, "The built-in class PharData is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test PharException
     *
     * @return void
     */
    public function testPharException()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 11, "The built-in class PharException is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test PharFileInfo
     *
     * @return void
     */
    public function testPharFileInfo()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 12, "The built-in class PharFileInfo is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test FilesystemIterator
     *
     * @return void
     */
    public function testFilesystemIterator()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 13, "The built-in class FilesystemIterator is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test GlobIterator
     *
     * @return void
     */
    public function testGlobIterator()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 14, "The built-in class GlobIterator is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test MultipleIterator
     *
     * @return void
     */
    public function testMultipleIterator()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 15, "The built-in class MultipleIterator is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test RecursiveTreeIterator
     *
     * @return void
     */
    public function testRecursiveTreeIterator()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 16, "The built-in class RecursiveTreeIterator is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test SplDoublyLinkedList
     *
     * @return void
     */
    public function testSplDoubleLinkedList()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 17, "The built-in class SplDoublyLinkedList is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test SplFixedArray
     *
     * @return void
     */
    public function testSplFixedArray()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 18, "The built-in class SplFixedArray is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test SplHeap
     *
     * @return void
     */
    public function testSplHeap()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 19, "The built-in class SplHeap is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test SplMaxHeap
     *
     * @return void
     */
    public function testSplMaxHeap()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 20, "The built-in class SplMaxHeap is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test SplMinHeap
     *
     * @return void
     */
    public function testSplMinHeap()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 21, "The built-in class SplMinHeap is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test SplPriorityQueue
     *
     * @return void
     */
    public function testSplPriorityQueue()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 22, "The built-in class SplPriorityQueue is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test SplQueue
     *
     * @return void
     */
    public function testSplQueue()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 23, "The built-in class SplQueue is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test SplStack
     *
     * @return void
     */
    public function testSplStack()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.2');
        $this->assertError($file, 24, "The built-in class SplStack is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test CallbackFilterIterator
     *
     * @return void
     */
    public function testCallbackFilterIterator()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.3');
        $this->assertError($file, 25, "The built-in class CallbackFilterIterator is not present in PHP version 5.3 or earlier");
    }

    /**
     * Test RecursiveCallbackFilterIterator
     *
     * @return void
     */
    public function testRecursiveCallbackFilterIterator()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.3');
        $this->assertError($file, 26, "The built-in class RecursiveCallbackFilterIterator is not present in PHP version 5.3 or earlier");
    }

    /**
     * Test ReflectionZendExtension
     *
     * @return void
     */
    public function testReflectionZendExtension()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.3');
        $this->assertError($file, 27, "The built-in class ReflectionZendExtension is not present in PHP version 5.3 or earlier");
    }

    /**
     * Test JsonSerializable
     *
     * @return void
     */
    public function testJsonSerializable()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.3');
        $this->assertError($file, 28, "The built-in class JsonSerializable is not present in PHP version 5.3 or earlier");
    }

    /**
     * Test SessionHandler
     *
     * @return void
     */
    public function testSessionHandler()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.3');
        $this->assertError($file, 29, "The built-in class SessionHandler is not present in PHP version 5.3 or earlier");
    }

    /**
     * Test SNMP
     *
     * @return void
     */
    public function testSNMP()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.3');
        $this->assertError($file, 30, "The built-in class SNMP is not present in PHP version 5.3 or earlier");
    }

    /**
     * Test Transliterator
     *
     * @return void
     */
    public function testTransliterator()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.3');
        $this->assertError($file, 31, "The built-in class Transliterator is not present in PHP version 5.3 or earlier");
    }

    /**
     * Test CURLFile
     *
     * @return void
     */
    public function testCURLFile()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.4');
        $this->assertError($file, 32, "The built-in class CURLFile is not present in PHP version 5.4 or earlier");
    }

    /**
     * Test DateTimeImmutable
     *
     * @return void
     */
    public function testDateTimeImmutable()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.4');
        $this->assertError($file, 33, "The built-in class DateTimeImmutable is not present in PHP version 5.4 or earlier");
    }

    /**
     * Test 
     *
     * @return void
     */
    public function testIntlCalendar()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.4');
        $this->assertError($file, 34, "The built-in class IntlCalendar is not present in PHP version 5.4 or earlier");
    }

    /**
     * Test IntlGregorianCalendar
     *
     * @return void
     */
    public function testIntlGregorianCalendar()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.4');
        $this->assertError($file, 35, "The built-in class IntlGregorianCalendar is not present in PHP version 5.4 or earlier");
    }

    /**
     * Test IntlTimeZone
     *
     * @return void
     */
    public function testIntlTimeZone()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.4');
        $this->assertError($file, 36, "The built-in class IntlTimeZone is not present in PHP version 5.4 or earlier");
    }

    /**
     * Test IntlBreakIterator
     *
     * @return void
     */
    public function testIntlBreakIterator()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.4');
        $this->assertError($file, 37, "The built-in class IntlBreakIterator is not present in PHP version 5.4 or earlier");
    }

    /**
     * Test IntlRuleBasedBreakIterator
     *
     * @return void
     */
    public function testIntlRuleBasedBreakIterator()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.4');
        $this->assertError($file, 38, "The built-in class IntlRuleBasedBreakIterator is not present in PHP version 5.4 or earlier");
    }

    /**
     * Test IntlCodePointBreakIterator
     *
     * @return void
     */
    public function testIntlCodePointBreakIterator()
    {
        $file = $this->sniffFile('sniff-examples/new_classes.php', '5.4');
        $this->assertError($file, 39, "The built-in class IntlCodePointBreakIterator is not present in PHP version 5.4 or earlier");
    }
}
