<?php
/**
 * Function parameters test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Util\Tests\Core;

use PHPCompatibility\Util\Tests\CoreMethodTestFrame;

/**
 * Function parameters function tests
 *
 * @group utilityDoesFunctionCallHaveParameters
 * @group utilityFunctions
 *
 * @uses    \PHPCompatibility\Util\Tests\CoreMethodTestFrame
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class DoesFunctionCallHaveParametersUnitTest extends CoreMethodTestFrame
{

    /**
     * testDoesFunctionCallHaveParameters
     *
     * @dataProvider dataDoesFunctionCallHaveParameters
     *
     * @covers \PHPCompatibility\Sniff::doesFunctionCallHaveParameters
     *
     * @param string $commentString The comment which prefaces the target token in the test file.
     * @param bool   $expected      Whether or not the function/array has parameters/values.
     *
     * @return void
     */
    public function testDoesFunctionCallHaveParameters($commentString, $expected)
    {
        $stackPtr = $this->getTargetToken($commentString, array(\T_STRING, \T_ARRAY, \T_OPEN_SHORT_ARRAY));
        $result   = $this->helperClass->doesFunctionCallHaveParameters($this->phpcsFile, $stackPtr);
        $this->assertSame($expected, $result);
    }

    /**
     * dataDoesFunctionCallHaveParameters
     *
     * @see testDoesFunctionCallHaveParameters()
     *
     * @return array
     */
    public function dataDoesFunctionCallHaveParameters()
    {
        return array(
            // Function calls.
            array('/* Case S1 */', false),
            array('/* Case S2 */', false),
            array('/* Case S3 */', false),
            array('/* Case S4 */', false),
            array('/* Case S5 */', true),
            array('/* Case S6 */', true),
            array('/* Case S7 */', true),

            // Arrays.
            array('/* Case A1 */', false),
            array('/* Case A2 */', false),
            array('/* Case A3 */', false),
            array('/* Case A4 */', false),
            array('/* Case A5 */', false),
            array('/* Case A6 */', false),
            array('/* Case A7 */', false),
            array('/* Case A8 */', false),
            array('/* Case A9 */', true),
            array('/* Case A10 */', true),
            array('/* Case A11 */', true),
            array('/* Case A12 */', true),
            array('/* Case A13 */', true),
            array('/* Case A14 */', true),
        );
    }
}
