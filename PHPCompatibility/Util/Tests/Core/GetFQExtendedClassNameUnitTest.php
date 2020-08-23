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
        $stackPtr = $this->getTargetToken($commentString, [\T_CLASS, \T_INTERFACE]);
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
        return [
            ['/* test 1 */', ''],
            ['/* test 2 */', '\DateTime'],
            ['/* test 3 */', '\MyTesting\DateTime'],
            ['/* test 4 */', '\DateTime'],
            ['/* test 5 */', '\MyTesting\anotherNS\DateTime'],
            ['/* test 6 */', '\FQNS\DateTime'],
            ['/* test 7 */', '\AnotherTesting\DateTime'],
            ['/* test 8 */', '\DateTime'],
            ['/* test 9 */', '\AnotherTesting\anotherNS\DateTime'],
            ['/* test 10 */', '\FQNS\DateTime'],
            ['/* test 11 */', '\DateTime'],
            ['/* test 12 */', '\DateTime'],
            ['/* test 13 */', '\Yet\More\Testing\DateTime'],
            ['/* test 14 */', '\Yet\More\Testing\anotherNS\DateTime'],
            ['/* test 15 */', '\FQNS\DateTime'],
            ['/* test 16 */', '\SomeInterface'],
            ['/* test 17 */', '\Yet\More\Testing\SomeInterface'],
        ];
    }
}
