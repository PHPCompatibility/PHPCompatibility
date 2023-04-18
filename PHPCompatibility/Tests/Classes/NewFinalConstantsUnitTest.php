<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2022 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Classes;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewFinalConstants sniff.
 *
 * @group newFinalConstants
 * @group classes
 *
 * @covers \PHPCompatibility\Sniffs\Classes\NewFinalConstantsSniff
 *
 * @since 10.0.0
 */
class NewFinalConstantsUnitTest extends BaseSniffTest
{

    /**
     * Test that an error is thrown for OO constants declared as final.
     *
     * @dataProvider dataFinalConstants
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testFinalConstants($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, $line, 'The final modifier for OO constants is not supported in PHP 8.0 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testFinalConstants()
     *
     * @return array
     */
    public function dataFinalConstants()
    {
        return [
            [10],
            [11],
            [12],
            [13],
            [14],
            [17],
            [18],

            [26],
            [27],
            [28],
            [31],
            [32],
            [35],
            [36],

            [45],
            [46],
            [47],
            [48],
            [49],
            [52],
            [53],
        ];
    }


    /**
     * Verify that there are no false positives for valid code/code errors outside the scope of this sniff.
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
    public function dataNoFalsePositives()
    {
        return [
            [3],
            [7],
            [23],
            [42],
            [62],
            [66],
        ];
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
