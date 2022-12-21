<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionUse;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the OptionalToRequiredFunctionParameters sniff.
 *
 * @group optionalToRequiredFunctionParameters
 * @group functionUse
 *
 * @covers \PHPCompatibility\Sniffs\FunctionUse\OptionalToRequiredFunctionParametersSniff
 *
 * @since 8.1.0
 */
class OptionalToRequiredFunctionParametersUnitTest extends BaseSniffTest
{

    /**
     * testOptionalRequiredParameterDeprecatedRemoved
     *
     * @dataProvider dataOptionalRequiredParameterDeprecatedRemoved
     *
     * @param string $functionName     Function name.
     * @param string $parameterName    Parameter name.
     * @param string $softRequiredFrom The last PHP version in which the parameter was still optional (deprecated).
     * @param string $hardRequiredFrom The last PHP version in which the parameter was still optional (removed).
     * @param array  $lines            The line numbers in the test file which apply to this class.
     * @param string $okVersion        A PHP version in which to test for no violation.
     *
     * @return void
     */
    public function testOptionalRequiredParameterDeprecatedRemoved($functionName, $parameterName, $softRequiredFrom, $hardRequiredFrom, $lines, $okVersion)
    {
        $file  = $this->sniffFile(__FILE__, $softRequiredFrom);
        $error = "The \"{$parameterName}\" parameter for function {$functionName}() is missing. Passing this parameter is no longer optional. The optional nature of the parameter is deprecated since PHP {$softRequiredFrom}";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }

        $file  = $this->sniffFile(__FILE__, $hardRequiredFrom);
        $error = "The \"{$parameterName}\" parameter for function {$functionName}() is missing. Passing this parameter is no longer optional. The optional nature of the parameter is deprecated since PHP {$softRequiredFrom} and removed since PHP {$hardRequiredFrom}";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }

        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testOptionalRequiredParameterDeprecatedRemoved()
     *
     * @return array
     */
    public function dataOptionalRequiredParameterDeprecatedRemoved()
    {
        return [
            ['mktime', 'hour', '5.1', '8.0', [19], '5.0'],
            ['crypt', 'salt', '5.6', '8.0', [8], '5.5'],
            ['parse_str', 'result', '7.2', '8.0', [7, 37], '7.1'],
        ];
    }


    /**
     * testOptionalRequiredParameterRemoved
     *
     * @dataProvider dataOptionalRequiredParameterRemoved
     *
     * @param string $functionName     Function name.
     * @param string $parameterName    Parameter name.
     * @param string $hardRequiredFrom The last PHP version in which the parameter was still optional.
     * @param array  $lines            The line numbers in the test file which apply to this class.
     * @param string $okVersion        A PHP version in which to test for no violation.
     *
     * @return void
     */
    public function testOptionalRequiredParameterRemoved($functionName, $parameterName, $hardRequiredFrom, $lines, $okVersion)
    {
        $file  = $this->sniffFile(__FILE__, $hardRequiredFrom);
        $error = "The \"{$parameterName}\" parameter for function {$functionName}() is missing. Passing this parameter is no longer optional. The optional nature of the parameter is removed since PHP {$hardRequiredFrom}";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }

        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testOptionalRequiredParameterRemoved()
     *
     * @return array
     */
    public function dataOptionalRequiredParameterRemoved()
    {
        return [
            ['gmmktime', 'hour', '8.0', [18, 31], '7.4'],
            ['mb_parse_str', 'result', '8.0', [22], '7.4'],
            ['openssl_seal', 'cipher_algo', '8.0', [25, 40], '7.4'],
            ['openssl_open', 'cipher_algo', '8.0', [28], '7.4'],
        ];
    }


    /**
     * Verify no false positives are thrown for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '99.0'); // High version beyond latest required/optional change.
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
        return [
            [4],
            [14],
            [15],
            [21],
            [32],
            [43],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.0'); // Version before earliest required/optional change.
        $this->assertNoViolation($file);
    }
}
