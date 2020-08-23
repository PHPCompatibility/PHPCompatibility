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
 * Test the NewListReferenceAssignment sniff.
 *
 * @group newListReferenceAssignment
 * @group lists
 *
 * @covers \PHPCompatibility\Sniffs\Lists\NewListReferenceAssignmentSniff
 *
 * @since 9.0.0
 */
class NewListReferenceAssignmentUnitTest extends BaseSniffTest
{

    /**
     * testNewListReferenceAssignment
     *
     * @dataProvider dataNewListReferenceAssignment
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testNewListReferenceAssignment($line)
    {
        $file = $this->sniffFile(__FILE__, '7.2');
        $this->assertError($file, $line, 'Reference assignments within list constructs are not supported in PHP 7.2 or earlier.');
    }

    /**
     * dataNewListReferenceAssignment
     *
     * @see testNewListReferenceAssignment()
     *
     * @return array
     */
    public function dataNewListReferenceAssignment()
    {
        return [
            [16],
            [17],
            [20],
            [24],
            [30],
            [33], // x2.
            [36], // x2.
            [37],
            [41],
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
        $file = $this->sniffFile(__FILE__, '7.2');
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
            [7],
            [9],
            [10],
            [19],
            [21],
            [22],
            [23],
            [25],
            [29],
            [31],
            [32],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.3');
        $this->assertNoViolation($file);
    }
}
