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
 * Test the NewConstantArraysUsingDefine sniff.
 *
 * @group newConstantArraysUsingDefine
 * @group initialValue
 *
 * @covers \PHPCompatibility\Sniffs\InitialValue\NewConstantArraysUsingDefineSniff
 *
 * @since 7.0.0
 */
class NewConstantArraysUsingDefineUnitTest extends BaseSniffTest
{

    /**
     * testConstantArraysUsingDefine
     *
     * @dataProvider dataConstantArraysUsingDefine
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testConstantArraysUsingDefine($line)
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertError($file, $line, 'Constant arrays using define are not allowed in PHP 5.6 or earlier');
    }

    /**
     * Data provider dataConstantArraysUsingDefine.
     *
     * @see testConstantArraysUsingDefine()
     *
     * @return array
     */
    public function dataConstantArraysUsingDefine()
    {
        return [
            [3],
            [9],
            [39],
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
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return [
            [15],
            [18],
            [19],
            [22],
            [23],
            [26],
            [28],
            [31],
            [32],
            [35],
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
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertNoViolation($file);
    }
}
