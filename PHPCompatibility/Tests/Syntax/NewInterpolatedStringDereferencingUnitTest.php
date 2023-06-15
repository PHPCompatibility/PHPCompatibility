<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Syntax;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewInterpolatedStringDereferencing sniff.
 *
 * @group newInterpolatedStringDereferencing
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\NewInterpolatedStringDereferencingSniff
 *
 * @since 10.0.0
 */
class NewInterpolatedStringDereferencingUnitTest extends BaseSniffTest
{

    /**
     * Test that an error is thrown for interpolated string dereferencing prior to PHP 8.0.
     *
     * @dataProvider dataInterpolatedStringDereferencing
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testInterpolatedStringDereferencing($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertError($file, $line, 'Dereferencing of interpolated strings is not supported in PHP 7.4 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testInterpolatedStringDereferencing
     *
     * @return array
     */
    public static function dataInterpolatedStringDereferencing()
    {
        return [
            [24],
            [25],
            [26],
            [28],
            [31],
        ];
    }

    /**
     * Verify the sniff does not throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public static function dataNoFalsePositives()
    {
        return [
            // No interpolation, no dereferencing.
            [4],
            [5],
            [6],

            // Interpolation, no dereferencing.
            [10],
            [11],
            [12],

            // Dereferencing, no interpolation.
            [16],
            [17],
            [18],
            [19],
            [20],
        ];
    }

    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertNoViolation($file);
    }
}
