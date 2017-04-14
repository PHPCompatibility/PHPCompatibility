<?php
/**
 * Classname determination test file
 *
 * @package PHPCompatibility
 */


/**
 * Classname determination from double colon token function tests
 *
 * @group utilityGetFQClassNameFromDoubleColonToken
 * @group utilityFunctions
 *
 * @uses    BaseClass_MethodTestFrame
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class BaseClass_GetFQClassNameFromDoubleColonTokenTest extends BaseClass_MethodTestFrame
{

    /**
     * The file name for the file containing the test cases within the
     * `sniff-examples/utility-functions/` directory.
     *
     * @var string
     */
    protected $filename = 'get_fqclassname_from_double_colon_token.php';

    /**
     * testGetFQClassNameFromDoubleColonToken
     *
     * @requires PHP 5.3
     *
     * @dataProvider dataGetFQClassNameFromDoubleColonToken
     *
     * @covers PHPCompatibility_Sniff::getFQClassNameFromDoubleColonToken
     *
     * @param string $commentString The comment which prefaces the T_DOUBLE_COLON token in the test file.
     * @param string $expected      The expected fully qualified class name.
     */
    public function testGetFQClassNameFromDoubleColonToken($commentString, $expected)
    {
        $stackPtr = $this->getTargetToken($commentString, T_DOUBLE_COLON);
        $result   = $this->helperClass->getFQClassNameFromDoubleColonToken($this->_phpcsFile, $stackPtr);
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
            array('/* Case 1 */', '\DateTime'),
            array('/* Case 2 */', '\DateTime'),
            array('/* Case 3 */', '\DateTime'),
            array('/* Case 4 */', '\DateTime'),
            array('/* Case 5 */', '\DateTime'),
            array('/* Case 6 */', '\AnotherNS\DateTime'),
            array('/* Case 7 */', '\FQNS\DateTime'),
            array('/* Case 8 */', '\DateTime'),
            array('/* Case 9 */', '\AnotherNS\DateTime'),
            array('/* Case 10 */', '\Testing\DateTime'),
            array('/* Case 11 */', '\Testing\DateTime'),
            array('/* Case 12 */', '\Testing\DateTime'),
            array('/* Case 13 */', '\Testing\MyClass'),
            array('/* Case 14 */', ''),
            array('/* Case 15 */', ''),
            array('/* Case 16 */', '\MyClass'),
            array('/* Case 17 */', ''),
            array('/* Case 18 */', ''),
            array('/* Case 19 */', ''),
        );
    }

}
