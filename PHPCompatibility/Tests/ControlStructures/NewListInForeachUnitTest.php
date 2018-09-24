<?php
/**
 * PHP 5.5 list() in foreach sniff test file.
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\ControlStructures;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * PHP 5.5 list() in foreach sniff test file.
 *
 * @group newListInForeach
 * @group controlStructures
 *
 * @covers \PHPCompatibility\Sniffs\ControlStructures\NewListInForeachSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewListInForeachUnitTest extends BaseSniffTest
{
    const TEST_FILE = 'Sniffs/ControlStructures/NewListInForeachUnitTest.inc';

    /**
     * testNewListInForeach
     *
     * @dataProvider dataNewListInForeach
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testNewListInForeach($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        $this->assertError($file, $line, 'Unpacking nested arrays with list() in a foreach is not supported in PHP 5.4 or earlier.');
    }

    /**
     * dataNewListInForeach
     *
     * @see testNewListInForeach()
     *
     * @return array
     */
    public function dataNewListInForeach()
    {
        return array(
            array(14),
            array(17),
            array(18),
            array(19),
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
        $file = $this->sniffFile(self::TEST_FILE, '5.4');
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
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.5');
        $this->assertNoViolation($file);
    }
}
