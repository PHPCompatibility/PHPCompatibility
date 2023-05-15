<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Util\Tests\Helpers\ResolveHelper;

use PHPCompatibility\Helpers\ResolveHelper;
use PHPCSUtils\TestUtils\UtilityMethodTestCase;

/**
 * Tests for the `getFQClassNameFromDoubleColonToken()` utility function.
 *
 * @group utilityGetFQClassNameFromDoubleColonToken
 * @group utilityFunctions
 *
 * @since 7.0.5
 */
final class GetFQClassNameFromDoubleColonTokenUnitTest extends UtilityMethodTestCase
{

    /**
     * Test retrieving a fully qualified class name based on a T_DOUBLE_COLON token.
     *
     * @dataProvider dataGetFQClassNameFromDoubleColonToken
     *
     * @covers \PHPCompatibility\Helpers\ResolveHelper::getFQClassNameFromDoubleColonToken
     *
     * @param string $commentString The comment which prefaces the T_DOUBLE_COLON token in the test file.
     * @param string $expected      The expected fully qualified class name.
     *
     * @return void
     */
    public function testGetFQClassNameFromDoubleColonToken($commentString, $expected)
    {
        $stackPtr = $this->getTargetToken($commentString, \T_DOUBLE_COLON);
        $result   = ResolveHelper::getFQClassNameFromDoubleColonToken(self::$phpcsFile, $stackPtr);
        $this->assertSame($expected, $result);
    }

    /**
     * Data provider.
     *
     * @see testGetFQClassNameFromDoubleColonToken()
     *
     * @return array
     */
    public function dataGetFQClassNameFromDoubleColonToken()
    {
        return [
            ['/* test 1 */', '\DateTime'],
            ['/* test 2 */', '\DateTime'],
            ['/* test 3 */', '\DateTime'],
            ['/* test 4 */', '\DateTime'],
            ['/* test 5 */', '\DateTime'],
            ['/* test 6 */', '\AnotherNS\DateTime'],
            ['/* test 7 */', '\FQNS\DateTime'],
            ['/* test 8 */', '\DateTime'],
            ['/* test 9 */', '\AnotherNS\DateTime'],
            ['/* test 10 */', '\Testing\DateTime'],
            ['/* test 11 */', '\Testing\DateTime'],
            ['/* test 12 */', '\Testing\DateTime'],
            ['/* test 13 */', '\Testing\MyClass'],
            ['/* test 14 */', ''],
            ['/* test 15 */', ''],
            ['/* test 16 */', '\MyClass'],
            ['/* test 17 */', ''],
            ['/* test 18 */', ''],
            ['/* test 19 */', ''],
        ];
    }
}
