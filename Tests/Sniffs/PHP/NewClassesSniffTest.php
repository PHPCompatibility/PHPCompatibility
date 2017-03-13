<?php
/**
 * New Classes Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New Classes Sniff tests
 *
 * @group newClasses
 * @group classes
 *
 * @covers PHPCompatibility_Sniffs_PHP_NewClassesSniff
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
        $file  = $this->sniffFile(self::TEST_FILE, $lastVersionBefore);
        $error = "The built-in class {$className} is not present in PHP version {$lastVersionBefore} or earlier";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
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
            array('DateTime', '5.1', array(25, 65, 105), '5.2'),
            array('DateTimeZone', '5.1', array(26, 66, 106), '5.2'),
            array('RegexIterator', '5.1', array(27, 67, 107), '5.2'),
            array('RecursiveRegexIterator', '5.1', array(28, 68, 108), '5.2'),
            array('DateInterval', '5.2', array(17, 18, 19, 20, 29, 69, 109), '5.3'),
            array('DatePeriod', '5.2', array(30, 70, 110), '5.3'),
            array('Phar', '5.2', array(31, 71, 111), '5.3'),
            array('PharData', '5.2', array(32, 72, 112), '5.3'),
            array('PharException', '5.2', array(33, 73, 113), '5.3'),
            array('PharFileInfo', '5.2', array(34, 74, 114), '5.3'),
            array('FilesystemIterator', '5.2', array(35, 75, 115), '5.3'),
            array('GlobIterator', '5.2', array(36, 76, 116), '5.3'),
            array('MultipleIterator', '5.2', array(37, 77, 117), '5.3'),
            array('RecursiveTreeIterator', '5.2', array(38, 78, 118), '5.3'),
            array('SplDoublyLinkedList', '5.2', array(39, 79, 119), '5.3'),
            array('SplFixedArray', '5.2', array(40, 80, 120), '5.3'),
            array('SplHeap', '5.2', array(41, 81, 121), '5.3'),
            array('SplMaxHeap', '5.2', array(42, 82, 122), '5.3'),
            array('SplMinHeap', '5.2', array(43, 83, 123), '5.3'),
            array('SplPriorityQueue', '5.2', array(44, 84, 124), '5.3'),
            array('SplQueue', '5.2', array(45, 85, 125), '5.3'),
            array('SplStack', '5.2', array(46, 86, 126), '5.3'),
            array('CallbackFilterIterator', '5.3', array(47, 87, 127), '5.4'),
            array('RecursiveCallbackFilterIterator', '5.3', array(48, 88, 128), '5.4'),
            array('ReflectionZendExtension', '5.3', array(49, 89, 129), '5.4'),
            array('SessionHandler', '5.3', array(50, 90, 130), '5.4'),
            array('SNMP', '5.3', array(51, 91, 131), '5.4'),
            array('Transliterator', '5.3', array(52, 92, 132), '5.4'),
            array('CURLFile', '5.4', array(53, 93, 133), '5.5'),
            array('DateTimeImmutable', '5.4', array(54, 94, 134), '5.5'),
            array('IntlCalendar', '5.4', array(55, 95, 135), '5.5'),
            array('IntlGregorianCalendar', '5.4', array(56, 96, 136), '5.5'),
            array('IntlTimeZone', '5.4', array(57, 97, 137), '5.5'),
            array('IntlBreakIterator', '5.4', array(58, 98, 138), '5.5'),
            array('IntlRuleBasedBreakIterator', '5.4', array(59, 99, 139), '5.5'),
            array('IntlCodePointBreakIterator', '5.4', array(60, 100, 140), '5.5'),
            
            array('DATETIME', '5.1', array(146), '5.2'),
            array('datetime', '5.1', array(147), '5.2'),
            array('dATeTiMe', '5.1', array(148), '5.2'),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.1'); // TestVersion based on the specific classes being tested.
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return array(
            array(6),
            array(7),
            array(8),
            array(9),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '99.0'); // High version beyond newest addition.
        $this->assertNoViolation($file);
    }

}
