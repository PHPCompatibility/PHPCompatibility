<?php
/**
 * Function parameter count test file
 *
 * @package PHPCompatibility
 */


/**
 * Function parameters count function tests
 *
 * @uses    BaseClass_MethodTestFrame
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class BaseClass_GetFunctionParametersTest extends BaseClass_MethodTestFrame
{

    public $filename = '../sniff-examples/utility-functions/get_function_parameters.php';

    /**
     * testGetFunctionCallParameters
     *
     * @group utilityFunctions
     *
     * @dataProvider dataGetFunctionCallParameters
     *
     * @covers PHPCompatibility_Sniff::getFunctionCallParameters
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
                       1 => array(
                             'start' => 90,
                             'end'   => 91,
                             'raw'   => '1',
                            ),
                       2 => array(
                             'start' => 93,
                             'end'   => 94,
                             'raw'   => '2',
                            ),
                       3 => array(
                             'start' => 96,
                             'end'   => 97,
                             'raw'   => '3',
                            ),
                       4 => array(
                             'start' => 99,
                             'end'   => 100,
                             'raw'   => '4',
                            ),
                       5 => array(
                             'start' => 102,
                             'end'   => 103,
                             'raw'   => '5',
                            ),
                       6 => array(
                             'start' => 105,
                             'end'   => 106,
                             'raw'   => '6',
                            ),
                       7 => array(
                             'start' => 108,
                             'end'   => 110,
                             'raw'   => 'true',
                            ),
                      ),

            ),
            array(120, array(
                        1 => array(
                              'start' => 122,
                              'end'   => 129,
                              'raw'   => 'dirname( __FILE__ )',
                             ),
                       ),
            ),
            array(250, array(
                        1 => array(
                              'start' => 252,
                              'end'   => 252,
                              'raw'   => '$stHour',
                             ),
                        2 => array(
                              'start' => 254,
                              'end'   => 255,
                              'raw'   => '0',
                             ),
                        3 => array(
                              'start' => 257,
                              'end'   => 258,
                              'raw'   => '0',
                             ),
                        4 => array(
                              'start' => 260,
                              'end'   => 264,
                              'raw'   => '$arrStDt[0]',
                             ),
                        5 => array(
                              'start' => 266,
                              'end'   => 270,
                              'raw'   => '$arrStDt[1]',
                             ),
                        6 => array(
                              'start' => 272,
                              'end'   => 276,
                              'raw'   => '$arrStDt[2]',
                             ),
                       ),

            ),
            array(535, array(
                        1 => array(
                              'start' => 537,
                              'end'   => 540,
                              'raw'   => 'array()',
                             ),
                       ),

            ),
            array(577, array(
                        1 => array(
                              'start' => 579,
                              'end'   => 611,
                              'raw'   => '[\'a\' => $a,] + (isset($b) ? [\'b\' => $b,] : [])',
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
     * @covers PHPCompatibility_Sniff::getFunctionCallParameter
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
            array(88, 4, array(
                          'start' => 99,
                          'end'   => 100,
                          'raw'   => '4',
                         ),
            ),
            array(120, 1, array(
                           'start' => 122,
                           'end'   => 129,
                           'raw'   => 'dirname( __FILE__ )',
                          ),
            ),
            array(250, 1, array(
                           'start' => 252,
                           'end'   => 252,
                           'raw'   => '$stHour',
                          ),
            ),
            array(250, 6, array(
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
     * @covers PHPCompatibility_Sniff::getFunctionCallParameterCount
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
            array(535, 1),

            // Issue #211.
            array(551, 1),
            array(564, 1),
            array(577, 1),
            array(615, 1),
            array(660, 1),
            array(710, 1),
            array(761, 1),
            array(818, 1),
            array(874, 1),
            array(894, 1),
            array(910, 1),
            array(930, 1),
            array(964, 1),
            array(984, 1),
            array(1008, 1),
            array(1038, 1),
            array(1074, 1),
            array(1111, 1),
            array(1154, 1),
            array(1184, 1),
            array(1215, 1),
            array(1241, 1),
        );
    }

}
