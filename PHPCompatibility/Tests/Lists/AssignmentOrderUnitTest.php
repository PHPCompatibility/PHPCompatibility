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
 * Test the AssignmentOrder sniff.
 *
 * @group assignmentOrder
 * @group lists
 *
 * @covers \PHPCompatibility\Sniffs\Lists\AssignmentOrderSniff
 *
 * @since 9.0.0
 */
class AssignmentOrderUnitTest extends BaseSniffTest
{

    /**
     * testAssignmentOrder
     *
     * @dataProvider dataAssignmentOrder
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testAssignmentOrder($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertError($file, $line, 'list() will assign variable from left-to-right since PHP 7.0. Ensure all variables in list() are unique to prevent unexpected results.');
    }

    /**
     * dataAssignmentOrder
     *
     * @see testAssignmentOrder()
     *
     * @return array
     */
    public function dataAssignmentOrder()
    {
        return [
            [17],
            [18],
            [19],
            [20],
            [22],
            [24],
            [27],
            [28],
            [29],
            [30],
            [32],
            [34],
            [37],
            [38],
            [45],
            [49],
            [52],
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
            [12],
            [41],
            [42],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertNoViolation($file);
    }
}
