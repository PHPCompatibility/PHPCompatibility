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
 * Test the RemovedProprietaryCSVEscaping sniff.
 *
 * @group removedProprietaryCSVEscaping
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedProprietaryCSVEscapingSniff
 *
 * @since 10.0.0
 */
final class RemovedProprietaryCSVEscapingUnitTest extends BaseSniffTestCase
{

    /**
     * Test the sniff correctly detects an empty string being passed as the $escape parameter.
     *
     * @dataProvider dataNewEmptyStringDisablesEscaping
     *
     * @param int    $line   Line number where the error should occur.
     * @param string $fnName Expected function name.
     *
     * @return void
     */
    public function testNewEmptyStringDisablesEscaping($line, $fnName)
    {
        $file  = $this->sniffFile(__FILE__, '7.3');
        $error = \sprintf('Passing an empty string as the $escape parameter to %s() to disable the proprietary CSV escaping mechanism was not supported in PHP 7.3 or earlier.', $fnName);

        $this->assertError($file, $line, $error);

        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNewEmptyStringDisablesEscaping()
     *
     * @return array<array<int|string>>
     */
    public static function dataNewEmptyStringDisablesEscaping()
    {
        return [
            [36, 'fputcsv'],
            [37, 'fgetcsv'],
        ];
    }

    /**
     * Test the sniff correctly detects an empty string being passed as the $escape parameter.
     *
     * @dataProvider dataChangedBehaviourStrGetCsvWithEmptyString
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testChangedBehaviourStrGetCsvWithEmptyString($line)
    {
        $file  = $this->sniffFile(__FILE__, '7.3-');
        $error = 'Passing an empty string as the $escape parameter to str_getcsv() would fall through to the default value ("\\") in PHP 7.3 and earlier, while in PHP 7.4+ it will disable the proprietary CSV escaping mechanism.';

        $this->assertError($file, $line, $error);

        // This error should only show if `testVersion` includes both PHP <= 7.3 as well as PHP >= 7.4.
        $file = $this->sniffFile(__FILE__, '7.3');
        $this->assertNoViolation($file, $line);

        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNewChangedBehaviourStrGetCsvWithEmptyString()
     *
     * @return array<array<int>>
     */
    public static function dataChangedBehaviourStrGetCsvWithEmptyString()
    {
        return [
            [43],
        ];
    }

    /**
     * Verify there are no false positives on code this sniff should ignore.
     *
     * @dataProvider dataNoFalsePositivesForEmptyStringParamValue
     *
     * @param int $line Line number.
     *
     * @return void
     */
    public function testNoFalsePositivesForEmptyStringParamValue($line)
    {
        $file = $this->sniffFile(__FILE__, '7.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesForEmptyStringParamValue()
     *
     * @return array<array<int>>
     */
    public static function dataNoFalsePositivesForEmptyStringParamValue()
    {
        $data = [];

        // No errors expected on the first 34 lines.
        for ($line = 1; $line <= 34; $line++) {
            $data[] = [$line];
        }

        return $data;
    }

    /**
     * Test the sniff correctly detects when the $escape parameter is not passed.
     *
     * @dataProvider dataDeprecatedNoEscapeParam
     *
     * @param int    $line   Line number where the error should occur.
     * @param string $fnName Expected function name.
     *
     * @return void
     */
    public function testDeprecatedNoEscapeParam($line, $fnName)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file, $line);

        $file    = $this->sniffFile(__FILE__, '8.4');
        $warning = \sprintf(
            'The $escape parameter must be passed when calling %s() as its default value will change in a future PHP version.',
            $fnName
        );

        $this->assertWarning($file, $line, $warning);
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedNoEscapeParam()
     *
     * @return array<array<int|string>>
     */
    public static function dataDeprecatedNoEscapeParam()
    {
        return [
            [24, 'fputcsv'],
            [25, 'fgetcsv'],
            [26, 'str_getcsv'],
            [27, 'fputcsv'],
            [28, 'fgetcsv'],
            [29, 'str_getcsv'],
            [30, 'fputcsv'],
            [31, 'fgetcsv'],
            [32, 'str_getcsv'],
            [33, 'fgetcsv'],
        ];
    }

    /**
     * Verify there are no false positives on code this sniff should ignore.
     *
     * @dataProvider dataNoFalsePositivesWhenEscapeParamProvided
     *
     * @param int $line Line number.
     *
     * @return void
     */
    public function testNoFalsePositivesWhenEscapeParamProvided($line)
    {
        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesWhenEscapeParamProvided()
     *
     * @return array<array<int>>
     */
    public static function dataNoFalsePositivesWhenEscapeParamProvided()
    {
        $data = [];

        // No errors expected on the first 22 lines.
        for ($line = 1; $line <= 22; $line++) {
            $data[] = [$line];
        }

        // No errors expected on the lines containing an empty string value for the parameter.
        for ($line = 35; $line <= 44; $line++) {
            $data[] = [$line];
        }

        return $data;
    }

    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will throw warnings/errors
     * about both for above as well as below a certain version.
     */
}
