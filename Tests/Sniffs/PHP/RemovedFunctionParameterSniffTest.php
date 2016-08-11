<?php
/**
 * Removed Functions Parameter Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Removed Functions Parameter Sniff test file
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class RemovedFunctionParameterSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/removed_function_parameter.php';

    /**
     * testRemovedParameter
     *
     * @dataProvider dataRemovedParameter
     *
     * @param string $functionName  Function name.
     * @param string $parameterName Parameter name.
     * @param string $removedIn     The PHP version in which the parameter was removed.
     * @param array  $lines         The line numbers in the test file which apply to this class.
     * @param string $okVersion     A PHP version in which the parameter was ok to be used.
     *
     * @return void
     */
    public function testRemovedParameter($functionName, $parameterName, $removedIn, $lines, $okVersion)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $file = $this->sniffFile(self::TEST_FILE, $removedIn);
        foreach ($lines as $line) {
            $this->assertError($file, $line, "The function {$functionName} does not have a parameter {$parameterName} in PHP version {$removedIn} or later");
        }
    }

    /**
     * Data provider.
     *
     * @see testRemovedParameter()
     *
     * @return array
     */
    public function dataRemovedParameter()
    {
        return array(
            array('mktime', 'is_dst', '7.0', array(8), '5.6'),
            array('gmmktime', 'is_dst', '7.0', array(9), '5.6'),
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
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
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
            array(5),
        );
    }

}
