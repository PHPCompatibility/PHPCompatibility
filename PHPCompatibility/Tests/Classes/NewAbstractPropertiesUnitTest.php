<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2022 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Classes;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewAbstractProperties sniff.
 *
 * @group newAbstractProperties
 * @group classes
 *
 * @covers \PHPCompatibility\Sniffs\Classes\NewAbstractPropertiesSniff
 *
 * @since 10.0.0
 */
final class NewAbstractPropertiesUnitTest extends BaseSniffTestCase
{

    /**
     * Test that an error is thrown for OO properties declared as abstract.
     *
     * @dataProvider dataAbstractProperties
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testAbstractProperties($line)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertError($file, $line, 'The abstract modifier for OO properties is not supported in PHP 8.3 or earlier.');
    }

    /**
     * Data provider.
     *
     * @return array<array<int>>
     */
    public static function dataAbstractProperties()
    {
        return [
            [69],
            [70],
            [71],
            [72],
            [75],
            [76],
            [82],
            [83],
            [88],
            [95],
            [102],
        ];
    }


    /**
     * Verify that there are no false positives for valid code/code errors outside the scope of this sniff.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
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
        for ($line = 1; $line <= 61; $line++) {
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
        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertNoViolation($file);
    }
}
