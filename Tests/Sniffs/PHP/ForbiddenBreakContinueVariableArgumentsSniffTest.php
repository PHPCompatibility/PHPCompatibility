<?php
/**
 * Forbidden break and continue variable arguments sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Forbidden break and continue variable arguments sniff test
 *
 * Checks for using break and continue with a variable afterwards
 *     break $varname
 *     continue $varname
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class ForbiddenBreakContinueVariableArgumentsSniffTest extends BaseSniffTest
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

        $this->_sniffFile = $this->sniffFile('sniff-examples/forbidden_break_continue_variable_argument.php');
    }

    /**
     * Test break
     *
     * @return void
     */
    public function testBreakAndContinueAlone()
    {
        $this->assertNoViolation($this->_sniffFile, 6);
        $this->assertNoViolation($this->_sniffFile, 10);
    }

    /**
     * testBreakAndContinueWithInteger
     *
     * @return void
     */
    public function testBreakAndContinueWithInteger()
    {
        $this->assertNoViolation($this->_sniffFile, 18);
        $this->assertNoViolation($this->_sniffFile, 22);
    }

    /**
     * testBreakAndContinueWithVariable
     *
     * @return void
     */
    public function testBreakAndContinueWithVariable()
    {
        $this->assertError($this->_sniffFile, 32, 'Using a variable argument on break or continue is forbidden since PHP 5.4');
        $this->assertError($this->_sniffFile, 36, 'Using a variable argument on break or continue is forbidden since PHP 5.4');

    }

    /**
     * testBreakAndContinueWithFunction
     *
     * @return void
     */
    public function testBreakAndContinueWithFunction()
    {
        $this->assertError($this->_sniffFile, 45, 'Using a variable argument on break or continue is forbidden since PHP 5.4');
        $this->assertError($this->_sniffFile, 49, 'Using a variable argument on break or continue is forbidden since PHP 5.4');

    }


    /**
     * testBreakAndContinueWithConstant
     *
     * @return void
     */
    public function testBreakAndContinueWithConstant()
    {
        $this->assertNoViolation($this->_sniffFile, 58);
        $this->assertNoViolation($this->_sniffFile, 62);
    }

    public function testSettingTestVersion()
    {
        $file = $this->sniffFile('sniff-examples/forbidden_break_continue_variable_argument.php', '5.3');

        $this->assertNoViolation($file);
    }
}

