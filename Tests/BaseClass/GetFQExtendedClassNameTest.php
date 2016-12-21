<?php
/**
 * Extended class name determination test file
 *
 * @package PHPCompatibility
 */


/**
 * Extended class name determination function tests
 *
 * @group utilityGetFQExtendedClassName
 * @group utilityFunctions
 *
 * @uses    BaseClass_MethodTestFrame
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class BaseClass_GetFQExtendedClassNameTest extends BaseClass_MethodTestFrame
{

    /**
     * The file name for the file containing the test cases within the
     * `sniff-examples/utility-functions/` directory.
     *
     * @var string
     */
    protected $filename = 'get_fqextended_classname.php';

    /**
     * testGetFQExtendedClassName
     *
     * @requires PHP 5.3
     *
     * @dataProvider dataGetFQExtendedClassName
     *
     * @covers PHPCompatibility_Sniff::getFQExtendedClassName
     *
     * @param int    $stackPtr Stack pointer for a T_CLASS token in the test file.
     * @param string $expected The expected fully qualified class name.
     */
    public function testGetFQExtendedClassName($stackPtr, $expected) {
        $result = $this->helperClass->getFQExtendedClassName($this->_phpcsFile, $stackPtr);
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
            array(7, '\MyTesting\DateTime'),
            array(18, '\DateTime'),
            array(30, '\MyTesting\anotherNS\DateTime'),
            array(43, '\FQNS\DateTime'),
            array(65, '\AnotherTesting\DateTime'),
            array(77, '\DateTime'),
            array(90, '\AnotherTesting\anotherNS\DateTime'),
            array(104, '\FQNS\DateTime'),
            array(121, '\DateTime'),
            array(132, '\DateTime'),
            array(155, '\Yet\More\Testing\DateTime'),
            array(166, '\Yet\More\Testing\anotherNS\DateTime'),
            array(179, '\FQNS\DateTime'),
        );
    }

}
