<?php
/**
 * Optional Required Functions Parameter Sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Sniffs\PHP;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Optional Required Parameter Sniff test file
 *
 * @group optionalRequiredFunctionParameters
 * @group functionParameters
 *
 * @covers \PHPCompatibility\Sniffs\PHP\OptionalRequiredFunctionParametersSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class OptionalRequiredFunctionParametersSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/optional_required_function_parameters.php';

    /**
     * testOptionalRequiredParameterDeprecated
     *
     * @dataProvider dataOptionalRequiredParameterDeprecated
     *
     * @param string $functionName     Function name.
     * @param string $parameterName    Parameter name.
     * @param string $softRequiredFrom The last PHP version in which the parameter was still optional.
     * @param array  $lines            The line numbers in the test file which apply to this class.
     * @param string $okVersion        A PHP version in which to test for no violation.
     *
     * @return void
     */
    public function testOptionalRequiredParameterDeprecated($functionName, $parameterName, $softRequiredFrom, $lines, $okVersion)
    {
        $file  = $this->sniffFile(self::TEST_FILE, $softRequiredFrom);
        $error = "The \"{$parameterName}\" parameter for function {$functionName}() is missing. Passing this parameter is no longer optional. The optional nature of the parameter is deprecated since PHP {$softRequiredFrom}";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testOptionalRequiredParameterDeprecated()
     *
     * @return array
     */
    public function dataOptionalRequiredParameterDeprecated()
    {
        return array(
            array('parse_str', 'result', '7.2', array(7), '7.1'),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '99.0'); // High version beyond latest required/optional change.
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return array(
            array(4),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.1'); // Version before earliest required/optional change.
        $this->assertNoViolation($file);
    }

}
