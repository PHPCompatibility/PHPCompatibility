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
        $result   = $this->helperClass->inClassScope($this->phpcsFile, $stackPtr, $strict);
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
        return array(
            array('/* Case C1 */', \T_VARIABLE, true), // $property
            array('/* Case C2 */', \T_FUNCTION, true), // Function in class.
            array('/* Case C3 */', \T_FUNCTION, false), // Global function.
            array('/* Case C4 */', \T_FUNCTION, true), // Function in namespaced class.
            array('/* Case C5 */', \T_FUNCTION, true), // Function in anon class.
            array('/* Case I1 */', \T_FUNCTION, false), // Function in interface / strict.
            array('/* Case I1 */', \T_FUNCTION, true, false), // Function in interface.
            array('/* Case T1 */', \T_VARIABLE, false), // Property in trait / strict.
            array('/* Case T1 */', \T_VARIABLE, true, false), // Property in trait.
            array('/* Case T2 */', \T_FUNCTION, false), // Function in trait / strict.
            array('/* Case T2 */', \T_FUNCTION, true, false), // Function in trait.
        );
    }
}
