<?php
/**
 * New Functions Parameter Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New Functions Parameter Sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewFunctionParameterSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/new_function_parameter.php';

    /**
     * testInvalidParameter
     *
     * @dataProvider dataInvalidParameter
     *
     * @param string $functionName      Function name.
     * @param string $parameterName     Parameter name.
     * @param string $lastVersionBefore The PHP version just *before* the parameter was introduced.
     * @param array  $lines             The line numbers in the test file which apply to this class.
     * @param string $okVersion         A PHP version in which the parameter was ok to be used.
     *
     * @return void
     */
    public function testInvalidParameter($functionName, $parameterName, $lastVersionBefore, $lines, $okVersion)
    {
        $file = $this->sniffFile(self::TEST_FILE, $lastVersionBefore);
        foreach ($lines as $line) {
            $this->assertError($file, $line, "The function {$functionName} does not have a parameter {$parameterName} in PHP version {$lastVersionBefore} or earlier");
        }

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testInvalidParameter()
     *
     * @return array
     */
    public function dataInvalidParameter()
    {
        return array(
            array('dirname', 'depth', '5.6', array(7), '7.0'),
            array('unserialize', 'options', '5.6', array(8), '7.0'),
            array('session_start', 'options', '5.6', array(9), '7.0'),
            array('strstr', 'before_needle', '5.2', array(10), '5.3'),
        );
    }


    /**
     * testValidParameter
     *
     * @dataProvider dataValidParameter
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testValidParameter($line)
    {
        $file = $this->sniffFile(self::TEST_FILE);
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testValidParameter()
     *
     * @return array
     */
    public function dataValidParameter()
    {
        return array(
            array(4),
        );
    }
}
