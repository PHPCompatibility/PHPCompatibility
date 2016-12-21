<?php
/**
 * Ternary Operators Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Ternary Operators Sniff tests
 *
 * @group ternaryOperators
 *
 * @covers PHPCompatibility_Sniffs_PHP_TernaryOperatorsSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class TernaryOperatorsSniffTest extends BaseSniffTest
{
    /**
     * Sniffed file
     *
     * @var PHP_CodeSniffer_File
     */
    protected $_sniffFile;

    /**
     * Test ternary operators that are acceptable in all PHP versions.
     *
     * @return void
     */
    public function testStandardTernaryOperators()
    {
        $this->_sniffFile = $this->sniffFile('sniff-examples/ternary_operator.php');
        $this->assertNoViolation($this->_sniffFile, 5);
    }

    /**
     * 5.2 doesn't support elvis operator.
     *
     * @return void
     */
    public function testMissingMiddleExpression5dot2()
    {
        $this->_sniffFile = $this->sniffFile('sniff-examples/ternary_operator.php', '5.2-5.4');
        $this->assertError($this->_sniffFile, 8,
                "Middle may not be omitted from ternary operators in PHP < 5.3");
        $this->assertError($this->_sniffFile, 10,
                "Middle may not be omitted from ternary operators in PHP < 5.3");
    }

    /**
     * 5.3 does support elvis operator.
     *
     * @return void
     */
    public function testMissingMiddleExpression5dot3()
    {
        $this->_sniffFile = $this->sniffFile('sniff-examples/ternary_operator.php', '5.3');
        $this->assertNoViolation($this->_sniffFile, 8,
                "Middle may not be omitted from ternary operators in PHP < 5.3");
        $this->assertNoViolation($this->_sniffFile, 10,
                "Middle may not be omitted from ternary operators in PHP < 5.3");
    }

}
