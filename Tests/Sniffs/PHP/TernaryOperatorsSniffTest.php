<?php
/**
 * Ternary Operators Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Ternary Operators Sniff tests
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
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->_sniffFile = $this->sniffFile('sniff-examples/ternary_operator.php');
    }

    /**
     * Test ternary operators that are acceptable in all PHP versions.
     *
     * @return void
     */
    public function testStandardTernaryOperators()
    {
        $this->assertNoViolation($this->_sniffFile, 5);
    }

    /**
     * testHttpGetVars
     *
     * @return void
     */
    public function testMissingMiddleExpression()
    {
        global $Foo;
        $Foo = true;
        $this->assertWarning($this->_sniffFile, 8,
                "Middle may not be omitted from ternary operators in PHP < 5.3");
        $Foo = false;
    }

}
