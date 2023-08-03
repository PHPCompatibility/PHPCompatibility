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
 * Test the NewConstVisibility sniff.
 *
 * @group newConstVisibility
 * @group classes
 *
 * @covers \PHPCompatibility\Sniffs\Classes\NewConstVisibilitySniff
 *
 * @since 7.0.7
 */
class NewConstVisibilityUnitTest extends BaseSniffTestCase
{

    /**
     * Test that an error is thrown for OO constants declared with visibility.
     *
     * @dataProvider dataConstVisibility
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testConstVisibility($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertError($file, $line, 'Visibility indicators for OO constants are not supported in PHP 7.0 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testConstVisibility()
     *
     * @return array
     */
    public static function dataConstVisibility()
    {
        return [
            [10],
            [11],
            [12],

            [20],
            [23],
            [24],

            [33],
            [34],
            [35],

            [57],
            [58],
            [59],

            [70],
            [71],
            [72],

            [84],
            [85],
            [86],
        ];
    }


    /**
     * Verify that there are no false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
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
            [3],
            [7],
            [17],
            [30],
            [44],
            [48],
            [67],
            [80],
            [81],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.1');
        $this->assertNoViolation($file);
    }
}
