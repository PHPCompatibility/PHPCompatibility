<?php
/**
 * Constant arrays using define in PHP 7.0 sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Constant arrays using define in PHP 7.0 sniff test file
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class ConstantArraysUsingDefineSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/constant_arrays_using_define.php';

    /**
     * Verify that checking for a specific version works
     *
     * @group constantArraysUsingDefine
     *
     * @dataProvider dataConstantArraysUsingDefine
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testConstantArraysUsingDefine($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file, $line);

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
     * testNoViolation
     *
     * @group constantArraysUsingDefine
     *
     * @dataProvider dataNoViolation
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoViolation($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoViolation()
     *
     * @return array
     */
    public function dataNoViolation()
    {
        return array(
            array(15),
        );
    }

}
