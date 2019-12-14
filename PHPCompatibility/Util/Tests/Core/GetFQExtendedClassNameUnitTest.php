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
 * Tests for the `getFQExtendedClassName()` utility function.
 *
 * @group utilityGetFQExtendedClassName
 * @group utilityFunctions
 *
 * @since 7.0.3
 */
class GetFQExtendedClassNameUnitTest extends CoreMethodTestFrame
{

    /**
     * testGetFQExtendedClassName
     *
     * @dataProvider dataGetFQExtendedClassName
     *
     * @covers \PHPCompatibility\Sniff::getFQExtendedClassName
     *
     * @param string $commentString The comment which prefaces the T_CLASS token in the test file.
     * @param string $expected      The expected fully qualified class name.
     *
     * @return void
     */
    public function testGetFQExtendedClassName($commentString, $expected)
    {
        $stackPtr = $this->getTargetToken($commentString, array(\T_CLASS, \T_INTERFACE));
        $result   = self::$helperClass->getFQExtendedClassName(self::$phpcsFile, $stackPtr);
        $this->assertSame($expected, $result);
    }

    /**
     * dataGetFQExtendedClassName
     *
     * @see testGetFQExtendedClassName()
     *
     * @return array
     */
    public function dataGetFQExtendedClassName()
    {
        return array(
            array('/* test 1 */', ''),
            array('/* test 2 */', '\DateTime'),
            array('/* test 3 */', '\MyTesting\DateTime'),
            array('/* test 4 */', '\DateTime'),
            array('/* test 5 */', '\MyTesting\anotherNS\DateTime'),
            array('/* test 6 */', '\FQNS\DateTime'),
            array('/* test 7 */', '\AnotherTesting\DateTime'),
            array('/* test 8 */', '\DateTime'),
            array('/* test 9 */', '\AnotherTesting\anotherNS\DateTime'),
            array('/* test 10 */', '\FQNS\DateTime'),
            array('/* test 11 */', '\DateTime'),
            array('/* test 12 */', '\DateTime'),
            array('/* test 13 */', '\Yet\More\Testing\DateTime'),
            array('/* test 14 */', '\Yet\More\Testing\anotherNS\DateTime'),
            array('/* test 15 */', '\FQNS\DateTime'),
            array('/* test 16 */', '\SomeInterface'),
            array('/* test 17 */', '\Yet\More\Testing\SomeInterface'),
        );
    }
}
