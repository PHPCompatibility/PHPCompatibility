<?php
/**
 * Is class constant ? test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Util\Tests\Core;

use PHPCompatibility\Util\Tests\CoreMethodTestFrame;

/**
 * isClassConstant() function tests
 *
 * @group utilityIsClassConstant
 * @group utilityFunctions
 *
 * @uses    \PHPCompatibility\Util\Tests\CoreMethodTestFrame
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class IsClassConstantUnitTest extends CoreMethodTestFrame
{

    /**
     * testIsClassConstant
     *
     * @dataProvider dataIsClassConstant
     *
     * @covers \PHPCompatibility\Sniff::isClassConstant
     * @covers \PHPCompatibility\Sniff::validDirectScope
     *
     * @param string $commentString The comment which prefaces the target token in the test file.
     * @param string $expected      The expected boolean return value.
     *
     * @return void
     */
    public function testIsClassConstant($commentString, $expected)
    {
        $stackPtr = $this->getTargetToken($commentString, \T_CONST);
        $result   = $this->helperClass->isClassConstant($this->phpcsFile, $stackPtr);
        $this->assertSame($expected, $result);
    }

    /**
     * dataIsClassConstant
     *
     * @see testIsClassConstant()
     *
     * @return array
     */
    public function dataIsClassConstant()
    {
        return array(
            array('/* Case 1 */', false),
            array('/* Case 2 */', false),
            array('/* Case 3 */', true),
            array('/* Case 4 */', false),
            array('/* Case 5 */', true),
            array('/* Case 6 */', false),
            array('/* Case 7 */', true),
            array('/* Case 8 */', false),
            array('/* Case 9 */', false),
        );
    }
}
