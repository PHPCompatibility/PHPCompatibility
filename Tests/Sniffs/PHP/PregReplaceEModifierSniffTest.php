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
    }

    /**
     * testDeprecatedPregReplace
     *
     * @return void
     */
    public function testDeprecatedPregReplace()
    {
        $error = "preg_replace() - /e modifier is deprecated in PHP 5.5";

        $this->assertError($this->_sniffFile, 26, $error);
        $this->assertError($this->_sniffFile, 27, $error);
        $this->assertError($this->_sniffFile, 30, $error);
        $this->assertError($this->_sniffFile, 31, $error);
        $this->assertError($this->_sniffFile, 34, $error);
        $this->assertError($this->_sniffFile, 35, $error);
        $this->assertError($this->_sniffFile, 36, $error);
    }

    /**
     * testUntestablePregReplace
     *
     * @return void
     */
    public function testUntestablePregReplace()
    {
        $this->assertNoViolation($this->_sniffFile, 46);
        $this->assertNoViolation($this->_sniffFile, 47);
        $this->assertNoViolation($this->_sniffFile, 48);
    }

}
