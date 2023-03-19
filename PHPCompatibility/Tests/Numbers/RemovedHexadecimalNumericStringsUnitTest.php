<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Numbers;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the RemovedHexadecimalNumericStrings sniff.
 *
 * @group removedHexadecimalNumericStrings
 * @group numbers
 *
 * @covers \PHPCompatibility\Sniffs\Numbers\RemovedHexadecimalNumericStringsSniff
 *
 * @since 7.0.3
 * @since 10.0.0 Split off from the ValidIntegers sniff.
 */
class RemovedHexadecimalNumericStringsUnitTest extends BaseSniffTest
{

    /**
     * Test correctly recognizing hexadecimal numeric strings.
     *
     * @dataProvider dataHexNumericString
     *
     * @param int    $line Line number where the error should occur.
     * @param string $hex  Hexadecminal number as a string.
     *
     * @return void
     */
    public function testHexNumericString($line, $hex)
    {
        $error = "The behaviour of hexadecimal numeric strings was inconsistent prior to PHP 7 and support has been removed in PHP 7. Found: '{$hex}'";

        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertWarning($file, $line, $error);

        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testHexNumericString()
     *
     * @return array
     */
    public static function dataHexNumericString()
    {
        // phpcs:disable PHPCompatibility.Numbers.RemovedHexadecimalNumericStrings
        return [
            [5, '0xaa78b5'],
            [6, '0Xbb99EF'],

            [14, '0x7ff000000'],
            [15, '0x7'],
            [16, '0x7fef'],
            [17, '0x7ff00'],
            [18, '0x7ff00'],
            [19, '0x7ff00'],
            [20, '0x7ff00'],
            [21, '0x7ff00'],
            [23, '0x7ff00'],
            [27, '0x7ff00'],
            [28, '0x7ff00'],
        ];
        // phpcs:enable
    }


    /**
     * Verify the sniff doesn't throw false positives.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertNoViolation($file, $line);

        $file = $this->sniffFile(__FILE__, '7.0');
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
            [3],
            [9],
            [10],
            [11],
        ];
    }

    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will throw warnings/errors
     * about hexadecimal numeric strings independently of the testVersion.
     */
}
