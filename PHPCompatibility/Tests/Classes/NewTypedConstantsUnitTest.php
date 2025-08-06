<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Classes;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewTypedConstants sniff.
 *
 * @group newTypedConstants
 * @group classes
 *
 * @covers \PHPCompatibility\Sniffs\Classes\NewTypedConstantsSniff
 *
 * @since 10.0.0
 */
final class NewTypedConstantsUnitTest extends BaseSniffTestCase
{

    /**
     * Verify that all type declarations are flagged when the minimum supported PHP version < 8.3.
     *
     * @dataProvider dataNewTypedConstants
     *
     * @param int  $line            The line number on which the error should occur.
     * @param bool $testNoViolation Whether or not to test noViolation for PHP 8.3.
     *
     * @return void
     */
    public function testNewTypedConstants($line, $testNoViolation = true)
    {
        $file = $this->sniffFile(__FILE__, '8.2');
        $this->assertError($file, $line, 'Typed constants are not supported in PHP 8.2 or earlier');

        if ($testNoViolation === true) {
            $file = $this->sniffFile(__FILE__, '8.3');
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testNewTypedConstants()
     *
     * @return array<array<int|bool>>
     */
    public static function dataNewTypedConstants()
    {
        return [
            [26],
            [27],
            [28],
            [29],
            [30],
            [33],
            [34],
            [35],
            [36],
            [39],
            [40],
            [41],
            [44],
            [45],
            [48],
            [49],
            [52],
            [53],
            [64],
            [65],
            [70, false],
            [71, false],
            [72, false],
            [73, false],
            [74, false],
            [75, false],
        ];
    }


    /**
     * Verify the sniff doesn't throw false positives for non-typed constants.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositivesNewTypedConstants($line)
    {
        $file = $this->sniffFile(__FILE__, '8.2');
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
        $cases = [];
        // No errors expected on the first 22 lines.
        for ($line = 1; $line <= 22; $line++) {
            $cases[] = [$line];
        }

        return $cases;
    }

    /**
     * Verify that invalid type declarations are flagged correctly.
     *
     * @dataProvider dataInvalidConstantType
     *
     * @param int    $line The line number on which the error should occur.
     * @param string $type The invalid type which should be detected.
     *
     * @return void
     */
    public function testInvalidConstantType($line, $type)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertError($file, $line, "$type is not supported as a type declaration for constants");
    }

    /**
     * Data provider.
     *
     * @see testInvalidConstantType()
     *
     * @return array<array<int|string>>
     */
    public static function dataInvalidConstantType()
    {
        return [
            [70, 'void'],
            [71, 'never'],
            [72, 'callable'],
            [73, 'callable'],
        ];
    }

    /**
     * Verify that invalid "long" type declarations are flagged correctly.
     *
     * @dataProvider dataInvalidLongType
     *
     * @param int    $line The line number on which the error should occur.
     * @param string $type The invalid type which should be detected.
     *
     * @return void
     */
    public function testInvalidLongType($line, $type)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertWarning($file, $line, "$type is not supported as a type declaration for constants");
    }

    /**
     * Data provider.
     *
     * @see testInvalidLongType()
     *
     * @return array<array<int|string>>
     */
    public static function dataInvalidLongType()
    {
        return [
            [74, 'boolean'],
            [75, 'integer'],
        ];
    }

    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will also throw warnings/errors
     * about invalid typed constants.
     */
}
