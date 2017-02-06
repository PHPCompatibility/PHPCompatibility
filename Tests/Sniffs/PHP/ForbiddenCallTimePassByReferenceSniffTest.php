<?php
/**
 * Forbidden call time pass by reference sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Forbidden call time pass by reference sniff test
 *
 * @group forbiddenCallTimePassByReference
 * @group references
 *
 * @covers PHPCompatibility_Sniffs_PHP_ForbiddenCallTimePassByReferenceSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class ForbiddenCallTimePassByReferenceSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/call_time_pass_by_reference.php';

    /**
     * Sniffed file
     *
     * @var PHP_CodeSniffer_File
     */
    protected $_sniffFile;

    /**
     * Set up the test file for this unit test.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_sniffFile = $this->sniffFile(self::TEST_FILE);
    }


    /**
     * testForbiddenCallTimePassByReference
     *
     * @dataProvider dataForbiddenCallTimePassByReference
     *
     * @param int    $line  Line number where the error should occur.
     *
     * @return void
     */
    public function testForbiddenCallTimePassByReference($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertNoViolation($file, $line);

        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertWarning($file, $line, 'Using a call-time pass-by-reference is deprecated since PHP 5.3');

        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        $this->assertError($file, $line, 'Using a call-time pass-by-reference is deprecated since PHP 5.3 and prohibited since PHP 5.4');
    }

    /**
     * dataForbiddenCallTimePassByReference
     *
     * @see testForbiddenCallTimePassByReference()
     *
     * @return array
     */
    public function dataForbiddenCallTimePassByReference() {
        return array(
            array(10), // Bad: call time pass by reference.
            array(14), // Bad: call time pass by reference with multi-parameter call.
            array(17), // Bad: nested call time pass by reference.
            array(25), // Bad: call time pass by reference with space.
            array(44), // Bad: call time pass by reference.
            array(45), // Bad: call time pass by reference.
            array(49), // Bad: multiple call time pass by reference.
            array(71), // Bad: call time pass by reference.
        );
    }


    /**
     * testNoViolation
     *
     * @dataProvider dataNoViolation
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoViolation($line)
    {
        $this->assertNoViolation($this->_sniffFile, $line);
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
            array(4), // OK: Declaring a parameter by reference.
            array(9), // OK: Call time passing without reference.

            // OK: Bitwise operations as parameter.
            array(20),
            array(21),
            array(22),
            array(23),
            array(24),
            array(39),
            array(40),
            //array(41), // Currently not yet covered.

            array(51), // OK: No variables.
            array(53), // OK: Outside scope of this sniff.

            // Assign by reference within function call.
            array(56),
            array(57),
            array(58),
            array(59),
            array(60),
            array(61),
            array(62),
            array(63),
            array(64),
            array(65),
            array(66),
            array(67),
            array(68),
            array(69),

            // Comparison with reference.
            array(74),
            array(75),
        );
    }

}

