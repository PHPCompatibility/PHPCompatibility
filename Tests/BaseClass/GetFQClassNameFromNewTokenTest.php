<?php
/**
 * Classname determination test file
 *
 * @package PHPCompatibility
 */


/**
 * Classname determination function tests
 *
 * @uses    BaseClass_MethodTestFrame
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class BaseClass_GetFQClassNameFromNewTokenTest extends BaseClass_MethodTestFrame
{

    public $filename = '../sniff-examples/utility-functions/get_fqclassname_from_new_token.php';

    /**
     * testGetFQClassNameFromNewToken
     *
     * @group utilityFunctions
     *
     * @requires PHP 5.3
     *
     * @dataProvider dataGetFQClassNameFromNewToken
     *
     * @covers PHPCompatibility_Sniff::getFQClassNameFromNewToken
     *
     * @param int    $stackPtr Stack pointer for a T_NEW token in the test file.
     * @param string $expected The expected fully qualified class name.
     */
    public function testGetFQClassNameFromNewToken($stackPtr, $expected) {
        $result = $this->helperClass->getFQClassNameFromNewToken($this->_phpcsFile, $stackPtr);
        $this->assertSame($expected, $result);
    }

    /**
     * dataGetFQClassNameFromNewToken
     *
     * @see testGetFQClassNameFromNewToken()
     *
     * @return array
     */
    public function dataGetFQClassNameFromNewToken()
    {
        return array(
            array(7, '\MyTesting\DateTime'),
            array(16, '\MyTesting\DateTime'),
            array(21, '\DateTime'),
            array(29, '\MyTesting\anotherNS\DateTime'),
            array(38, '\FQNS\DateTime'),
            array(56, '\AnotherTesting\DateTime'),
            array(66, '\AnotherTesting\DateTime'),
            array(72, '\DateTime'),
            array(81, '\AnotherTesting\anotherNS\DateTime'),
            array(91, '\FQNS\DateTime'),
            array(104, '\DateTime'),
            array(109, '\DateTime'),
            array(115, '\AnotherTesting\DateTime'),
            array(133, ''),
        );
    }

}
