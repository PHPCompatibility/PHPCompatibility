<?php
/**
 * Short array syntax test file
 *
 * @package PHPCompatibility
 */


/**
 * Short array syntax sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 */
class ShortArraySniffTest extends BaseSniffTest
{
    /** @var string */
    protected $_sniffFileName;

    /** @var int */
    protected $_lineNumber = 1;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->_sniffFileName = 'sniff-examples/short_array.php';
    }

    /**
     *
     */
    public function testNoViolation()
    {
        $file = $this->sniffFile($this->_sniffFileName, '5.4');
        $this->assertNoViolation($file, $this->_lineNumber);
        $file = $this->sniffFile($this->_sniffFileName, '5.5');
        $this->assertNoViolation($file, $this->_lineNumber);
        $file = $this->sniffFile($this->_sniffFileName, '5.6');
        $this->assertNoViolation($file, $this->_lineNumber);
    }

    /**
     *
     */
    public function testViolation()
    {
        $file = $this->sniffFile($this->_sniffFileName, '5.3');
        $this->assertError($file, $this->_lineNumber, 'Short array syntax (open) is available since 5.4');
        $this->assertError($file, $this->_lineNumber, 'Short array syntax (close) is available since 5.4');

        $file = $this->sniffFile($this->_sniffFileName, '5.2');
        $this->assertError($file, $this->_lineNumber, 'Short array syntax (open) is available since 5.4');
        $this->assertError($file, $this->_lineNumber, 'Short array syntax (close) is available since 5.4');
    }
}