<?php
/**
 * Extended class name determination test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Util\Tests\Core;

use PHPCompatibility\Util\Tests\CoreMethodTestFrame;

/**
 * Extended class name determination function tests
 *
 * @group utilityGetFQExtendedClassName
 * @group utilityFunctions
 *
 * @uses    \PHPCompatibility\Util\Tests\CoreMethodTestFrame
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
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
        $result   = $this->helperClass->getFQExtendedClassName($this->phpcsFile, $stackPtr);
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
            array('/* Case 1 */', ''),
            array('/* Case 2 */', '\DateTime'),
            array('/* Case 3 */', '\MyTesting\DateTime'),
            array('/* Case 4 */', '\DateTime'),
            array('/* Case 5 */', '\MyTesting\anotherNS\DateTime'),
            array('/* Case 6 */', '\FQNS\DateTime'),
            array('/* Case 7 */', '\AnotherTesting\DateTime'),
            array('/* Case 8 */', '\DateTime'),
            array('/* Case 9 */', '\AnotherTesting\anotherNS\DateTime'),
            array('/* Case 10 */', '\FQNS\DateTime'),
            array('/* Case 11 */', '\DateTime'),
            array('/* Case 12 */', '\DateTime'),
            array('/* Case 13 */', '\Yet\More\Testing\DateTime'),
            array('/* Case 14 */', '\Yet\More\Testing\anotherNS\DateTime'),
            array('/* Case 15 */', '\FQNS\DateTime'),
            array('/* Case 16 */', '\SomeInterface'),
            array('/* Case 17 */', '\Yet\More\Testing\SomeInterface'),
        );
    }
}
