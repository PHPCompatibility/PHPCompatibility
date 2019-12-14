<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Util\Tests\Core;

use PHPCompatibility\Util\Tests\CoreMethodTestFrame;

/**
 * Tests for the `isNumber()`, `isPositiveNumber()` and `isNegativeNumber()` utility functions.
 *
 * @group utilityIsNumber
 * @group utilityFunctions
 *
 * @since 8.2.0
 */
class IsNumberUnitTest extends CoreMethodTestFrame
{

    /**
     * testIsNumber
     *
     * @dataProvider dataIsNumber
     *
     * @covers \PHPCompatibility\Sniff::isNumber
     * @covers \PHPCompatibility\Sniff::isPositiveNumber
     * @covers \PHPCompatibility\Sniff::isNegativeNumber
     *
     * @param string     $commentString    The comment which prefaces the target snippet in the test file.
     * @param bool       $allowFloats      Testing the snippets for integers only or floats as well ?
     * @param float|bool $isNumber         The expected return value for isNumber().
     * @param bool       $isPositiveNumber The expected return value for isPositiveNumber().
     * @param bool       $isNegativeNumber The expected return value for isNegativeNumber().
     *
     * @return void
     */
    public function testIsNumber($commentString, $allowFloats, $isNumber, $isPositiveNumber, $isNegativeNumber)
    {
        $start = ($this->getTargetToken($commentString, \T_EQUAL) + 1);
        $end   = ($this->getTargetToken($commentString, \T_SEMICOLON) - 1);

        $result = self::$helperClass->isNumber(self::$phpcsFile, $start, $end, $allowFloats);
        $this->assertSame($isNumber, $result);

        $result = self::$helperClass->isPositiveNumber(self::$phpcsFile, $start, $end, $allowFloats);
        $this->assertSame($isPositiveNumber, $result);

        $result = self::$helperClass->isNegativeNumber(self::$phpcsFile, $start, $end, $allowFloats);
        $this->assertSame($isNegativeNumber, $result);
    }

    /**
     * dataIsNumber
     *
     * @see testIsNumber()
     *
     * {@internal Case I13 is not tested here on purpose as the result depends on the
     * `testVersion` which we don't use in the utility tests.
     * For a `testVersion` with a minimum of PHP 7.0, the result will be false.
     * For a `testVersion` which includes any PHP 5 version, the result will be true.}
     *
     * @return array
     */
    public function dataIsNumber()
    {
        return array(
            array('/* test 1 */', true, false, false, false),
            array('/* test 2 */', true, false, false, false),
            array('/* test 4 */', true, false, false, false),
            array('/* test 5 */', true, false, false, false),
            array('/* test 6 */', true, false, false, false),
            array('/* test 7 */', true, false, false, false),
            array('/* test 8 */', true, false, false, false),
            array('/* test 9 */', true, false, false, false),
            array('/* test 10 */', true, false, false, false),

            array('/* test ZI1 */', false, 0, false, false),
            array('/* test ZI2 */', false, 0, false, false),
            array('/* test ZI3 */', false, -0, false, false),
            array('/* test ZI4 */', false, 0, false, false),
            array('/* test ZI5 */', false, -0, false, false),
            array('/* test ZI6 */', false, 0, false, false),
            array('/* test ZI7 */', false, 0, false, false),

            array('/* test ZI1 */', true, 0.0, false, false),
            array('/* test ZI2 */', true, 0.0, false, false),
            array('/* test ZI3 */', true, -0.0, false, false),
            array('/* test ZI4 */', true, 0.0, false, false),
            array('/* test ZI5 */', true, -0.0, false, false),
            array('/* test ZI6 */', true, 0.0, false, false),
            array('/* test ZI7 */', true, 0.0, false, false),

            array('/* test ZF1 */', false, false, false, false),
            array('/* test ZF2 */', false, false, false, false),

            array('/* test ZF1 */', true, 0.0, false, false),
            array('/* test ZF2 */', true, -0.0, false, false),

            array('/* test I1 */', false, 1, true, false),
            array('/* test I2 */', false, -10, false, true),
            array('/* test I3 */', false, 10, true, false),
            array('/* test I4 */', false, -10, false, true),
            array('/* test I5 */', false, 10, true, false),
            array('/* test I6 */', false, 10, true, false),
            array('/* test I7 */', false, 10, true, false),
            array('/* test I8 */', false, -10, false, true),
            array('/* test I9 */', false, 10, true, false),
            array('/* test I10 */', false, -1, false, true),
            array('/* test I11 */', false, 10, true, false),
            array('/* test I12 */', false, 10, true, false),
            array('/* test I14 */', false, -1, false, true),
            array('/* test I15 */', false, 123, true, false),
            array('/* test I16 */', false, 10, true, false),

            array('/* test I1 */', true, 1.0, true, false),
            array('/* test I2 */', true, -10.0, false, true),
            array('/* test I3 */', true, 10.0, true, false),
            array('/* test I4 */', true, -10.0, false, true),
            array('/* test I5 */', true, 10.0, true, false),
            array('/* test I6 */', true, 10.0, true, false),
            array('/* test I7 */', true, 10.0, true, false),
            array('/* test I8 */', true, -10.0, false, true),
            array('/* test I9 */', true, 10.0, true, false),
            array('/* test I10 */', true, -1.0, false, true),
            array('/* test I11 */', true, 10.0, true, false),
            array('/* test I12 */', true, 10.0, true, false),
            array('/* test I14 */', true, -1.0, false, true),
            array('/* test I15 */', true, 123.0, true, false),
            array('/* test I16 */', true, 10.0, true, false),

            array('/* test F1 */', false, false, false, false),
            array('/* test F2 */', false, false, false, false),
            array('/* test F3 */', false, false, false, false),
            array('/* test F4 */', false, false, false, false),
            array('/* test F5 */', false, false, false, false),
            array('/* test F6 */', false, false, false, false),
            array('/* test F7 */', false, false, false, false),
            array('/* test F8 */', false, false, false, false),
            array('/* test F9 */', false, false, false, false),
            array('/* test F10 */', false, false, false, false),
            array('/* test F11 */', false, false, false, false),

            array('/* test F1 */', true, 1.23, true, false),
            array('/* test F2 */', true, -10.123, false, true),
            array('/* test F3 */', true, 10.123, true, false),
            array('/* test F4 */', true, -10.123, false, true),
            array('/* test F5 */', true, 10.123, true, false),
            array('/* test F6 */', true, 10.123, true, false),
            array('/* test F7 */', true, 10.123, true, false),
            array('/* test F8 */', true, -10E3, false, true),
            array('/* test F9 */', true, -10e8, false, true),
            array('/* test F10 */', true, 10.123, true, false),
            array('/* test F11 */', true, 0.123, true, false),
        );
    }
}
