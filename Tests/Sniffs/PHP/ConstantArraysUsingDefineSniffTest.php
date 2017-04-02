<?php
/**
 * Constant arrays using define in PHP 7.0 sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Constant arrays using define in PHP 7.0 sniff test file
 *
 * @group constantArraysUsingDefine
 * @group constants
 *
 * @covers PHPCompatibility_Sniffs_PHP_ConstantArraysUsingDefineSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class ConstantArraysUsingDefineSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/constant_arrays_using_define.php';

    /**
     * testConstantArraysUsingDefine
     *
     * @dataProvider dataConstantArraysUsingDefine
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testConstantArraysUsingDefine($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertError($file, $line, 'Constant arrays using define are not allowed in PHP 5.6 or earlier');
    }

    /**
     * Data provider dataConstantArraysUsingDefine.
     *
     * @see testConstantArraysUsingDefine()
     *
     * @return array
     */
    public function dataConstantArraysUsingDefine()
    {
        return array(
            array(3),
            array(9),
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
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
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
            array(15),
            array(18),
            array(19),
            array(22),
            array(23),
            array(26),
            array(28),
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
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file);
    }

}
