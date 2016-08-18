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
    const TEST_FILE = 'sniff-examples/forbidden_break_continue_variable_argument.php';

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

        $this->_sniffFile = $this->sniffFile(self::TEST_FILE);
    }


    /**
     * testAllowedBreakAndContinueVariableArgument
     *
     * In PHP 5.3, none of the statements should give an error.
     *
     * @group forbiddenBreakContinue
     *
     * @return void
     */
    public function testAllowedBreakAndContinueVariableArgument()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertNoViolation($file);
    }


    /**
     * testBreakAndContinueVariableArgument
     *
     * @group forbiddenBreakContinue
     *
     * @dataProvider dataBreakAndContinueVariableArgument
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testBreakAndContinueVariableArgument($line)
    {
        $this->assertError($this->_sniffFile, $line, 'Using a variable argument on break or continue is forbidden since PHP 5.4');
    }

    /**
     * Data provider.
     *
     * @see testBreakAndContinueVariableArgument()
     *
     * @return array
     */
    public function dataBreakAndContinueVariableArgument()
    {
        return array(
            array(53),
            array(57),
            array(62),
            array(66),
            array(71),
            array(75),
            array(80),
            array(84),
            array(89),
            array(93),
            array(98),
            array(102),
        );
    }


    /**
     * testNoViolation
     *
     * @group forbiddenBreakContinue
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
            array(8),
            array(12),
            array(17),
            array(21),
            array(26),
            array(30),
            array(35),
            array(39),
            array(44),
            array(48),
        );
    }
}

