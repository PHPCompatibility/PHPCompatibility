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
        return array(
            array('/* test 1 */', '\DateTime'),
            array('/* test 2 */', '\MyTesting\DateTime'),
            array('/* test 3 */', '\MyTesting\DateTime'),
            array('/* test 4 */', '\DateTime'),
            array('/* test 5 */', '\MyTesting\anotherNS\DateTime'),
            array('/* test 6 */', '\FQNS\DateTime'),
            array('/* test 7 */', '\AnotherTesting\DateTime'),
            array('/* test 8 */', '\AnotherTesting\DateTime'),
            array('/* test 9 */', '\DateTime'),
            array('/* test 10 */', '\AnotherTesting\anotherNS\DateTime'),
            array('/* test 11 */', '\FQNS\DateTime'),
            array('/* test 12 */', '\DateTime'),
            array('/* test 13 */', '\DateTime'),
            array('/* test 14 */', '\AnotherTesting\DateTime'),
            array('/* test 15 */', ''),
            array('/* test 16 */', ''),
        );
    }
}
