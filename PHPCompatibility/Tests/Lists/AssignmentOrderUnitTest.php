<?php
/**
 * PHP 7.0 assignment order change sniff test file.
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Sniffs\Lists;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * PHP 7.0 assignment order change sniff test file.
 *
 * @group assignmentOrder
 * @group lists
 *
 * @covers \PHPCompatibility\Sniffs\Lists\AssignmentOrderSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class AssignmentOrderUnitTest extends BaseSniffTest
{
    const TEST_FILE = 'Sniffs/Lists/AssignmentOrderUnitTest.inc';

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
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
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
        return array(
            array(17),
            array(18),
            array(19),
            array(20),
            array(22),
            array(24),
            array(27),
            array(28),
            array(29),
            array(30),
            array(32),
            array(34),
            array(37),
            array(38),
            array(45),
        );
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
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
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
        return array(
            array(6),
            array(8),
            array(10),
            array(12),
            array(41),
            array(42),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertNoViolation($file);
    }
}
