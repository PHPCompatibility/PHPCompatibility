<?php
/**
 * VariableVariables sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * VariableVariables sniff test file
 *
 * @group variableVariables
 * @group variables
 *
 * @covers PHPCompatibility_Sniffs_PHP_VariableVariablesSniff
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class VariableVariablesSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/variable_variables.php';

    /**
     * testVariableVariables
     *
     * @dataProvider dataVariableVariables
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testVariableVariables($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertNoViolation($file, $line);

        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertError($file, $line, 'Indirect access to variables, properties and methods will be evaluated strictly in left-to-right order since PHP 7.0. Use curly braces to remove ambiguity.');
    }

    /**
     * Data provider.
     *
     * @see testVariableVariables()
     *
     * @return array
     */
    public function dataVariableVariables()
    {
        return array(
            array(4),
            array(5),
            array(6),
            array(7),
            array(8),
        );
    }


    /**
     * testNoFalsePositive
     *
     * @dataProvider dataNoFalsePositive
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositive($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositive()
     *
     * @return array
     */
    public function dataNoFalsePositive()
    {
        return array(
            array(11),
            array(12),
            array(13),
            array(14),
            array(15),

            array(18),
            array(19),
            array(20),
            array(21),
            array(22),
            array(23),
            array(24),
            array(25),
            array(26),
            array(27),
            array(28),
            array(29),
            array(32),
            array(37),
        );
    }
}
