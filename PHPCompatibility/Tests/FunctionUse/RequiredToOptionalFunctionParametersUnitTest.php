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
 * Test the RequiredToOptionalFunctionParameters sniff.
 *
 * @group requiredToOptionalFunctionParameters
 * @group functionUse
 *
 * @covers \PHPCompatibility\Sniffs\FunctionUse\RequiredToOptionalFunctionParametersSniff
 *
 * @since 7.0.3
 */
class RequiredToOptionalFunctionParametersUnitTest extends BaseSniffTest
{

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
     * @param string $testVersion   Optional PHP version to use for testing the flagged case.
     *
     * @return void
     */
    public function testRequiredOptionalParameter($functionName, $parameterName, $requiredUpTo, $lines, $okVersion, $testVersion = null)
    {
        $errorVersion = (isset($testVersion)) ? $testVersion : $requiredUpTo;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The \"{$parameterName}\" parameter for function {$functionName}() is missing, but was required for PHP version {$requiredUpTo} and lower";
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
     * @see testRequiredOptionalParameter()
     *
     * @return array
     */
    public function dataRequiredOptionalParameter()
    {
        return [
            ['preg_match_all', 'matches', '5.3', [8], '5.4'],
            ['stream_socket_enable_crypto', 'crypto_method', '5.5', [9], '5.6'],
            ['bcscale', 'scale', '7.2', [12], '7.3'],
            ['getenv', 'name', '7.0', [15, 78], '7.1'],
            ['array_push', 'values', '7.2', [18], '7.3'],
            ['array_unshift', 'values', '7.2', [21], '7.3'],
            ['ftp_fget', 'mode', '7.2', [24, 87], '7.3'],
            ['ftp_fput', 'mode', '7.2', [25], '7.3'],
            ['ftp_get', 'mode', '7.2', [26], '7.3'],
            ['ftp_nb_fget', 'mode', '7.2', [27], '7.3'],
            ['ftp_nb_fput', 'mode', '7.2', [28], '7.3'],
            ['ftp_nb_get', 'mode', '7.2', [29], '7.3'],
            ['ftp_nb_put', 'mode', '7.2', [30], '7.3'],
            ['ftp_put', 'mode', '7.2', [31], '7.3'],
            ['array_merge', 'arrays', '7.3', [35], '7.4'],
            ['array_merge_recursive', 'arrays', '7.3', [36], '7.4'],
            ['fgetcsv', 'length', '5.0', [39], '5.1'],
            ['xmlwriter_write_element', 'content', '5.2.2', [41], '5.3', '5.2'],
            ['xmlwriter_write_element_ns', 'content', '5.2.2', [42], '5.3', '5.2'],
            ['imagepolygon', 'num_points', '7.4', [45], '8.0'],
            ['imageopenpolygon', 'num_points', '7.4', [46], '8.0'],
            ['imagefilledpolygon', 'num_points', '7.4', [47], '8.0'],
            ['array_diff_assoc', 'arrays', '7.4', [60], '8.0'],
            ['array_diff_key', 'arrays', '7.4', [61], '8.0'],
            ['array_diff_uassoc', 'rest', '7.4', [62, 84], '8.0'],
            ['array_diff_ukey', 'rest', '7.4', [63], '8.0'],
            ['array_diff', 'arrays', '7.4', [64], '8.0'],
            ['array_intersect_assoc', 'arrays', '7.4', [65], '8.0'],
            ['array_intersect_key', 'arrays', '7.4', [66], '8.0'],
            ['array_intersect_uassoc', 'rest', '7.4', [67], '8.0'],
            ['array_intersect_ukey', 'rest', '7.4', [68], '8.0'],
            ['array_intersect', 'arrays', '7.4', [69], '8.0'],
            ['array_udiff_assoc', 'rest', '7.4', [70], '8.0'],
            ['array_udiff_uassoc', 'rest', '7.4', [71], '8.0'],
            ['array_udiff', 'rest', '7.4', [72], '8.0'],
            ['array_uintersect_assoc', 'rest', '7.4', [73], '8.0'],
            ['array_uintersect_uassoc', 'rest', '7.4', [74], '8.0'],
            ['array_uintersect', 'rest', '7.4', [75], '8.0'],
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
        $file = $this->sniffFile(__FILE__, '5.3'); // Version before earliest required/optional change.
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
            [11],
            [14],
            [17],
            [20],
            [23],
            [32],
            [34],
            [38],
            [44],
            [49],
            [50],
            [51],
            [52],
            [53],
            [54],
            [55],
            [56],
            [57],
            [58],
            [79],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '99.0'); // High version beyond latest required/optional change.
        $this->assertNoViolation($file);
    }
}
