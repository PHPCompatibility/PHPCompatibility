<?php
/**
 * Switch statements can only have one default case in PHP 7.0
 *
 * @package PHPCompatibility
 */


/**
 * Switch statements can only have one default case in PHP 7.0
 *
 * @group forbiddenSwitchWithMultipleDefaultBlocks
 *
 * @covers PHPCompatibility_Sniffs_PHP_ForbiddenSwitchWithMultipleDefaultBlocksSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class ForbiddenSwitchWithMultipleDefaultBlocksSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/forbidden_switch_with_multiple_default_blocks.php';

    /**
     * testForbiddenSwitchWithMultipleDefaultBlocks
     *
     * @dataProvider dataForbiddenSwitchWithMultipleDefaultBlocks
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testForbiddenSwitchWithMultipleDefaultBlocks($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertNoViolation($file, $line);

        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertError($file, $line, 'Switch statements can not have multiple default blocks since PHP 7.0');
    }

    /**
     * Data provider.
     *
     * @see testForbiddenSwitchWithMultipleDefaultBlocks()
     *
     * @return array
     */
    public function dataForbiddenSwitchWithMultipleDefaultBlocks()
    {
        return array(
            array(3),
            array(47),
            array(56),
        );
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
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
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
        return array(
            array(14),
            array(23),
            array(43),
            array(67), // Live coding.
        );
    }
}

