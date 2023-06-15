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
 * Test the NewMagicConstantDereferencing sniff.
 *
 * @group newMagicConstantDereferencing
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\NewMagicConstantDereferencingSniff
 *
 * @since 10.0.0
 */
class NewMagicConstantDereferencingUnitTest extends BaseSniffTest
{

    /**
     * Test that an error is thrown for magic constant dereferencing prior to PHP 8.
     *
     * @dataProvider dataNewMagicConstantDereferencing
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewMagicConstantDereferencing($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertError($file, $line, 'Dereferencing of magic constants is not present in PHP version 7.4 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testNewMagicConstantDereferencing()
     *
     * @return array
     */
    public static function dataNewMagicConstantDereferencing()
    {
        return [
            [15],
            [16],
            [17],
            [18],
            [19],
            [20],
            [21],
            [22],
        ];
    }


    /**
     * Verify the sniff does not throw false positives for valid code, nor for code which is still invalid.
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
            [6],
            [7],
            [10],
            [29],
            [30],
            [31],
            [32],
            [33],
            [36],
            [40],
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
