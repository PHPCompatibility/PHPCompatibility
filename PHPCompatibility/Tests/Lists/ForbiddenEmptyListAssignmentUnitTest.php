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

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the ForbiddenEmptyListAssignment sniff.
 *
 * @group forbiddenEmptyListAssignment
 * @group lists
 *
 * @covers \PHPCompatibility\Sniffs\Lists\ForbiddenEmptyListAssignmentSniff
 *
 * @since 7.0.0
 */
class ForbiddenEmptyListAssignmentUnitTest extends BaseSniffTestCase
{

    /**
     * testEmptyListAssignment
     *
     * @dataProvider dataEmptyListAssignment
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testEmptyListAssignment($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertError($file, $line, 'Empty list() assignments are not allowed since PHP 7.0');
    }

    /**
     * dataEmptyListAssignment
     *
     * @see testEmptyListAssignment()
     *
     * @return array
     */
    public static function dataEmptyListAssignment()
    {
        return [
            [3],
            [4],
            [5],
            [6],
            [7],
            [19],
            [20],
            [21],
            [22],
            [23],
            [27],
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
    public static function dataNoFalsePositives()
    {
        return [
            [12],
            [13],
            [14],
            [15],
            [16],
            [30],
            [31],
            [34],
            [37],
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
