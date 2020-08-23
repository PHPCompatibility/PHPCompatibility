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
 * Tests for the `getFQClassNameFromNewToken()` utility function.
 *
 * @group utilityGetFQClassNameFromNewToken
 * @group utilityFunctions
 *
 * @since 7.0.3
 */
class GetFQClassNameFromNewTokenUnitTest extends CoreMethodTestFrame
{

    /**
     * testGetFQClassNameFromNewToken
     *
     * @dataProvider dataGetFQClassNameFromNewToken
     *
     * @covers \PHPCompatibility\Sniff::getFQClassNameFromNewToken
     *
     * @param string $commentString The comment which prefaces the T_NEW token in the test file.
     * @param string $expected      The expected fully qualified class name.
     *
     * @return void
     */
    public function testGetFQClassNameFromNewToken($commentString, $expected)
    {
        $stackPtr = $this->getTargetToken($commentString, \T_NEW);
        $result   = self::$helperClass->getFQClassNameFromNewToken(self::$phpcsFile, $stackPtr);
        $this->assertSame($expected, $result);
    }

    /**
     * dataGetFQClassNameFromNewToken
     *
     * @see testGetFQClassNameFromNewToken()
     *
     * @return array
     */
    public function dataGetFQClassNameFromNewToken()
    {
        return [
            ['/* test 1 */', '\DateTime'],
            ['/* test 2 */', '\MyTesting\DateTime'],
            ['/* test 3 */', '\MyTesting\DateTime'],
            ['/* test 4 */', '\DateTime'],
            ['/* test 5 */', '\MyTesting\anotherNS\DateTime'],
            ['/* test 6 */', '\FQNS\DateTime'],
            ['/* test 7 */', '\AnotherTesting\DateTime'],
            ['/* test 8 */', '\AnotherTesting\DateTime'],
            ['/* test 9 */', '\DateTime'],
            ['/* test 10 */', '\AnotherTesting\anotherNS\DateTime'],
            ['/* test 11 */', '\FQNS\DateTime'],
            ['/* test 12 */', '\DateTime'],
            ['/* test 13 */', '\DateTime'],
            ['/* test 14 */', '\AnotherTesting\DateTime'],
            ['/* test 15 */', ''],
            ['/* test 16 */', ''],
        ];
    }
}
