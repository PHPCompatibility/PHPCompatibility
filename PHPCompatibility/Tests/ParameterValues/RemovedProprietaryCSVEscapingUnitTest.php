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
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.3');
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

        // No errors expected on the first 34 lines.
        for ($line = 1; $line <= 34; $line++) {
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
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertNoViolation($file);
    }
}
