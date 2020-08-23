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
 * Tests for the `tokenHasScope()` and `inClassScope()` utility functions.
 *
 * @group utilityTokenScope
 * @group utilityFunctions
 *
 * @since 7.0.5
 */
class TokenScopeUnitTest extends CoreMethodTestFrame
{

    /**
     * testInClassScope
     *
     * @dataProvider dataInClassScope
     *
     * @covers \PHPCompatibility\Sniff::inClassScope
     *
     * @param string $commentString The comment which prefaces the target token in the test file.
     * @param int    $targetType    The token type for the target token.
     * @param string $expected      The expected boolean return value.
     * @param bool   $strict        The value for the $strict parameter to pass to the function call.
     *
     * @return void
     */
    public function testInClassScope($commentString, $targetType, $expected, $strict = true)
    {
        $stackPtr = $this->getTargetToken($commentString, $targetType);
        $result   = self::$helperClass->inClassScope(self::$phpcsFile, $stackPtr, $strict);
        $this->assertSame($expected, $result);
    }

    /**
     * dataInClassScope
     *
     * @see testInClassScope()
     *
     * @return array
     */
    public function dataInClassScope()
    {
        return [
            ['/* test C1 */', \T_VARIABLE, true], // $property
            ['/* test C2 */', \T_FUNCTION, true], // Function in class.
            ['/* test C3 */', \T_FUNCTION, false], // Global function.
            ['/* test C4 */', \T_FUNCTION, true], // Function in namespaced class.
            ['/* test C5 */', \T_FUNCTION, true], // Function in anon class.
            ['/* test I1 */', \T_FUNCTION, false], // Function in interface / strict.
            ['/* test I1 */', \T_FUNCTION, true, false], // Function in interface.
            ['/* test T1 */', \T_VARIABLE, false], // Property in trait / strict.
            ['/* test T1 */', \T_VARIABLE, true, false], // Property in trait.
            ['/* test T2 */', \T_FUNCTION, false], // Function in trait / strict.
            ['/* test T2 */', \T_FUNCTION, true, false], // Function in trait.
        ];
    }
}
