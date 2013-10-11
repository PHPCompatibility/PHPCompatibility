<?php
/**
 * Forbidden call time pass by reference sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Forbidden call time pass by reference sniff test
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class ForbiddenCallTimePassByReferenceSniffTest extends BaseSniffTest
{
    /**
     * Sniffed file
     *
     * @var PHP_CodeSniffer_File
     */
    protected $_sniffFile;

    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->_sniffFile = $this->sniffFile('sniff-examples/call_time_pass_by_reference.php');
    }

    /**
     * Test declare parameter by reference
     *
     * @return void
     */
    public function testDeclareParameterByReference()
    {
        $this->assertNoViolation($this->_sniffFile, 9);
    }

    public function testCallTimeNormal()
    {
        $this->assertNoViolation($this->_sniffFile, 14);
    }

    public function testCallTimePassByReferenceSingleParam()
    {
        $this->assertError($this->_sniffFile, 15, 'Using a call-time pass-by-reference is prohibited since php 5.4');
    }

    public function testCallTimePassByReferenceMultiParam()
    {
        $this->assertError($this->_sniffFile, 19, 'Using a call-time pass-by-reference is prohibited since php 5.4');
    }

    public function testCallTimePassByReferenceNested()
    {
        $this->assertError($this->_sniffFile, 22, 'Using a call-time pass-by-reference is prohibited since php 5.4');
    }

    public function testBitwiseOperationsAsParameter()
    {
        $this->assertNoViolation($this->_sniffFile, 24);
        $this->assertNoViolation($this->_sniffFile, 25);
        $this->assertNoViolation($this->_sniffFile, 26);
        $this->assertNoViolation($this->_sniffFile, 27);
        $this->assertNoViolation($this->_sniffFile, 28);
    }

    public function testCallTimePassByReferenceWithWhiteSpace()
    {
        $this->assertError($this->_sniffFile, 29, 'Using a call-time pass-by-reference is prohibited since php 5.4');
    }

}

