<?php
/**
 * PHP 7.3 list reference assignments sniff test file.
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Lists;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * PHP 7.3 list reference assignments sniff test file.
 *
 * @group newListReferenceAssignment
 * @group lists
 *
 * @covers \PHPCompatibility\Sniffs\Lists\NewListReferenceAssignmentSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewListReferenceAssignmentUnitTest extends BaseSniffTest
{
    const TEST_FILE = 'Sniffs/Lists/NewListReferenceAssignmentUnitTest.inc';

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
        $file = $this->sniffFile(self::TEST_FILE, '7.2');
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
        return array(
            array(16),
            array(17),
            array(20),
            array(24),
            array(30),
            array(33), // x2.
            array(36), // x2.
            array(37),
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
        $file = $this->sniffFile(self::TEST_FILE, '7.2');
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
            array(7),
            array(9),
            array(10),
            array(19),
            array(21),
            array(22),
            array(23),
            array(25),
            array(29),
            array(31),
            array(32),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.3');
        $this->assertNoViolation($file);
    }

}
