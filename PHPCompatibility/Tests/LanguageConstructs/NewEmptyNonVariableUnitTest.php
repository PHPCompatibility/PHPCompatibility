<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\LanguageConstructs;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewEmptyNonVariable sniff.
 *
 * @group newEmptyNonVariable
 * @group languageConstructs
 *
 * @covers \PHPCompatibility\Sniffs\LanguageConstructs\NewEmptyNonVariableSniff
 * @covers \PHPCompatibility\Helpers\TokenGroup::isVariable
 *
 * @since 7.0.4
 */
class NewEmptyNonVariableUnitTest extends BaseSniffTest
{

    /**
     * testEmptyNonVariable
     *
     * @dataProvider dataEmptyNonVariable
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testEmptyNonVariable($line)
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertError($file, $line, 'Only variables can be passed to empty() prior to PHP 5.5.');
    }

    /**
     * Data provider.
     *
     * @see testEmptyNonVariable()
     *
     * @return array
     */
    public function dataEmptyNonVariable()
    {
        return [
            [17],
            [18],

            [20],
            [21],
            [22],
            [23],

            [25],
            [26],
            [27],
            [28],
            [29],
            [30],
            [31],
            [32],

            [34],
            [35],
            [37],
            [38],
            [39],
            [40],

            [42],
            [43],
        ];
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
        $file = $this->sniffFile(__FILE__, '5.4');
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
            [4],
            [5],
            [6],
            [7],
            [8],
            [9],
            [10],
            [11],
            [12],
            [13],
            [14],

            // Issue #210.
            [47],
            [48],
            [49],
            [50],
            [51],
            [52],
            [53],
            [54],
            [55],
            [56],
            [57],
            [58],
            [59],
            [60],
            [61],

            // Live coding.
            [65],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.5');
        $this->assertNoViolation($file);
    }
}
