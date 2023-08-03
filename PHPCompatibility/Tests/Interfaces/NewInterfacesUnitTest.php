<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Interfaces;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewInterfaces sniff.
 *
 * @group newInterfaces
 * @group interfaces
 *
 * @covers \PHPCompatibility\Sniffs\Interfaces\NewInterfacesSniff
 *
 * @since 7.0.3
 */
class NewInterfacesUnitTest extends BaseSniffTestCase
{

    /**
     * Test detection of use of new interfaces.
     *
     * @dataProvider dataNewInterface
     *
     * @param string $interfaceName     Interface name.
     * @param string $lastVersionBefore The PHP version just *before* the class was introduced.
     * @param array  $lines             The line numbers in the test file which apply to this class.
     * @param string $okVersion         A PHP version in which the class was ok to be used.
     * @param string $testVersion       Optional. A PHP version in which to test for the error if different
     *                                  from the $lastVersionBefore.
     *
     * @return void
     */
    public function testNewInterface($interfaceName, $lastVersionBefore, $lines, $okVersion, $testVersion = null)
    {
        $errorVersion = (isset($testVersion)) ? $testVersion : $lastVersionBefore;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The built-in interface {$interfaceName} is not present in PHP version {$lastVersionBefore} or earlier";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }

        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testNewInterface()
     *
     * @return array
     */
    public static function dataNewInterface()
    {
        return [
            ['Reflector', '4.4', [75, 116, 164, 178], '5.0'],
            ['Traversable', '4.4', [35, 50, 60, 71, 79, 164], '5.0'],
            ['Countable', '5.0', [3, 17, 41, 153], '5.1'],
            ['OuterIterator', '5.0', [4, 42, 65, 154], '5.1'],
            ['RecursiveIterator', '5.0', [5, 43, 65, 154], '5.1'],
            ['SeekableIterator', '5.0', [6, 17, 28, 44, 163], '5.1'],
            ['Serializable', '5.0', [7, 29, 45, 55, 70, 119, 125, 191], '5.1'],
            ['SplObserver', '5.0', [11, 46, 65, 153], '5.1'],
            ['SplSubject', '5.0', [12, 17, 47, 69, 163], '5.1'],
            ['JsonSerializable', '5.3', [13, 48, 134, 135, 155, 190], '5.4'],
            ['SessionHandlerInterface', '5.3', [14, 49, 147, 155], '5.4'],
            ['DateTimeInterface', '5.4', [36, 51, 61, 80], '5.5'],
            ['SessionIdInterface', '5.5.0', [89, 117, 146], '5.6', '5.5'],
            ['Throwable', '5.6', [37, 52, 62, 93, 98, 103, 162, 186], '7.0'],
            ['SessionUpdateTimestampHandlerInterface', '5.6', [90, 142, 162], '7.0'],
            ['Stringable', '7.4', [112, 179], '8.0'],
        ];
    }

    /**
     * Test unsupported methods
     *
     * @dataProvider dataUnsupportedMethods
     *
     * @param array  $line       The line number.
     * @param string $methodName The name of the unsupported method which should be detected.
     *
     * @return void
     */
    public function testUnsupportedMethods($line, $methodName)
    {
        $file = $this->sniffFile(__FILE__, '5.1'); // Version in which the Serializable interface was introduced.
        $this->assertError($file, $line, " interface Serializable do not support the method {$methodName}(). See https://www.php.net/serializable");
    }

    /**
     * Data provider.
     *
     * @see testUnsupportedMethods()
     *
     * @return array
     */
    public static function dataUnsupportedMethods()
    {
        return [
            [8, '__sleep'],
            [9, '__wakeup'],
            [30, '__sleep'],
            [31, '__wakeup'],
            [120, '__sleep'],
            [121, '__wakeup'],
            [192, '__sleep'],
            [193, '__wakeup'],
        ];
    }


    /**
     * Verify that the unsupported methods check doesn't throw false positives.
     *
     * @dataProvider dataNoFalsePositivesUnsupportedMethods
     *
     * @param array $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositivesUnsupportedMethods($line)
    {
        $file = $this->sniffFile(__FILE__, '5.1'); // Version in which the Serializable interface was introduced.
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesUnsupportedMethods()
     *
     * @return array
     */
    public static function dataNoFalsePositivesUnsupportedMethods()
    {
        return [
            [127],
            [128],
        ];
    }


    /**
     * Test interfaces in different cases.
     *
     * @return void
     */
    public function testCaseInsensitive()
    {
        $file = $this->sniffFile(__FILE__, '5.0');
        $this->assertError($file, 20, 'The built-in interface COUNTABLE is not present in PHP version 5.0 or earlier');
        $this->assertError($file, 21, 'The built-in interface countable is not present in PHP version 5.0 or earlier');
        $this->assertError($file, 78, 'The built-in interface COUNTABLE is not present in PHP version 5.0 or earlier');
        $this->assertError($file, 81, 'The built-in interface throwable is not present in PHP version 5.6 or earlier');
    }


    /**
     * Test the sniff doesn't throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '4.4'); // Low version below the first addition.
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public static function dataNoFalsePositives()
    {
        return [
            [24],
            [25],
            [56],
            [57],
            [72],
            [84],
            [85],
            [86],
            [108],
            [115],
            [138],
            [141],
            [151],
            [160],
            [168],
            [172],
            [175],
            [177],
            [185],
            [189],
            [199],
        ];
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will throw an error
     * on invalid use of some magic methods for the Serializable Interface.
     */
}
