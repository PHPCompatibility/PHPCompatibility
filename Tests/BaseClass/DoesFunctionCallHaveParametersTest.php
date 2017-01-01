<?php
/**
 * Function parameters test file
 *
 * @package PHPCompatibility
 */


/**
 * Function parameters function tests
 *
 * @group utilityDoesFunctionCallHaveParameters
 * @group utilityFunctions
 *
 * @uses    BaseClass_MethodTestFrame
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class BaseClass_DoesFunctionCallHaveParametersTest extends BaseClass_MethodTestFrame
{

    /**
     * The file name for the file containing the test cases within the
     * `sniff-examples/utility-functions/` directory.
     *
     * @var string
     */
    protected $filename = 'does_function_call_have_parameters.php';

    /**
     * testDoesFunctionCallHaveParameters
     *
     * @dataProvider dataDoesFunctionCallHaveParameters
     *
     * @covers PHPCompatibility_Sniff::doesFunctionCallHaveParameters
     *
     * @param int    $stackPtr Stack pointer for a function call T_STRING token
     *                         or a T_ARRAY token in the test file.
     * @param string $expected The expected fully qualified class name.
     */
    public function testDoesFunctionCallHaveParameters($stackPtr, $expected) {
        $result = $this->helperClass->doesFunctionCallHaveParameters($this->_phpcsFile, $stackPtr);
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
            array(12, false),
            array(17, false),
            array(23, false),
            array(31, false),
            array(42, true),
            array(50, true),
            array(60, true),

            // Arrays.
            array(80, false),
            array(89, false),
            array(99, false),
            array(111, false),
            array(121, false),
            array(129, false),
            array(138, false),
            array(149, false),
            array(163, true),
            array(175, true),
            array(189, true),
            array(199, true),
            array(210, true),
            array(223, true),
        );
    }

}
