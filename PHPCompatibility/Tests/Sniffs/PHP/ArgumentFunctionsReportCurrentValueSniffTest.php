<?php
/**
 * Argument Functions Report Current Value sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Sniffs\PHP;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Argument Functions Report Current Value sniff tests
 *
 * @group deprecatedFunctions
 * @group functions
 *
 * @covers \PHPCompatibility\Sniffs\PHP\DeprecatedFunctionsSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Wim Godden <wim.godden@cu.be>
 */
class ArgumentFunctionsReportCurrentValueSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/argument_functions_report_current_value.php';


    /**
     * testDeprecatedFunction
     *
     * @dataProvider dataFunction
     *
     * @param string $functionName      Name of the function.
     * @param string $deprecatedIn      The PHP version in which the function was deprecated.
     * @param array  $lines             The line numbers in the test file which apply to this function.
     * @param string $okVersion         A PHP version in which the function was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     *
     * @return void
     */
    public function testFunction($functionName, $deprecatedIn, $lines, $okVersion, $deprecatedVersion = null)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(self::TEST_FILE, $errorVersion);
        $error        = "Functions inspecting arguments report the current parameter value Function since PHP 7.0. Verify if the value is changed somewhere.";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedFunction()
     *
     * @return array
     */
    public function dataFunction()
    {
        return array(
            array('func_get_arg', '7.0', array(4), '5.6'),
            array('func_get_args', '7.0', array(5), '5.6'),
            array('debug_backtrace', '7.0', array(6), '5.6'),
        );
    }
}
