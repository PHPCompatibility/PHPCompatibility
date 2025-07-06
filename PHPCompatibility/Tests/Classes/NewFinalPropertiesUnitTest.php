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
 * Test the NewFinalProperties sniff.
 *
 * @group newFinalProperties
 * @group classes
 *
 * @covers \PHPCompatibility\Sniffs\Classes\NewFinalPropertiesSniff
 *
 * @since 10.0.0
 */
class NewFinalPropertiesUnitTest extends BaseSniffTestCase
{

    /**
     * Test that an error is thrown for OO properties declared as final.
     *
     * @dataProvider dataFinalProperties
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testFinalProperties($line)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertError($file, $line, 'The final modifier for OO properties is not supported in PHP 8.3 or earlier.');
    }

    /**
     * Data provider.
     *
     * @return array<array<int>>
     */
    public static function dataFinalProperties()
    {
        return [
            [66],
            [67],
            [68],
            [69],
            [70],
            [71],
            [76],
            [79],
            [80],
            [85],
            [90],
            [97],
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
        for ($line = 1; $line <= 60; $line++) {
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
