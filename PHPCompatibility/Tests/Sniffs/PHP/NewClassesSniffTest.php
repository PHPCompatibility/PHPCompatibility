<?php
/**
 * New Classes Sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Sniffs\PHP;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New Classes Sniff tests
 *
 * @group newClasses
 * @group classes
 *
 * @covers \PHPCompatibility\Sniffs\PHP\NewClassesSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Jansen Price <jansen.price@gmail.com>
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
     * @param string $testVersion       Optional. A PHP version in which to test for the error if different
     *                                  from the $lastVersionBefore.
     *
     * @return void
     */
    public function testNewClass($className, $lastVersionBefore, $lines, $okVersion, $testVersion = null)
    {
        if (version_compare(phpversion(), '5.3', '<') === true && strpos($className, '\\') !== false) {
            $this->markTestSkipped('PHP 5.2 does not recognize namespaces.');
            return;
        }

        $errorVersion = (isset($testVersion)) ? $testVersion : $lastVersionBefore;
        $file         = $this->sniffFile(self::TEST_FILE, $errorVersion);
        $error        = "The built-in class {$className} is not present in PHP version {$lastVersionBefore} or earlier";
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
            array('DateTime', '5.1', array(25, 65, 105, 151), '5.2'),
            array('DateTimeZone', '5.1', array(26, 66, 106, 162), '5.2'),
            array('RegexIterator', '5.1', array(27, 67, 107, 163), '5.2'),
            array('RecursiveRegexIterator', '5.1', array(28, 68, 108), '5.2'),
            array('DateInterval', '5.2', array(17, 18, 19, 20, 29, 69, 109), '5.3'),
            array('DatePeriod', '5.2', array(30, 70, 110, 173), '5.3'),
            array('Phar', '5.2', array(31, 71, 111, 152), '5.3'),
            array('PharData', '5.2', array(32, 72, 112), '5.3'),
            array('PharException', '5.2', array(33, 73, 113), '5.3'),
            array('PharFileInfo', '5.2', array(34, 74, 114), '5.3'),
            array('FilesystemIterator', '5.2', array(35, 75, 115, 174), '5.3'),
            array('GlobIterator', '5.2', array(36, 76, 116, 168), '5.3'),
            array('MultipleIterator', '5.2', array(37, 77, 117, 178), '5.3'),
            array('RecursiveTreeIterator', '5.2', array(38, 78, 118), '5.3'),
            array('SplDoublyLinkedList', '5.2', array(39, 79, 119), '5.3'),
            array('SplFixedArray', '5.2', array(40, 80, 120), '5.3'),
            array('SplHeap', '5.2', array(41, 81, 121, 164), '5.3'),
            array('SplMaxHeap', '5.2', array(42, 82, 122), '5.3'),
            array('SplMinHeap', '5.2', array(43, 83, 123, 153), '5.3'),
            array('SplPriorityQueue', '5.2', array(44, 84, 124), '5.3'),
            array('SplQueue', '5.2', array(45, 85, 125), '5.3'),
            array('SplStack', '5.2', array(46, 86, 126), '5.3'),
            array('CallbackFilterIterator', '5.3', array(47, 87, 127), '5.4'),
            array('RecursiveCallbackFilterIterator', '5.3', array(48, 88, 128, 179), '5.4'),
            array('ReflectionZendExtension', '5.3', array(49, 89, 129), '5.4'),
            array('SessionHandler', '5.3', array(50, 90, 130), '5.4'),
            array('SNMP', '5.3', array(51, 91, 131, 180), '5.4'),
            array('Transliterator', '5.3', array(52, 92, 132, 154), '5.4'),
            array('CURLFile', '5.4', array(53, 93, 133), '5.5'),
            array('DateTimeImmutable', '5.4', array(54, 94, 134), '5.5'),
            array('IntlCalendar', '5.4', array(55, 95, 135, 165), '5.5'),
            array('IntlGregorianCalendar', '5.4', array(56, 96, 136), '5.5'),
            array('IntlTimeZone', '5.4', array(57, 97, 137), '5.5'),
            array('IntlBreakIterator', '5.4', array(58, 98, 138), '5.5'),
            array('IntlRuleBasedBreakIterator', '5.4', array(59, 99, 139), '5.5'),
            array('IntlCodePointBreakIterator', '5.4', array(60, 100, 140), '5.5'),
            array('libXMLError', '5.0', array(61, 101, 141), '5.1'),

            array('DATETIME', '5.1', array(146), '5.2'),
            array('datetime', '5.1', array(147), '5.2'),
            array('dATeTiMe', '5.1', array(148), '5.2'),

            array('Exception', '4.4', array(190, 217), '5.0'),
            array('ErrorException', '5.0', array(194, 218), '5.1'),
            array('BadFunctionCallException', '5.0', array(201, 219), '5.1'),
            array('BadMethodCallException', '5.0', array(207, 220), '5.1'),
            array('DomainException', '5.0', array(186, 221), '5.1'),
            array('InvalidArgumentException', '5.0', array(222, 255), '5.1'),
            array('LengthException', '5.0', array(195, 223), '5.1'),
            array('LogicException', '5.0', array(224, 255), '5.1'),
            array('OutOfBoundsException', '5.0', array(225, 255), '5.1'),
            array('OutOfRangeException', '5.0', array(226, 255), '5.1'),
            array('OverflowException', '5.0', array(196, 227), '5.1'),
            array('RangeException', '5.0', array(208, 228), '5.1'),
            array('RuntimeException', '5.0', array(229, 255), '5.1'),
            array('UnderflowException', '5.0', array(197, 230), '5.1'),
            array('UnexpectedValueException', '5.0', array(191, 231), '5.1'),
            array('DOMException', '4.4', array(232, 260), '5.0'),
            array('mysqli_sql_exception', '4.4', array(202, 233), '5.0'),
            array('PDOException', '5.0', array(198, 234), '5.1'),
            array('ReflectionException', '4.4', array(187, 235), '5.0'),
            array('SoapFault', '4.4', array(236), '5.0'),
            array('PharException', '5.2', array(237), '5.3'),
            array('SNMPException', '5.3', array(238), '5.4'),
            array('IntlException', '5.5.0', array(239), '5.6', '5.5'),
            array('Error', '5.6', array(214, 240), '7.0'),
            array('ArithmeticError', '5.6', array(209, 241), '7.0'),
            array('AssertionError', '5.6', array(242), '7.0'),
            array('DivisionByZeroError', '5.6', array(203, 243), '7.0'),
            array('ParseError', '5.6', array(244), '7.0'),
            array('TypeError', '5.6', array(245), '7.0'),
            array('UI\Exception\InvalidArgumentException', '5.6', array(192, 210, 246), '7.0'),
            array('UI\Exception\RuntimeException', '5.6', array(188, 199, 247), '7.0'),
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
            array(157),
            array(158),
            array(169),
            array(170),
            array(181),
            array(265),
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
