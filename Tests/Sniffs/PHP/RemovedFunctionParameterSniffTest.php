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
     * @param string $testVersion   Optional. A PHP version in which to test for the removal message.
     *
     * @return void
     */
    public function testRemovedParameter($functionName, $parameterName, $removedIn, $lines, $okVersion, $testVersion = null)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        if (isset($testVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $testVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $removedIn);
        }
        foreach ($lines as $line) {
            $this->assertError($file, $line, "The \"{$parameterName}\" parameter for function {$functionName} was removed in PHP version {$removedIn}");
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
            array('ldap_first_attribute', 'ber_identifier', '5.2.4', array(11), '5.2', '5.3'),
            array('ldap_next_attribute', 'ber_identifier', '5.2.4', array(12), '5.2', '5.3'),
        );
    }


    /**
     * testDeprecatedRemovedParameter
     *
     * @dataProvider dataDeprecatedRemovedParameter
     *
     * @param string $functionName  Function name.
     * @param string $parameterName Parameter name.
     * @param string $deprecatedIn  The PHP version in which the parameter was deprecated.
     * @param string $removedIn     The PHP version in which the parameter was removed.
     * @param array  $lines         The line numbers in the test file which apply to this class.
     * @param string $okVersion     A PHP version in which the parameter was ok to be used.
     *
     * @return void
     */
    public function testDeprecatedRemovedParameter($functionName, $parameterName, $deprecatedIn, $removedIn, $lines, $okVersion)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $file = $this->sniffFile(self::TEST_FILE, $deprecatedIn);
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, "The \"{$parameterName}\" parameter for function {$functionName} was deprecated in PHP version {$deprecatedIn}");
        }

        $file = $this->sniffFile(self::TEST_FILE, $removedIn);
        foreach ($lines as $line) {
            $this->assertError($file, $line, "The \"{$parameterName}\" parameter for function {$functionName} was deprecated in PHP version {$deprecatedIn} and removed in PHP version {$removedIn}");
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedRemovedParameter()
     *
     * @return array
     */
    public function dataDeprecatedRemovedParameter()
    {
        return array(
            array('mktime', 'is_dst', '5.1', '7.0', array(8), '5.0'),
            array('gmmktime', 'is_dst', '5.1', '7.0', array(9), '5.0'),
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
