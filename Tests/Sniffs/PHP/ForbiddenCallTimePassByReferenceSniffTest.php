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

    /**
     * testCallTimeNormal
     *
     * @return void
     */
    public function testCallTimeNormal()
    {
        $this->assertNoViolation($this->_sniffFile, 14);
    }

    /**
     * testCallTimePassByReferenceSingleParam
     *
     * @return void
     */
    public function testCallTimePassByReferenceSingleParam()
    {
        $this->assertError($this->_sniffFile, 15, 'Using a call-time pass-by-reference is prohibited since php 5.4');
    }

    /**
     * testCallTimePassByReferenceMultiParam
     *
     * @return void
     */
    public function testCallTimePassByReferenceMultiParam()
    {
        $this->assertError($this->_sniffFile, 19, 'Using a call-time pass-by-reference is prohibited since php 5.4');
    }

    /**
     * testCallTimePassByReferenceNested
     *
     * @return void
     */
    public function testCallTimePassByReferenceNested()
    {
        $this->assertError($this->_sniffFile, 22, 'Using a call-time pass-by-reference is prohibited since php 5.4');
    }

    /**
     * testCallTimePassByReferenceGlobal
     *
     * @return void
     */
    public function testCallTimePassByReferenceGlobal()
    {
        $this->assertError($this->_sniffFile, 44, 'Using a call-time pass-by-reference is prohibited since php 5.4');
    }

    /**
     * testBitwiseOperationsAsParameter
     *
     * @return void
     */
    public function testBitwiseOperationsAsParameter()
    {
        $this->assertNoViolation($this->_sniffFile, 24);
        $this->assertNoViolation($this->_sniffFile, 25);
        $this->assertNoViolation($this->_sniffFile, 26);
        $this->assertNoViolation($this->_sniffFile, 27);
        $this->assertNoViolation($this->_sniffFile, 28);
        $this->assertNoViolation($this->_sniffFile, 40);
        $this->assertNoViolation($this->_sniffFile, 41);
    }

    /**
     * testCallTimePassByReferenceWithWhiteSpace
     *
     * @return void
     */
    public function testCallTimePassByReferenceWithWhiteSpace()
    {
        $this->assertError($this->_sniffFile, 29, 'Using a call-time pass-by-reference is prohibited since php 5.4');
    }

    /**
     * testSettingTestVersion
     *
     * @return void
     */
    public function testSettingTestVersion()
    {
        $file = $this->sniffFile('sniff-examples/call_time_pass_by_reference.php', '5.2');

        $this->assertNoViolation($file, 29);
    }
}

