<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2026 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewTrimCharactersDefault sniff.
 *
 * @group newTrimCharactersDefault
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\NewTrimCharactersDefaultSniff
 *
 * @since 10.0.0
 */
final class NewTrimCharactersDefaultUnitTest extends BaseSniffTestCase
{

    /**
     * Verify that an error gets thrown when the $characters parameter is not set and both PHP < 8.6
     * as well as PHP 8.6+ needs to be supported.
     *
     * @dataProvider dataNewTrimCharactersDefault
     *
     * @param int    $line         Line number where the error should occur.
     * @param string $functionName The name of the function called.
     *
     * @return void
     */
    public function testNewTrimCharactersDefault($line, $functionName)
    {
        $file  = $this->sniffFile(__FILE__, '8.5-8.6');
        $error = \sprintf(
            'The default value of the $characters parameter for %s() includes the form feed character as of PHP 8.6. It was changed from " \n\r\t\v\x00" to " \f\n\r\t\v\x00"',
            $functionName
        );

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @return array<array<int|string>>
     */
    public static function dataNewTrimCharactersDefault()
    {
        return [
            [26, 'trim'],
            [27, 'ltrim'],
            [28, 'RTrim'],
            [29, 'ltrim'],
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
        $file = $this->sniffFile(__FILE__, '8.5-8.6');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @return array<array<int>>
     */
    public static function dataNoFalsePositives()
    {
        $data = [];

        // No errors expected on the first 22 lines.
        for ($line = 1; $line <= 22; $line++) {
            $data[] = [$line];
        }

        return $data;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @dataProvider dataNoViolationsInFileOnValidVersion
     *
     * @param string $testVersion The testVersion to use.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion($testVersion)
    {
        $file = $this->sniffFile(__FILE__, $testVersion);
        $this->assertNoViolation($file);
    }

    /**
     * Data provider.
     *
     * @return array<array<string>>
     */
    public static function dataNoViolationsInFileOnValidVersion()
    {
        return [
            ['5.6-8.5'],
            ['8.6-'],
        ];
    }
}
