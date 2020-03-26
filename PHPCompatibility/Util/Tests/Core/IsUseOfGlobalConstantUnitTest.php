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
        return array(
            array('/* test 1 */', false),
            array('/* test 2 */', false),
            array('/* test 3 */', false),
            array('/* test 4 */', false),
            array('/* test 5 */', false),
            array('/* test 6 */', false),
            array('/* test 7 */', false),
            array('/* test 8 */', false),
            array('/* test 9 */', false),
            array('/* test 10 */', false),
            array('/* test 11 */', false),
            array('/* test 12 */', false),
            array('/* test 13 */', false),
            array('/* test 14 */', false),
            array('/* test 15 */', false),
            array('/* test 16 */', false),
            array('/* test 17 */', false),
            array('/* test 18 */', false),
            array('/* test 19 */', false),
            array('/* test 20 */', false),
            array('/* test 21 */', false),
            array('/* test 22 */', false),
            array('/* test 23 */', false),
            array('/* test 24 */', false),
            array('/* test 25 */', false),
            array('/* test 26 */', false),
            array('/* test 27 */', false),
            array('/* test 28 */', false),
            array('/* test 29 */', false),
            array('/* test 30 */', false),
            array('/* test 31 */', false),

            array('/* test A1 */', true),
            array('/* test A2 */', true),
            array('/* test A3 */', true),
            array('/* test A4 */', true),
            array('/* test A5 */', true),
            array('/* test A6 */', true),
            array('/* test A7 */', true),
            array('/* test A8 */', true),
            array('/* test A9 */', true),
            array('/* test A10 */', true),
        );
    }
}
