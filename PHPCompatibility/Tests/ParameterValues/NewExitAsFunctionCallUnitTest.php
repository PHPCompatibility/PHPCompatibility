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
 * Test the NewExitAsFunctionCall sniff.
 *
 * @group newExitAsFunctionCall
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\NewExitAsFunctionCallSniff
 *
 * @since 10.0.0
 */
final class NewExitAsFunctionCallUnitTest extends BaseSniffTestCase
{

    /**
     * Test an array parameter being passed to exit/die will be flagged with an error.
     *
     * @dataProvider dataTypeError
     *
     * @param int    $line Line number where the error should occur.
     * @param string $name Function call name.
     * @param string $type Parameter type expected to be mentioned in the message.
     *
     * @return void
     */
    public function testTypeError($line, $name, $type)
    {
        $file  = $this->sniffFile(__FILE__, '8.4');
        $error = " {$type} to {$name}() will result in a TypeError since PHP 8.4.";

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @return array<array<int|string>>
     */
    public static function dataTypeError()
    {
        return [
            [102, 'exit', 'array'],
            [103, 'die', 'array'],
            [104, 'exit', 'array'],
            [105, 'die', 'array'],
            [108, 'die', 'resource'],

            // Scalars and null with strict_types=1.
            [129, 'exit', 'boolean'],
            [130, 'die', 'boolean'],
            [133, 'exit', 'float'],
            [134, 'exit', 'float'],
            [137, 'die', 'float'],
            [138, 'die', 'null'],
        ];
    }


    /**
     * Test a boolean parameter being passed to exit/die will be flagged.
     *
     * @dataProvider dataBooleanParamFound
     *
     * @param int    $line Line number where the error should occur.
     * @param string $name Function call name.
     *
     * @return void
     */
    public function testBooleanParamWarning($line, $name)
    {
        $file  = $this->sniffFile(__FILE__, '8.4');
        $error = "Passing a boolean value to {$name}() will be interpreted as an exit code instead of as a status message since PHP 8.4.";

        $this->assertWarning($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @return array<array<int|string>>
     */
    public static function dataBooleanParamFound()
    {
        $data = [
            [118, 'exit'],
            [119, 'die'],
            [142, 'die'],
        ];

        // This constant _should_ be available since PHP 5.2.7, but wasn't always a boolean.
        if (\defined('PHP_ZTS') && \is_bool(\PHP_ZTS)) {
            $data[] = [120, 'exit'];
        }

        return $data;
    }


    /**
     * Test a float parameter being passed to exit/die will be flagged.
     *
     * @dataProvider dataFloatParamFound
     *
     * @param int    $line Line number where the error should occur.
     * @param string $name Function call name.
     *
     * @return void
     */
    public function testFloatParamFound($line, $name)
    {
        $file  = $this->sniffFile(__FILE__, '8.4');
        $error = "Passing a floating point value to {$name}() will be interpreted as an exit code instead of as a status message since PHP 8.4.";

        $this->assertWarning($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @return array<array<int|string>>
     */
    public static function dataFloatParamFound()
    {
        $data = [
            [121, 'exit'],
            [122, 'exit'],
            [123, 'die'],
        ];

        // PHP native constant from an optional extension used in the test. The constant may not exist in the test environment.
        if (\defined('TRADER_REAL_MIN')) {
            $data[] = [124, 'die'];
        }

        return $data;
    }


    /**
     * Test a null parameter being passed to exit/die will be flagged.
     *
     * @dataProvider dataNullParamFound
     *
     * @param int    $line Line number where the error should occur.
     * @param string $name Function call name.
     *
     * @return void
     */
    public function testNullParamFound($line, $name)
    {
        $file  = $this->sniffFile(__FILE__, '8.4');
        $error = "Passing null to {$name}() will be interpreted as an exit code instead of as a status message since PHP 8.4.";

        $this->assertWarning($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @return array<array<int|string>>
     */
    public static function dataNullParamFound()
    {
        return [
            [125, 'die'],
        ];
    }


    /**
     * Test that the use of fully qualified exit/die will be flagged with an error.
     *
     * @dataProvider dataFullyQualifiedError
     *
     * @param int    $line Line number where the error should occur.
     * @param string $name Function call name.
     *
     * @return void
     */
    public function testFullyQualifiedError($line, $name)
    {
        $file  = $this->sniffFile(__FILE__, '8.4');
        $error = "Using \"{$name}\" as a fully qualified function call is not allowed in PHP 8.3 or earlier.";

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @return array<array<int|string>>
     */
    public static function dataFullyQualifiedError()
    {
        return [
            [148, 'exit'],
            [149, 'die'],
        ];
    }


    /**
     * Test that there are no false positives.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array<array<int>>
     */
    public static function dataNoFalsePositives()
    {
        $data = [];

        // No errors expected on the first 96 lines.
        for ($line = 1; $line <= 96; $line++) {
            $data[] = [$line];
        }

        // PHP native constant (bool) which wasn't _always_ a boolean.
        if (\defined('PHP_ZTS') === false || \is_bool(\PHP_ZTS) === false) {
            $data[] = [120];
        }

        // PHP native constant (float) from an optional extension used in the test.
        // The constant may not exist in the test environment.
        // Note: unfortunately, this appears to be the only PHP extension which has float typed constants,
        // so there is no alternative we could use for this test (at this time).
        if (\defined('TRADER_REAL_MIN') === false) {
            $data[] = [124];
        }

        // Parse error/live coding.
        for ($line = 150; $line <= 153; $line++) {
            $data[] = [$line];
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
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file);
    }
}
