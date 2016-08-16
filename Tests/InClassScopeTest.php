<?php
/**
 * In class scope test file
 *
 * @package PHPCompatibility
 */


/**
 * In class scope function tests
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class InClassScopeTest extends BaseAbstractClassMethodTest
{

    public $filename = 'sniff-examples/utility-functions/in_class_scope.php';

    /**
     * testInClassScope
     *
     * @group utilityFunctions
     *
     * @dataProvider dataInClassScope
     *
     * @param int    $stackPtr Stack pointer for an arbitrary token in the test file.
     * @param string $expected The expected boolean return value.
     */
    public function testInClassScope($stackPtr, $expected)
    {
        $result = $this->helperClass->inClassScope($this->_phpcsFile, $stackPtr);
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
            array(2, false), // $var
            array(9, false), // function
            array(28, true), // $property
            array(32, true), // function
            array(49, false), // function
            array(67, true), // function
        );
    }

}
