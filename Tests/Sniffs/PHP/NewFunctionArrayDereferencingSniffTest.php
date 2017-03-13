<?php
/**
 * New function array dereferencing sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New function array dereferencing sniff tests
 *
 * @group newFunctionArrayDereferencing
 *
 * @covers PHPCompatibility_Sniffs_PHP_NewFunctionArrayDereferencingSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewFunctionArrayDereferencingSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_function_array_dereferencing.php';

    /**
     * testArrayDereferencing
     *
     * @dataProvider dataArrayDereferencing
     *
     * @param int $line Line number with valid code.
     *
     * @return void
     */
    public function testArrayDereferencing($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertError($file, $line, 'Function array dereferencing is not present in PHP version 5.3 or earlier');
    }

    /**
     * dataArrayDereferencing
     *
     * @see testArrayDereferencing()
     *
     * @return array
     */
    public function dataArrayDereferencing()
    {
        return array(
            array(3),
            array(14),
            array(15),
            array(16),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number with valid code.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
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
            array(5),
            array(8),
            array(9),
            array(10),
            array(11),
            array(19),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        $this->assertNoViolation($file);
    }

}
