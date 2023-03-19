<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2021 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewArrayMergeRecursiveWithGlobalsVar sniff.
 *
 * @group newArrayMergeRecursiveWithGlobalsVar
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\NewArrayMergeRecursiveWithGlobalsVarSniff
 *
 * @since 10.0.0
 */
final class NewArrayMergeRecursiveWithGlobalsVarUnitTest extends BaseSniffTest
{

    /**
     * Test detecting use of `array_merge_recursive()` with $GLOBALS passed multiple times.
     *
     * @dataProvider dataRecursiveMerge
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testRecursiveMerge($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, $line, 'Recursively merging the $GLOBALS array is not supported prior to PHP 8.1.');
    }

    /**
     * Data provider.
     *
     * @see testRecursiveMerge()
     *
     * @return array
     */
    public static function dataRecursiveMerge()
    {
        return [
            [28],
            [29],
        ];
    }


    /**
     * Test the sniff does not throw false positives.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
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
        $cases = [];

        // No errors expected on the first 22 lines.
        for ($line = 1; $line <= 22; $line++) {
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
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertNoViolation($file);
    }
}
