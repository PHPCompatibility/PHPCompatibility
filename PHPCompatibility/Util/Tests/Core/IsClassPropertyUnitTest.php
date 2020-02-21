<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2019 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Util\Tests\Core;

use PHPCompatibility\Util\Tests\CoreMethodTestFrame;

/**
 * Tests for the `isClassProperty()` utility function.
 *
 * @group utilityIsClassProperty
 * @group utilityFunctions
 *
 * @since 7.1.4
 */
class IsClassPropertyUnitTest extends CoreMethodTestFrame
{

    /**
     * testIsClassProperty
     *
     * @dataProvider dataIsClassProperty
     *
     * @covers \PHPCompatibility\Sniff::isClassProperty
     * @covers \PHPCompatibility\Sniff::validDirectScope
     *
     * @param string $commentString The comment which prefaces the target token in the test file.
     * @param string $expected      The expected boolean return value.
     *
     * @return void
     */
    public function testIsClassProperty($commentString, $expected)
    {
        $stackPtr = $this->getTargetToken($commentString, \T_VARIABLE);
        $result   = $this->helperClass->isClassProperty($this->phpcsFile, $stackPtr);
        $this->assertSame($expected, $result);
    }

    /**
     * dataIsClassProperty
     *
     * @see testIsClassProperty()
     *
     * @return array
     */
    public function dataIsClassProperty()
    {
        return array(
            array('/* Case 1 */', false),
            array('/* Case 2 */', false),
            array('/* Case 3 */', false),
            array('/* Case 4 */', true),
            array('/* Case 5 */', true),
            array('/* Case 6 */', true),
            array('/* Case 7 */', true),
            array('/* Case 8 */', false),
            array('/* Case 9 */', false),
            array('/* Case 10 */', true),
            array('/* Case 11 */', true),
            array('/* Case 12 */', true),
            array('/* Case 13 */', true),
            array('/* Case 14 */', false),
            array('/* Case 15 */', false),
            array('/* Case 16 */', false),
            array('/* Case 17 */', false),
            array('/* Case 18 */', false),
            array('/* Case 19 */', false),
            array('/* Case 20 */', false),
            array('/* Case 21 */', true),
            array('/* Case 22 */', true),
            array('/* Case 23 */', true),
            array('/* Case 24 */', true),
            array('/* Case 25 */', false),
            array('/* Case 26 */', false),
            array('/* Case 27 */', true),
            array('/* Case 28 */', true),
            array('/* Case 29 */', true),
            array('/* Case 30 */', true),
            array('/* Case 31 */', true),
            array('/* Case 32 */', true),
            array('/* Case 33 */', true),
            array('/* Case 34 */', false),
            array('/* Case 35 */', true),
            array('/* Case 36 */', false),
        );
    }
}
