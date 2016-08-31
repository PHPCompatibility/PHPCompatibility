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
    const ERROR_TYPE_VARIABLE = 'a variable argument';
    const ERROR_TYPE_ZERO = '0 as an argument';

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
     * @param string $errorType The error type.
     *
     * @return void
     */
    public function testBreakAndContinueVariableArgument($line, $errorType)
    {
        $this->assertError($this->_sniffFile, $line, 'Using ' . $errorType . ' on break or continue is forbidden since PHP 5.4');
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
            array(53, self::ERROR_TYPE_VARIABLE),
            array(57, self::ERROR_TYPE_VARIABLE),
            array(62, self::ERROR_TYPE_VARIABLE),
            array(66, self::ERROR_TYPE_VARIABLE),
            array(71, self::ERROR_TYPE_VARIABLE),
            array(75, self::ERROR_TYPE_VARIABLE),
            array(80, self::ERROR_TYPE_VARIABLE),
            array(84, self::ERROR_TYPE_VARIABLE),
            array(89, self::ERROR_TYPE_VARIABLE),
            array(93, self::ERROR_TYPE_VARIABLE),
            array(98, self::ERROR_TYPE_VARIABLE),
            array(102, self::ERROR_TYPE_VARIABLE),
            array(107, self::ERROR_TYPE_ZERO),
            array(111, self::ERROR_TYPE_ZERO),
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

