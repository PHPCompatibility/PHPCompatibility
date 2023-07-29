<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Operators;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the ForbiddenNegativeBitshift sniff.
 *
 * @group forbiddenNegativeBitshift
 * @group operators
 *
 * @covers \PHPCompatibility\Sniffs\Operators\ForbiddenNegativeBitshiftSniff
 *
 * @since 7.0.0
 */
class ForbiddenNegativeBitshiftUnitTest extends BaseSniffTestCase
{

    /**
     * testForbiddenNegativeBitshift
     *
     * @dataProvider dataForbiddenNegativeBitshift
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testForbiddenNegativeBitshift($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertError($file, $line, 'Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0');
    }

    /**
     * dataForbiddenNegativeBitshift
     *
     * @see testForbiddenNegativeBitshift()
     *
     * @return array
     */
    public static function dataForbiddenNegativeBitshift()
    {
        return [
            [3],
            [4],
            [5],
            [7],
            [8],
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
            [10],
            [11],
            [12],
            [13],
            [16],
            [19],
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
