<?php
/**
 * New function array dereferencing sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New function array dereferencing sniff tests
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
     * @group functionArrayDereferencing
     *
     * @return void
     */
    public function testArrayDereferencing()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertError($file, 3, 'Function array dereferencing is not present in PHP version 5.3 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        $this->assertNoViolation($file, 3);
    }


    /**
     * testNoViolation
     *
     * @group functionArrayDereferencing
     *
     * @dataProvider dataNoViolation
     *
     * @param int $line Line number with valid code.
     *
     * @return void
     */
    public function testNoViolation($line)
    {
        $file = $this->sniffFile(self::TEST_FILE);
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataNoViolation
     *
     * @see testNoViolation()
     *
     * @return array
     */
    public function dataNoViolation() {
        return array(
            array(5),
            array(6),
        );
    }
}
