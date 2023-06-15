<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Util\Tests\Helpers\TokenGroup;

use PHPCompatibility\Helpers\TokenGroup;
use PHPCSUtils\TestUtils\UtilityMethodTestCase;

/**
 * Tests for the `isNumericCalculation()` utility function.
 *
 * @group utilityIsNumericCalculation
 * @group utilityFunctions
 *
 * @covers \PHPCompatibility\Helpers\TokenGroup::isNumericCalculation
 *
 * @since 9.0.0
 */
final class IsNumericCalculationUnitTest extends UtilityMethodTestCase
{

    /**
     * Verify the functionality of the `isNumericCalculation()` function.
     *
     * @dataProvider dataIsNumericCalculation
     *
     * @param string $commentString The comment which prefaces the target snippet in the test file.
     * @param bool   $isCalc        The expected return value for isNumericCalculation().
     *
     * @return void
     */
    public function testIsNumericCalculation($commentString, $isCalc)
    {
        $start = ($this->getTargetToken($commentString, \T_EQUAL) + 1);
        $end   = ($this->getTargetToken($commentString, \T_SEMICOLON) - 1);

        $result = TokenGroup::isNumericCalculation(self::$phpcsFile, $start, $end);
        $this->assertSame($isCalc, $result);
    }

    /**
     * Data provider.
     *
     * @see testIsNumericCalculation()
     *
     * @return array
     */
    public static function dataIsNumericCalculation()
    {
        return [
            ['/* test A1 */', false],
            ['/* test A2 */', false],
            ['/* test A3 */', false],
            ['/* test A4 */', false],
            ['/* test A5 */', false],
            ['/* test A6 */', false],

            ['/* test B1 */', true],
            ['/* test B2 */', true],
            ['/* test B3 */', true],
            ['/* test B4 */', true],
            ['/* test B5 */', true],
            ['/* test B6 */', true],
            ['/* test B7 */', true],
        ];
    }
}
