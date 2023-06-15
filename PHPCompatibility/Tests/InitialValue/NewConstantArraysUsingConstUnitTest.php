<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\InitialValue;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewConstantArraysUsingConst sniff.
 *
 * @group newConstantArraysUsingConst
 * @group initialValue
 *
 * @covers \PHPCompatibility\Sniffs\InitialValue\NewConstantArraysUsingConstSniff
 *
 * @since 7.1.4
 */
class NewConstantArraysUsingConstUnitTest extends BaseSniffTest
{

    /**
     * testConstantArraysUsingConst
     *
     * @dataProvider dataConstantArraysUsingConst
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testConstantArraysUsingConst($line)
    {
        $file = $this->sniffFile(__FILE__, '5.5');
        $this->assertError($file, $line, 'Constant arrays using the "const" keyword are not allowed in PHP 5.5 or earlier');
    }

    /**
     * Data provider dataConstantArraysUsingConst.
     *
     * @see testConstantArraysUsingConst()
     *
     * @return array
     */
    public static function dataConstantArraysUsingConst()
    {
        return [
            [3],
            [4],
            [6],
            [12],
            [19],
            [25],
            [37],
            [39],
            [41],
        ];
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '5.5');
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
            [31],
            [33],
            [36],
            [38],
            [40],
            [42],
            [46],
            [47],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertNoViolation($file);
    }
}
