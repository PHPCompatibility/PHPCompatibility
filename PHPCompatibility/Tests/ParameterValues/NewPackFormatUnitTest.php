<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewPackFormat sniff.
 *
 * @group newPackFormat
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\NewPackFormatSniff
 *
 * @since 9.0.0
 */
final class NewPackFormatUnitTest extends BaseSniffTestCase
{

    /**
     * testNewPackFormat
     *
     * @dataProvider dataNewPackFormat
     *
     * @param int    $line           Line number where the error should occur.
     * @param string $code           Format code which should be detected.
     * @param string $functionName   Name of the function found.
     * @param string $errorVersion   The PHP version to use to test for the error.
     * @param string $okVersion      A PHP version in which the code is valid.
     * @param string $displayVersion Optional PHP version which is shown in the error message
     *                               if different from the $errorVersion.
     *
     * @return void
     */
    public function testNewPackFormat($line, $code, $functionName, $errorVersion, $okVersion, $displayVersion = null)
    {
        $file  = $this->sniffFile(__FILE__, $errorVersion);
        $error = \sprintf(
            'Passing the $format(s) "%s" to %s() is not supported in PHP %s or lower.',
            $code,
            $functionName,
            isset($displayVersion) ? $displayVersion : $errorVersion
        );
        $this->assertError($file, $line, $error);

        $file = $this->sniffFile(__FILE__, $okVersion);
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNewPackFormat()
     *
     * @return array<array<int|string>>
     */
    public static function dataNewPackFormat()
    {
        return [
            [8, 'Z', 'unpack', '5.4', '5.5'],
            [9, 'q', 'pack', '5.6', '7.0', '5.6.2'],
            [10, 'Q', 'pack', '5.6', '7.0', '5.6.2'],
            [11, 'J', 'unpack', '5.6', '7.0', '5.6.2'],
            [12, 'P', 'pack', '5.6', '7.0', '5.6.2'],
            [13, 'e', 'unpack', '7.0', '7.1', '7.0.14'],
            [14, 'E', 'pack', '7.0', '7.1', '7.0.14'],
            [15, 'g', 'pack', '7.0', '7.1', '7.0.14'],
            [16, 'G', 'unpack', '7.0', '7.1', '7.0.14'],
            [18, 'Z', 'pack', '5.4', '7.1'], // OK version set to beyond last error.
            [18, 'J', 'pack', '5.6', '7.1', '5.6.2'], // OK version set to beyond last error.
            [18, 'E', 'pack', '7.0', '7.1', '7.0.14'],
            [20, 'P', 'unpack', '5.6', '7.0', '5.6.2'],

            // Endianness modifiers on integers.
            [47, 's<', 'pack', '8.5', '8.6'],
            [47, 'l<', 'pack', '8.5', '8.6'],
            [47, 'q<', 'pack', '8.5', '8.6'],
            [48, 's>', 'pack', '8.5', '8.6'],
            [48, 'l>', 'pack', '8.5', '8.6'],
            [48, 'q>', 'pack', '8.5', '8.6'],
            [49, 'S<', 'pack', '8.5', '8.6'],
            [49, 'L>', 'pack', '8.5', '8.6'],
            [49, 'Q<', 'pack', '8.5', '8.6'],
            [50, 's<', 'pack', '8.5', '8.6'],
            [50, 'l>', 'pack', '8.5', '8.6'],
            [51, 's<', 'unpack', '8.5', '8.6'],
            [51, 'l<', 'unpack', '8.5', '8.6'],
            [52, 'S>', 'unpack', '8.5', '8.6'],
            [52, 'L<', 'unpack', '8.5', '8.6'],

            // Endianness modifiers on floats.
            [65, 'f<', 'pack', '8.5', '8.6'],
            [65, 'd<', 'pack', '8.5', '8.6'],
            [66, 'f>', 'pack', '8.5', '8.6'],
            [66, 'd>', 'pack', '8.5', '8.6'],
            [67, 'f<', 'unpack', '8.5', '8.6'],
            [67, 'd<', 'unpack', '8.5', '8.6'],
        ];
    }


    /**
     * Test that there are no false positives.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int    $line        Line number.
     * @param string $testVersion TestVersion to verify there are no false positives..
     *
     * @return void
     */
    public function testNoFalsePositives($line, $testVersion = '5.4')
    {
        $file = $this->sniffFile(__FILE__, $testVersion);
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array<array<int|string>>
     */
    public static function dataNoFalsePositives()
    {
        $data = [];

        // No errors expected on the first 6 lines.
        for ($line = 1; $line <= 6; $line++) {
            $data[] = [$line];
        }

        for ($line = 22; $line <= 40; $line++) {
            $data[] = [$line];
        }

        for ($line = 56; $line <= 59; $line++) {
            $data[] = [$line];
        }

        // The float codes with inherent endianness were introduced in PHP 7.0/7.1, so test with a higher version.
        for ($line = 71; $line <= 77; $line++) {
            $data[] = [$line, '7.2'];
        }

        return $data;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.6');
        $this->assertNoViolation($file);
    }
}
