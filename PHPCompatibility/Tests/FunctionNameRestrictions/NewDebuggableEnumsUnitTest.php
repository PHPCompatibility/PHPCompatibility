<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionNameRestrictions;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewDebuggableEnums sniff.
 *
 * @group newDebuggableEnums
 * @group functionNameRestrictions
 * @group magicMethods
 *
 * @covers \PHPCompatibility\Sniffs\FunctionNameRestrictions\NewDebuggableEnumsSniff
 *
 * @since 10.0.0
 */
final class NewDebuggableEnumsUnitTest extends BaseSniffTestCase
{

    /**
     * Verify enums declaring the __debugInfo() method are flagged for PHP 8.5 and lower.
     *
     * @dataProvider dataNewDebuggableEnums
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewDebuggableEnums($line)
    {
        $file = $this->sniffFile(__FILE__, '8.5');
        $this->assertError($file, $line, 'The magic method __debugInfo() could not be declared on an enum prior to PHP 8.6.');
    }

    /**
     * Data provider.
     *
     * @return array<array<int>>
     */
    public static function dataNewDebuggableEnums()
    {
        return [
            [48],
            [56],
        ];
    }


    /**
     * Test that there are no false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.5');
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

        // No errors expected on the first 41 lines.
        for ($line = 1; $line <= 41; $line++) {
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
        $file = $this->sniffFile(__FILE__, '8.6');
        $this->assertNoViolation($file);
    }
}
