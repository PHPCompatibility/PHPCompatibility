<?php
/**
 * Classname determination test file
 *
 * @package PHPCompatibility
 */


/**
 * Classname determination from double colon token function tests
 *
 * @uses    BaseClass_MethodTestFrame
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class BaseClass_GetFQClassNameFromDoubleColonTokenTest extends BaseClass_MethodTestFrame
{

    public $filename = '../sniff-examples/utility-functions/get_fqclassname_from_double_colon_token.php';

    /**
     * testGetFQClassNameFromDoubleColonToken
     *
     * @group utilityFunctions
     *
     * @requires PHP 5.3
     *
     * @dataProvider dataGetFQClassNameFromDoubleColonToken
     *
     * @covers PHPCompatibility_Sniff::getFQClassNameFromDoubleColonToken
     *
     * @param int    $stackPtr Stack pointer for a T_DOUBLE_COLON token in the test file.
     * @param string $expected The expected fully qualified class name.
     */
    public function testGetFQClassNameFromDoubleColonToken($stackPtr, $expected) {
        $result = $this->helperClass->getFQClassNameFromDoubleColonToken($this->_phpcsFile, $stackPtr);
        $this->assertSame($expected, $result);
    }

    /**
     * dataGetFQClassNameFromDoubleColonToken
     *
     * @see testGetFQClassNameFromDoubleColonToken()
     *
     * @return array
     */
    public function dataGetFQClassNameFromDoubleColonToken()
    {
        return array(
            array(3, '\DateTime'),
            array(8, '\DateTime'),
            array(13, '\DateTime'),
            array(21, '\DateTime'),
            array(30, '\DateTime'),
            array(39, '\AnotherNS\DateTime'),
            array(49, '\FQNS\DateTime'),
            array(61, '\DateTime'),
            array(76, '\AnotherNS\DateTime'),
            array(90, '\Testing\DateTime'),
            array(96, '\Testing\DateTime'),
            array(102, '\Testing\DateTime'),
            array(127, '\Testing\MyClass'),
            array(135, ''),
            array(141, ''),
            array(173, '\MyClass'),
            array(181, ''),
            array(187, ''),
            array(247, ''),
        );
    }

}
