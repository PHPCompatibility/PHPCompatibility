<?php
/**
 * Function parameter count test file
 *
 * @package PHPCompatibility
 */


/**
 * Function parameters count function tests
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class GetFunctionParametersTest extends BaseAbstractClassMethodTest
{

    public $filename = 'sniff-examples/utility-functions/get_function_parameters.php';

    /**
     * testGetFunctionCallParameters
     *
     * @group utilityFunctions
     *
     * @dataProvider dataGetFunctionCallParameters
     *
     * @param int    $stackPtr Stack pointer for a T_CLASS token in the test file.
     * @param string $expected The expected fully qualified class name.
     */
    public function testGetFunctionCallParameters($stackPtr, $expected)
    {
        $result = $this->helperClass->getFunctionCallParameters($this->_phpcsFile, $stackPtr);
        $this->assertSame($expected, $result);
    }

    /**
     * dataGetFunctionCallParameters
     *
     * @see testGetFunctionCallParameters()
     *
     * @return array
     */
    public function dataGetFunctionCallParameters()
    {
        return array(
            array(88, array(
                       0 => array(
                             'start' => 90,
                             'end'   => 91,
                             'raw'   => '1',
                            ),
                       1 => array(
                             'start' => 93,
                             'end'   => 94,
                             'raw'   => '2',
                            ),
                       2 => array(
                             'start' => 96,
                             'end'   => 97,
                             'raw'   => '3',
                            ),
                       3 => array(
                             'start' => 99,
                             'end'   => 100,
                             'raw'   => '4',
                            ),
                       4 => array(
                             'start' => 102,
                             'end'   => 103,
                             'raw'   => '5',
                            ),
                       5 => array(
                             'start' => 105,
                             'end'   => 106,
                             'raw'   => '6',
                            ),
                       6 => array(
                             'start' => 108,
                             'end'   => 110,
                             'raw'   => 'true',
                            ),
                      ),

            ),
            array(120, array(
                        0 => array(
                              'start' => 122,
                              'end'   => 129,
                              'raw'   => 'dirname( __FILE__ )',
                             ),
                       ),
            ),
            array(250, array(
                        0 => array(
                              'start' => 252,
                              'end'   => 252,
                              'raw'   => '$stHour',
                             ),
                        1 => array(
                              'start' => 254,
                              'end'   => 255,
                              'raw'   => '0',
                             ),
                        2 => array(
                              'start' => 257,
                              'end'   => 258,
                              'raw'   => '0',
                             ),
                        3 => array(
                              'start' => 260,
                              'end'   => 264,
                              'raw'   => '$arrStDt[0]',
                             ),
                        4 => array(
                              'start' => 266,
                              'end'   => 270,
                              'raw'   => '$arrStDt[1]',
                             ),
                        5 => array(
                              'start' => 272,
                              'end'   => 276,
                              'raw'   => '$arrStDt[2]',
                             ),
                       ),

            ),
        );
    }


    /**
     * testGetFunctionCallParameter
     *
     * @group utilityFunctions
     *
     * @dataProvider dataGetFunctionCallParameter
     *
     * @param int    $stackPtr Stack pointer for a T_CLASS token in the test file.
     * @param string $expected The expected fully qualified class name.
     */
    public function testGetFunctionCallParameter($stackPtr, $paramPosition, $expected)
    {
        $result = $this->helperClass->getFunctionCallParameter($this->_phpcsFile, $stackPtr, $paramPosition);
        $this->assertSame($expected, $result);
    }

    /**
     * dataGetFunctionCallParameter
     *
     * @see testGetFunctionCallParameter()
     *
     * @return array
     */
    public function dataGetFunctionCallParameter()
    {
        return array(
            array(88, 3, array(
                          'start' => 99,
                          'end'   => 100,
                          'raw'   => '4',
                         ),
            ),
            array(120, 0, array(
                           'start' => 122,
                           'end'   => 129,
                           'raw'   => 'dirname( __FILE__ )',
                          ),
            ),
            array(250, 0, array(
                           'start' => 252,
                           'end'   => 252,
                           'raw'   => '$stHour',
                          ),
            ),
            array(250, 5, array(
                           'start' => 272,
                           'end'   => 276,
                           'raw'   => '$arrStDt[2]',
                          ),
            ),
        );
    }


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
