<?php
/**
 * Function parameter count test file
 *
 * @package PHPCompatibility
 */


/**
 * Function parameters count function tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 */
class GetFunctionParameterCountTest extends BaseAbstractClassMethodTest
{
	
	public $filename = 'sniff-examples/utility-functions/count_function_parameters.php';
	
    /**
     * testGetFunctionCallParameterCount
     *
     * @group utilityFunctions
     *
     * @dataProvider dataGetFunctionCallParameterCount
     *
     * @param int    $stackPtr Stack pointer for a T_CLASS token in the test file.
     * @param string $expected The expected fully qualified class name.
     */
    public function testGetFunctionCallParameterCount($stackPtr, $expected)
    {
        $result = $this->helperClass->getFunctionCallParameterCount($this->_phpcsFile, $stackPtr);
        $this->assertSame($expected, $result);
    }

    /**
     * dataGetFunctionCallParameterCount
     *
     * @see testGetFunctionCallParameterCount()
     *
     * @return array
     */
    public function dataGetFunctionCallParameterCount()
    {
        return array(
            array(5, 1),
            array(11, 2),
            array(22, 3),
            array(34, 4),
            array(49, 5),
            array(67, 6),
            array(88, 7),
            array(120, 1),
            array(135, 1),
            array(150, 1),
            array(164, 2),
            array(181, 1),
            array(194, 1),
            array(209, 1),
            array(228, 2),
            array(250, 6),
            array(281, 6),
            array(312, 6),
            array(351, 6),
            array(386, 6),
            array(420, 6),
            array(454, 6),
            array(499, 3),
            array(518, 1),
        );
    }

}
