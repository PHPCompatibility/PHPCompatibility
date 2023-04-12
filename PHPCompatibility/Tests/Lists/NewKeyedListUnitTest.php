<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Lists;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewKeyedList sniff.
 *
 * @group newKeyedList
 * @group lists
 *
 * @covers \PHPCompatibility\Sniffs\Lists\NewKeyedListSniff
 *
 * @since 9.0.0
 */
class NewKeyedListUnitTest extends BaseSniffTest
{

    /**
     * testNewKeyedList
     *
     * @dataProvider dataNewKeyedList
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testNewKeyedList($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertError($file, $line, 'Specifying keys in list constructs is not supported in PHP 7.0 or earlier.');
    }

    /**
     * dataNewKeyedList
     *
     * @see testNewKeyedList()
     *
     * @return array
     */
    public function dataNewKeyedList()
    {
        return [
            [15], // x3.
            [16], // x2.
            [17], // x2.
            [18],
            [19], // x2.
            [20], // x2.
            [22], // x3.
            [23], // x2.
            [28],
            [29],
            [30],
            [31],
            [36], // x2.
            [37], // x2.
            [41], // x2.
            [42], // x2.
            [46],
            [48],
            [54],
            [62],
            [66],
            [72],
        ];
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number with a valid list assignment.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataNoFalsePositives
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return [
            [6],
            [8],
            [10],
            [27],
            [35],
            [40],
            [45],
            [47],
            [49],
            [69],
            [73],
            [77],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.1');
        $this->assertNoViolation($file);
    }
}
