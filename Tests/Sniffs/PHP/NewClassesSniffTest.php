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

    const TEST_FILE = 'sniff-examples/new_classes.php';

    /**
     * testNewClass
     *
     * @dataProvider dataNewClass
     *
     * @param string $className         Class name.
     * @param string $lastVersionBefore The PHP version just *before* the class was introduced.
     * @param array  $lines             The line numbers in the test file which apply to this class.
     * @param string $okVersion         A PHP version in which the class was ok to be used.
     *
     * @return void
     */
    public function testNewClass($className, $lastVersionBefore, $lines, $okVersion)
    {
        $file = $this->sniffFile(self::TEST_FILE, $lastVersionBefore);
        foreach ($lines as $line) {
            $this->assertError($file, $line, "The built-in class {$className} is not present in PHP version {$lastVersionBefore} or earlier");
        }

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testNewClass()
     *
     * @return array
     */
    public function dataNewClass()
    {
        return array(
            array('DateTime', '5.1', array(3, 43, 45), '5.2'),
            array('DateTimeZone', '5.1', array(4, 46), '5.2'),
            array('RegexIterator', '5.1', array(5, 47), '5.2'),
            array('RecursiveRegexIterator', '5.1', array(6, 48), '5.2'),
            array('DateInterval', '5.2', array(7, 49), '5.3'),
            array('DatePeriod', '5.2', array(8, 50), '5.3'),
            array('Phar', '5.2', array(9, 51), '5.3'),
            array('PharData', '5.2', array(10, 52), '5.3'),
            array('PharException', '5.2', array(11, 53), '5.3'),
            array('PharFileInfo', '5.2', array(12, 54), '5.3'),
            array('FilesystemIterator', '5.2', array(13, 55), '5.3'),
            array('GlobIterator', '5.2', array(14, 56), '5.3'),
            array('MultipleIterator', '5.2', array(15, 57), '5.3'),
            array('RecursiveTreeIterator', '5.2', array(16, 58), '5.3'),
            array('SplDoublyLinkedList', '5.2', array(17, 59), '5.3'),
            array('SplFixedArray', '5.2', array(18, 60), '5.3'),
            array('SplHeap', '5.2', array(19, 61), '5.3'),
            array('SplMaxHeap', '5.2', array(20, 62), '5.3'),
            array('SplMinHeap', '5.2', array(21, 63), '5.3'),
            array('SplPriorityQueue', '5.2', array(22, 64), '5.3'),
            array('SplQueue', '5.2', array(23, 65), '5.3'),
            array('SplStack', '5.2', array(24, 66), '5.3'),
            array('CallbackFilterIterator', '5.3', array(25, 67), '5.4'),
            array('RecursiveCallbackFilterIterator', '5.3', array(26, 68), '5.4'),
            array('ReflectionZendExtension', '5.3', array(27, 69), '5.4'),
            array('JsonSerializable', '5.3', array(28), '5.4'),
            array('SessionHandler', '5.3', array(29, 71), '5.4'),
            array('SNMP', '5.3', array(30, 72), '5.4'),
            array('Transliterator', '5.3', array(31, 73), '5.4'),
            array('CURLFile', '5.4', array(32, 74), '5.5'),
            array('DateTimeImmutable', '5.4', array(33, 75), '5.5'),
            array('IntlCalendar', '5.4', array(34, 76), '5.5'),
            array('IntlGregorianCalendar', '5.4', array(35, 77), '5.5'),
            array('IntlTimeZone', '5.4', array(36, 78), '5.5'),
            array('IntlBreakIterator', '5.4', array(37, 79), '5.5'),
            array('IntlRuleBasedBreakIterator', '5.4', array(38, 80), '5.5'),
            array('IntlCodePointBreakIterator', '5.4', array(39, 81), '5.5'),
        );
    }

}
