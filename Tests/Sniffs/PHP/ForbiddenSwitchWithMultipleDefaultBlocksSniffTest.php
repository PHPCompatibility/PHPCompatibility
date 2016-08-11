<?php
/**
 * Switch statements can only have one default case in PHP 7.0
 *
 * @package PHPCompatibility
 */


/**
 * Switch statements can only have one default case in PHP 7.0
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
     * @return void
     */
    public function testForbiddenSwitchWithMultipleDefaultBlocks()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertNoViolation($file, 3);

        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertError($file, 3, 'Switch statements can not have multiple default blocks since PHP 7.0');
    }


    /**
     * testValidSwitchStatement
     *
     * @dataProvider dataValidSwitchStatement
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testValidSwitchStatement($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testValidSwitchStatement()
     *
     * @return array
     */
    public function dataValidSwitchStatement()
    {
        return array(
            array(14),
            array(23),
            array(43),
        );
    }
}

