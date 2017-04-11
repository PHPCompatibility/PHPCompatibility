<?php
/**
 * Constant arrays using the const keyword in PHP 5.6 sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Constant arrays using the const keyword in PHP 5.6 sniff test file
 *
 * @group constantArraysUsingConst
 * @group constants
 *
 * @covers PHPCompatibility_Sniffs_PHP_ConstantArraysUsingConstSniff
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class ConstantArraysUsingConstSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/constant_arrays_using_const.php';

    /**
     * testConstantArraysUsingConst
     *
     * @dataProvider dataConstantArraysUsingConst
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testConstantArraysUsingConst($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.5');
        $this->assertError($file, $line, 'Constant arrays using the "const" keyword are not allowed in PHP 5.5 or earlier');
    }

    /**
     * Data provider dataConstantArraysUsingConst.
     *
     * @see testConstantArraysUsingConst()
     *
     * @return array
     */
    public function dataConstantArraysUsingConst()
    {
        return array(
            array(3),
            array(4),
            array(6),
            array(12),
            array(19),
            array(25),
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
        $file = $this->sniffFile(self::TEST_FILE, '5.5');
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
            array(31),
            array(33),
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
