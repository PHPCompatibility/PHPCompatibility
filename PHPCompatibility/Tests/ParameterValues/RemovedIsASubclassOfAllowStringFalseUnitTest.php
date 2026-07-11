<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedIsASubclassOfAllowStringFalse sniff.
 *
 * @group removedIsASubclassOfAllowStringFalse
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedIsASubclassOfAllowStringFalseSniff
 *
 * @since 10.0.0
 */
final class RemovedIsASubclassOfAllowStringFalseUnitTest extends BaseSniffTestCase
{

    /**
     * Verify a warning is thrown when an explicit false is passed as the $allow_string parameter.
     *
     * @dataProvider dataRemovedIsASubclassOfAllowStringFalse
     *
     * @param int $line Line number where the message should occur.
     *
     * @return void
     */
    public function testRemovedIsASubclassOfAllowStringFalse($line)
    {
        $file = $this->sniffFile(__FILE__, '8.6');
        $this->assertWarning($file, $line, '() with a string, while $allow_string is false, is deprecated since PHP 8.6');
    }

    /**
     * Data provider.
     *
     * @return array<array<int>>
     */
    public static function dataRemovedIsASubclassOfAllowStringFalse()
    {
        return [
            [93],
            [94],
            [95],
            [101],
            [102],
            [113],
            [114],
            [116],
            [117],
            [118],
            [119],
            [120],
            [123],
            [124],
        ];
    }


    /**
     * Verify the sniff does not throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.6');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array<array<int>>
     */
    public static function dataNoFalsePositives()
    {
        $data = [];

        // No errors expected on the first 89 lines.
        for ($line = 1; $line <= 89; $line++) {
            $data[] = [$line];
        }

        return $data;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.5');
        $this->assertNoViolation($file);
    }
}
