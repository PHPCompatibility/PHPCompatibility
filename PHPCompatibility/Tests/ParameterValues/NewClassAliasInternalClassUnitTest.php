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
 * Test the NewClassAliasInternalClass sniff.
 *
 * @group mewClassAliasInternalClass
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\NewClassAliasInternalClassSniff
 *
 * @since 10.0.0
 */
final class NewClassAliasInternalClassUnitTest extends BaseSniffTestCase
{

    /**
     * Verify that aliasing a PHP internal class is flagged.
     *
     * @dataProvider dataNewClassAliasInternalClass
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testNewClassAliasInternalClass($line)
    {
        $file = $this->sniffFile(__FILE__, '8.2');
        $this->assertError($file, $line, 'PHP internal classes can not be aliased prior to PHP 8.3. Found aliasing of class:');
    }

    /**
     * Data provider.
     *
     * @see testNewClassAliasInternalClass()
     *
     * @return array<array<int>>
     */
    public static function dataNewClassAliasInternalClass()
    {
        $data = [
            [56],
            [57],
            [58],
            [63],
            [70],
            [74],
            [75],
            [76],
            [82],
            [88],
        ];

        if (\extension_loaded('mysqli')) {
            $data[] = [85];
        }

        if (\PHP_VERSION_ID >= 80000) {
            $data[] = [91];
        }

        return $data;
    }


    /**
     * Verify there are no false positives on valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.2');
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
        $cases = [];

        // No errors expected on the first 50 lines.
        for ($line = 1; $line <= 50; $line++) {
            $cases[] = [$line];
        }

        return $cases;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file);
    }
}
