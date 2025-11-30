<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Util\Tests\Helpers\MiscHelper;

use PHP_CodeSniffer\Util\Tokens;
use PHPCompatibility\Helpers\MiscHelper;
use PHPCSUtils\TestUtils\UtilityMethodTestCase;

/**
 * Tests for the `isUseOfGlobalConstant()` utility function.
 *
 * @group utilityIsUseOfGlobalConstant
 * @group utilityFunctions
 *
 * @since 8.1.0
 *
 * @covers \PHPCompatibility\Helpers\MiscHelper::isUseOfGlobalConstant
 */
final class IsUseOfGlobalConstantUnitTest extends UtilityMethodTestCase
{

    /**
     * Verify handling of invalid token pointer.
     *
     * @return void
     */
    public function testIsUseOfGlobalConstantReturnsFalseForNonExistentToken()
    {
        $result = MiscHelper::isUseOfGlobalConstant(self::$phpcsFile, 10000);
        $this->assertFalse($result);
    }

    /**
     * Verify handling of invalid passed token type.
     *
     * @return void
     */
    public function testIsUseOfGlobalConstantReturnsFalseForUnsupportedToken()
    {
        $stackPtr = $this->getTargetToken('/* test 20 */', \T_ECHO);
        $result   = MiscHelper::isUseOfGlobalConstant(self::$phpcsFile, $stackPtr);
        $this->assertFalse($result);
    }

    /**
     * Test whether detection of whether a T_STRING is a global constant works correctly.
     *
     * @dataProvider dataIsUseOfGlobalConstant
     *
     * @param string $commentString The comment which prefaces the target token in the test file.
     * @param string $expected      The expected boolean return value.
     * @param string $targetContent Optional. The expected contents of the target token.
     *
     * @return void
     */
    public function testIsUseOfGlobalConstant($commentString, $expected, $targetContent = 'PHP_VERSION_ID')
    {
        $stackPtr = $this->getTargetToken($commentString, Tokens::NAME_TOKENS, $targetContent);
        $result   = MiscHelper::isUseOfGlobalConstant(self::$phpcsFile, $stackPtr);
        $this->assertSame($expected, $result);
    }

    /**
     * Data provider.
     *
     * @see testIsUseOfGlobalConstant()
     *
     * @return array
     */
    public static function dataIsUseOfGlobalConstant()
    {
        return [
            ['/* test 1 */', false],
            ['/* test 2 */', false, 'MY\OTHER\PHP_VERSION_ID\NS'],
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
            ['/* test 16 */', false, 'My\UsedAsNamespace\PHP_VERSION_ID\something'],
            ['/* test 17 */', false, 'My\UsedAsNamespace\PHP_VERSION_ID\something'],
            ['/* test 18 */', false],
            ['/* test 19 */', false],
            ['/* test 20 */', false, '\mynamespace\PHP_VERSION_ID'],
            ['/* test 21 */', false],
            ['/* test 22 */', false],
            ['/* test 23 */', false, 'SomeNamespace\PHP_VERSION_ID'],
            ['/* test 24 */', false],
            ['/* test 25 */', false],
            ['/* test 26 */', false],
            ['/* test 27 */', false],
            ['/* test 28 */', false],
            ['/* test 29 */', false],
            ['/* test 30 */', false],
            ['/* test 31 */', false],
            ['/* test 32 */', false],
            ['/* test 33 */', false],
            ['/* test 34 */', false],
            ['/* test 35 */', false, 'namespace\PHP_VERSION_ID'],
            ['/* test 36 */', false],
            ['/* test 37 */', false],
            ['/* test 38 */', false],
            ['/* test 39 */', false],
            ['/* test 40 */', false],
            ['/* test 41 */', false],
            ['/* test 42 */', false],
            ['/* test 43 */', false],
            ['/* test 44 */', false],
            ['/* test 45 */', false],
            ['/* test 46 */', false],
            ['/* test 47 */', false],
            ['/* test 48 */', false],
            ['/* test 49 */', false],
            ['/* test 50 */', false],
            ['/* test 51 */', false],
            ['/* test 52 */', false],
            ['/* test 53 */', false],
            ['/* test 54 */', false],
            ['/* test 55 */', false],
            ['/* test 56 */', false],
            ['/* test 57 */', false],
            ['/* test 58 */', false],
            ['/* test 59 */', false],
            ['/* test 60 */', false],
            ['/* test 61 */', false],
            ['/* test 62 */', false],
            ['/* test 63 */', false],
            ['/* test 64 */', false],
            ['/* test 65 */', false],
            ['/* test 66 */', false],
            ['/* test 67 */', false],
            ['/* test 68 */', false],
            ['/* test 69 */', false],
            ['/* test 70 */', false],
            ['/* test 71 */', false],
            ['/* test 72 */', false],
            ['/* test 73 */', false],
            ['/* test 74 */', false],
            ['/* test 75 */', false],
            ['/* test 76 */', false],
            ['/* test 77 */', false],
            ['/* test 78 */', false],
            ['/* test 79 */', false],
            ['/* test 80 */', false],
            ['/* test 81 */', false],
            ['/* test 82 */', false],
            ['/* test 83 */', false],
            ['/* test 84 */', false],
            ['/* test 85 */', false],
            ['/* test 86 */', false],
            ['/* test 87 */', false],
            ['/* test 88 */', false],
            ['/* test 89 */', false],
            ['/* test 90 */', false],
            ['/* test 91 */', false],
            ['/* test 92 */', false],
            ['/* test 93 */', false],
            ['/* test 94 */', false],
            ['/* test 95 */', false],
            ['/* test 96 */', false, '\PHP_VERSION_ID'],
            ['/* test 97 */', false, '\PHP_VERSION_ID'],
            ['/* test 98 */', false, '\PHP_VERSION_ID'],
            ['/* test 99 */', false, '\PHP_VERSION_ID'],
            ['/* test 100 */', false, '\PHP_VERSION_ID'],
            ['/* test 101 */', false, '\PHP_VERSION_ID'],
            ['/* test 102 */', false, '\PHP_VERSION_ID'],
            ['/* test 103 */', false, '\PHP_VERSION_ID'],
            ['/* test 104 */', false, '\PHP_VERSION_ID'],
            ['/* test 105 */', false, '\PHP_VERSION_ID'],
            ['/* test 106 */', false, '\PHP_VERSION_ID'],
            ['/* test 107 */', false, '\PHP_VERSION_ID'],
            ['/* test 108 */', false, '\PHP_VERSION_ID'],
            ['/* test 109 */', false, '\PHP_VERSION_ID'],
            ['/* test 110 */', false, '\PHP_VERSION_ID'],
            ['/* test 111 */', false, '\PHP_VERSION_ID'],
            ['/* test 112 */', false, '\PHP_VERSION_ID'],
            ['/* test 113 */', false, '\PHP_VERSION_ID'],
            ['/* test 114 */', false, '\PHP_VERSION_ID'],
            ['/* test 115 */', false, '\PHP_VERSION_ID'],
            ['/* test 116 */', false, '\PHP_VERSION_ID'],
            ['/* test 117 */', false, '\PHP_VERSION_ID'],
            ['/* test 118 */', false, '\PHP_VERSION_ID'],
            ['/* test 119 */', false, '\PHP_VERSION_ID'],
            ['/* test 120 */', false, '\PHP_VERSION_ID'],
            ['/* test 121 */', false, '\PHP_VERSION_ID'],
            ['/* test 122 */', false, '\PHP_VERSION_ID'],
            ['/* test 123 */', false, '\PHP_VERSION_ID'],
            ['/* test 124 */', false, '\PHP_VERSION_ID'],
            ['/* test 125 */', false, '\PHP_VERSION_ID'],
            ['/* test 126 */', false, '\PHP_VERSION_ID'],
            ['/* test 127 */', false, '\PHP_VERSION_ID'],
            ['/* test 128 */', false, '\PHP_VERSION_ID'],
            ['/* test 129 */', false, '\PHP_VERSION_ID'],
            ['/* test 130 */', false, '\PHP_VERSION_ID'],
            ['/* test 131 */', false, '\PHP_VERSION_ID'],
            ['/* test 132 */', false, '\PHP_VERSION_ID'],
            ['/* test 133 */', false, '\PHP_VERSION_ID'],
            ['/* test 134 */', false, '\PHP_VERSION_ID'],
            ['/* test 135 */', false, '\PHP_VERSION_ID'],
            ['/* test 136 */', false, '\PHP_VERSION_ID'],
            ['/* test 137 */', false, '\PHP_VERSION_ID'],
            ['/* test 138 */', false, '\PHP_VERSION_ID'],
            ['/* test 139 */', false, '\PHP_VERSION_ID'],
            ['/* test 140 */', false, '\PHP_VERSION_ID'],
            ['/* test 141 */', false, '\PHP_VERSION_ID'],

            ['/* test A1 */', true],
            ['/* test A2 */', true, '\PHP_VERSION_ID'],
            ['/* test A3 */', true],
            ['/* test A4 */', true],
            ['/* test A5 */', true],
            ['/* test A6 */', true],
            ['/* test A7 */', true],
            ['/* test A8 */', true],
            ['/* test A9 */', true],
            ['/* test A10 */', true],
            ['/* test A11 */', true],
            ['/* test A12 */', true],
            ['/* test A13 */', true],
            ['/* test A14 */', true],
            ['/* test A15 */', true],
            ['/* test A16 */', true],
            ['/* test A17 */', true],
            ['/* test A18 */', true, '\PHP_VERSION_ID'],
            ['/* test A19 */', true],
            ['/* test A20 */', true, '\PHP_VERSION_ID'],
            ['/* test A21 */', true],
            ['/* test A22 */', true],
            ['/* test A23 */', true, '\PHP_VERSION_ID'],
            ['/* test A24 */', true, '\PHP_VERSION_ID'],
        ];
    }
}
