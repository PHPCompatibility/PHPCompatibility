<?php
/**
 * preg_replace() /e modifier sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * preg_replace() /e modifier sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class PregReplaceEModifierSniffTest extends BaseSniffTest
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

        $this->_sniffFile = $this->sniffFile('sniff-examples/preg_replace_e_modifier.php');
    }

    /**
     * testNonDeprecatedPregReplace
     *
     * @return void
     */
    public function testNonDeprecatedPregReplace()
    {
        $this->assertNoViolation($this->_sniffFile, 9);
        $this->assertNoViolation($this->_sniffFile, 10);
        $this->assertNoViolation($this->_sniffFile, 13);
        $this->assertNoViolation($this->_sniffFile, 14);
        $this->assertNoViolation($this->_sniffFile, 17);
        $this->assertNoViolation($this->_sniffFile, 18);
        $this->assertNoViolation($this->_sniffFile, 21);
        $this->assertNoViolation($this->_sniffFile, 24);
        $this->assertNoViolation($this->_sniffFile, 39);
        $this->assertNoViolation($this->_sniffFile, 45);
    }

    /**
     * testDeprecatedPregReplace
     *
     * @return void
     */
    public function testDeprecatedPregReplace()
    {
        $error = "preg_replace() - /e modifier is deprecated in PHP 5.5";

        $this->assertError($this->_sniffFile, 50, $error);
        $this->assertError($this->_sniffFile, 51, $error);
        $this->assertError($this->_sniffFile, 54, $error);
        $this->assertError($this->_sniffFile, 55, $error);
        $this->assertError($this->_sniffFile, 58, $error);
        $this->assertError($this->_sniffFile, 59, $error);
        $this->assertError($this->_sniffFile, 60, $error);
        $this->assertError($this->_sniffFile, 63, $error);
        $this->assertError($this->_sniffFile, 78, $error);
        $this->assertError($this->_sniffFile, 84, $error);
    }

    /**
     * testUntestablePregReplace
     *
     * @return void
     */
    public function testUntestablePregReplace()
    {
        $this->assertNoViolation($this->_sniffFile, 94);
        $this->assertNoViolation($this->_sniffFile, 95);
        $this->assertNoViolation($this->_sniffFile, 96);
    }

}
