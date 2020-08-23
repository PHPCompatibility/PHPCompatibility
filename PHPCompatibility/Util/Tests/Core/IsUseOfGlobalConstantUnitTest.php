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
 * Tests for the `isUseOfGlobalConstant()` utility function.
 *
 * @group utilityIsUseOfGlobalConstant
 * @group utilityFunctions
 *
 * @since 8.1.0
 */
class IsUseOfGlobalConstantUnitTest extends CoreMethodTestFrame
{

    /**
     * testIsUseOfGlobalConstant
     *
     * @dataProvider dataIsUseOfGlobalConstant
     *
     * @covers \PHPCompatibility\Sniff::isUseOfGlobalConstant
     *
     * @param string $commentString The comment which prefaces the target token in the test file.
     * @param string $expected      The expected boolean return value.
     *
     * @return void
     */
    public function testIsUseOfGlobalConstant($commentString, $expected)
    {
        $stackPtr = $this->getTargetToken($commentString, \T_STRING, 'PHP_VERSION_ID');
        $result   = self::$helperClass->isUseOfGlobalConstant(self::$phpcsFile, $stackPtr);
        $this->assertSame($expected, $result);
    }

    /**
     * dataIsUseOfGlobalConstant
     *
     * @see testIsUseOfGlobalConstant()
     *
     * @return array
     */
    public function dataIsUseOfGlobalConstant()
    {
        return [
            ['/* test 1 */', false],
            ['/* test 2 */', false],
            ['/* test 3 */', false],
            ['/* test 4 */', false],
            ['/* test 5 */', false],
            ['/* test 6 */', false],
            ['/* test 7 */', false],
            ['/* test 8 */', false],
            ['/* test 9 */', false],
            ['/* test 10 */', false],
            ['/* test 11 */', false],
            ['/* test 12 */', false],
            ['/* test 13 */', false],
            ['/* test 14 */', false],
            ['/* test 15 */', false],
            ['/* test 16 */', false],
            ['/* test 17 */', false],
            ['/* test 18 */', false],
            ['/* test 19 */', false],
            ['/* test 20 */', false],
            ['/* test 21 */', false],
            ['/* test 22 */', false],
            ['/* test 23 */', false],
            ['/* test 24 */', false],
            ['/* test 25 */', false],
            ['/* test 26 */', false],
            ['/* test 27 */', false],
            ['/* test 28 */', false],
            ['/* test 29 */', false],
            ['/* test 30 */', false],
            ['/* test 31 */', false],

            ['/* test A1 */', true],
            ['/* test A2 */', true],
            ['/* test A3 */', true],
            ['/* test A4 */', true],
            ['/* test A5 */', true],
            ['/* test A6 */', true],
            ['/* test A7 */', true],
            ['/* test A8 */', true],
            ['/* test A9 */', true],
            ['/* test A10 */', true],
        ];
    }
}
