<?php
/**
 * Required Optional Functions Parameter Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Required Optional Parameter Sniff test file
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class RequiredOptionalFunctionParameterSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/required_optional_function_parameters.php';

    /**
     * testRequiredOptionalParameter
     *
     * @dataProvider dataRequiredOptionalParameter
     *
     * @param string $functionName  Function name.
     * @param string $parameterName Parameter name.
     * @param string $requiredUpTo  The last PHP version in which the parameter was still required.
     * @param array  $lines         The line numbers in the test file which apply to this class.
     * @param string $okVersion     A PHP version in which to test for no violation.
     *
     * @return void
     */
    public function testRequiredOptionalParameter($functionName, $parameterName, $requiredUpTo, $lines, $okVersion)
    {
        $file = $this->sniffFile(self::TEST_FILE, $requiredUpTo);
        foreach ($lines as $line) {
            $this->assertError($file, $line, "The \"{$parameterName}\" parameter for function {$functionName} is missing, but was required for PHP version {$requiredUpTo} and lower");
        }

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testRequiredOptionalParameter()
     *
     * @return array
     */
    public function dataRequiredOptionalParameter()
    {
        return array(
            array('preg_match_all', 'matches', '5.3', array(8), '5.4'),
            array('stream_socket_enable_crypto', 'crypto_type', '5.5', array(9), '5.6'),
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
