<?php
/**
 * Function parameter count test file
 *
 * @package PHPCompatibility
 */


/**
 * Function parameters count function tests
 *
 * @group utilityGetFunctionParameters
 * @group utilityFunctions
 *
 * @uses    BaseClass_MethodTestFrame
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class BaseClass_GetFunctionParametersTest extends BaseClass_MethodTestFrame
{

    /**
     * The file name for the file containing the test cases within the
     * `sniff-examples/utility-functions/` directory.
     *
     * @var string
     */
    protected $filename = 'get_function_parameters.php';

    /**
     * testGetFunctionCallParameters
     *
     * @dataProvider dataGetFunctionCallParameters
     *
     * @covers PHPCompatibility_Sniff::getFunctionCallParameters
     *
     * @param int    $stackPtr Stack pointer for a function call T_STRING,
     *                         T_ARRAY or T_OPEN_SHORT_ARRAY token in the test file.
     * @param string $expected The expected parameter array.
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

            // Long array.
            array(1422, array(
                        1 => array(
                              'start' => 1424,
                              'end'   => 1430,
                              'raw'   => 'some_call(5, 1)',
                             ),
                        2 => array(
                              'start' => 1432,
                              'end'   => 1436,
                              'raw'   => 'another(1)',
                             ),
                        3 => array(
                              'start' => 1438,
                              'end'   => 1448,
                              'raw'   => 'why(5, 1, 2)',
                             ),
                        4 => array(
                              'start' => 1450,
                              'end'   => 1451,
                              'raw'   => '4',
                             ),
                        5 => array(
                              'start' => 1453,
                              'end'   => 1454,
                              'raw'   => '5',
                             ),
                        6 => array(
                              'start' => 1456,
                              'end'   => 1457,
                              'raw'   => '6',
                             ),
                       ),
            ),

            // Short array.
            array(1667, array(
                        1 => array(
                              'start' => 1668,
                              'end'   => 1668,
                              'raw'   => '0',
                             ),
                        2 => array(
                              'start' => 1670,
                              'end'   => 1671,
                              'raw'   => '0',
                             ),
                        3 => array(
                              'start' => 1673,
                              'end'   => 1677,
                              'raw'   => 'date(\'s\')',
                             ),
                        4 => array(
                              'start' => 1679,
                              'end'   => 1683,
                              'raw'   => 'date(\'m\')',
                             ),
                        5 => array(
                              'start' => 1685,
                              'end'   => 1689,
                              'raw'   => 'date(\'d\')',
                             ),
                        6 => array(
                              'start' => 1691,
                              'end'   => 1695,
                              'raw'   => 'date(\'Y\')',
                             ),
                       ),
            ),
        );
    }


    /**
     * testGetFunctionCallParameter
     *
     * @dataProvider dataGetFunctionCallParameter
     *
     * @covers PHPCompatibility_Sniff::getFunctionCallParameter
     *
     * @param int    $stackPtr Stack pointer for a function call T_STRING,
     *                         T_ARRAY or T_OPEN_SHORT_ARRAY token in the test file.
     * @param string $expected The expected array for the specific parameter.
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
            array(1296, 1, array(
                           'start' => 1298,
                           'end'   => 1299,
                           'raw'   => '1',
                          ),
            ),
            array(1296, 7, array(
                           'start' => 1316,
                           'end'   => 1318,
                           'raw'   => 'true',
                          ),
            ),
            array(1422, 3, array(
                           'start' => 1438,
                           'end'   => 1448,
                           'raw'   => 'why(5, 1, 2)',
                          ),
            ),
            array(1466, 2, array(
                           'start' => 1474,
                           'end'   => 1479,
                           'raw'   => '\'b\' => $b',
                          ),
            ),
            array(1611, 1, array(
                           'start' => 1612,
                           'end'   => 1624,
                           'raw'   => 'str_replace("../", "/", trim($value))',
                          ),
            ),
            array(1814, 3, array(
                           'start' => 1828,
                           'end'   => 1850,
                           'raw'   => '(isset($c) ? 6 => $c : 6 => null)',
                          ),
            ),
        );
    }


    /**
     * testGetFunctionCallParameterCount
     *
     * @dataProvider dataGetFunctionCallParameterCount
     *
     * @covers PHPCompatibility_Sniff::getFunctionCallParameterCount
     *
     * @param int    $stackPtr Stack pointer for a function call T_STRING,
     *                         T_ARRAY or T_OPEN_SHORT_ARRAY token in the test file.
     * @param string $expected The expected parameter count.
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

            // Long arrays.
            array(1296, 7),
            array(1326, 1),
            array(1349, 6),
            array(1384, 6),
            array(1422, 6),
            array(1466, 3),
            array(1494, 3),
            array(1535, 3),

            // Short arrays.
            array(1582, 7),
            array(1611, 1),
            array(1633, 6),
            array(1667, 6),
            array(1704, 6),
            array(1747, 3),
            array(1774, 3),
            array(1814, 3),
        );
    }

}
