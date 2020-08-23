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
 * Test the RemovedFunctionParameters sniff.
 *
 * @group removedFunctionParameters
 * @group functionUse
 *
 * @covers \PHPCompatibility\Sniffs\FunctionUse\RemovedFunctionParametersSniff
 *
 * @since 7.0.0
 */
class RemovedFunctionParametersUnitTest extends BaseSniffTest
{

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
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($testVersion)) ? $testVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The \"{$parameterName}\" parameter for function {$functionName}() is removed since PHP {$removedIn}";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
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
        return [
            ['ldap_first_attribute', 'ber_identifier', '5.2.4', [11], '5.2', '5.3'],
            ['ldap_next_attribute', 'ber_identifier', '5.2.4', [12], '5.2', '5.3'],
            ['mb_decode_numericentity', 'is_hex', '8.0', [24], '7.4'],
        ];
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
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $file  = $this->sniffFile(__FILE__, $deprecatedIn);
        $error = "The \"{$parameterName}\" parameter for function {$functionName}() is deprecated since PHP {$deprecatedIn}";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }

        $file  = $this->sniffFile(__FILE__, $removedIn);
        $error = "The \"{$parameterName}\" parameter for function {$functionName}() is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
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
        return [
            ['mktime', 'is_dst', '5.1', '7.0', [8], '5.0'],
            ['gmmktime', 'is_dst', '5.1', '7.0', [9], '5.0'],
            ['define', 'case_insensitive', '7.3', '8.0', [15], '7.2'],

            ['curl_version', 'age', '7.4', '8.0', [18], '7.3'],
            ['curl_version', 'age', '7.4', '8.0', [19], '7.3'],
            ['curl_version', 'age', '7.4', '8.0', [20], '7.3'],
            ['curl_version', 'age', '7.4', '8.0', [21], '7.3'],
        ];
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
        $file = $this->sniffFile(__FILE__, '99.0'); // High version beyond latest deprecation.
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
            [5],
            [14],
            [17],
            [23],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.0'); // Low version below the first deprecation.
        $this->assertNoViolation($file);
    }
}
