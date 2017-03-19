<?php
/**
 * Function parameter retrieval test file
 *
 * @package PHPCompatibility
 */


/**
 * Function parameters retrieval function tests
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
     * @param string $commentString The comment which prefaces the target token in the test file.
     * @param string $expected The expected parameter array.
     */
    public function testGetFunctionCallParameters($commentString, $expected)
    {
        $stackPtr = $this->getTargetToken($commentString, array(T_STRING, T_ARRAY, T_OPEN_SHORT_ARRAY));
        /*
         * Start/end token position values in the expected array are set as offsets
         * in relation to the target token.
         *
         * Change these to exact positions based on the retrieved stackPtr.
         */
        foreach ($expected as $key => $value) {
            $expected[$key]['start'] = $stackPtr + $value['start'];
            $expected[$key]['end']   = $stackPtr + $value['end'];
        }

        $result   = $this->helperClass->getFunctionCallParameters($this->_phpcsFile, $stackPtr);
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
            array('/* Case S1 */', array(
                       1 => array(
                             'start' => 2,
                             'end'   => 3,
                             'raw'   => '1',
                            ),
                       2 => array(
                             'start' => 5,
                             'end'   => 6,
                             'raw'   => '2',
                            ),
                       3 => array(
                             'start' => 8,
                             'end'   => 9,
                             'raw'   => '3',
                            ),
                       4 => array(
                             'start' => 11,
                             'end'   => 12,
                             'raw'   => '4',
                            ),
                       5 => array(
                             'start' => 14,
                             'end'   => 15,
                             'raw'   => '5',
                            ),
                       6 => array(
                             'start' => 17,
                             'end'   => 18,
                             'raw'   => '6',
                            ),
                       7 => array(
                             'start' => 20,
                             'end'   => 22,
                             'raw'   => 'true',
                            ),
                      ),

            ),
            array('/* Case S2 */', array(
                        1 => array(
                              'start' => 2,
                              'end'   => 9,
                              'raw'   => 'dirname( __FILE__ )',
                             ),
                       ),
            ),
            array('/* Case S3 */', array(
                        1 => array(
                              'start' => 2,
                              'end'   => 2,
                              'raw'   => '$stHour',
                             ),
                        2 => array(
                              'start' => 4,
                              'end'   => 5,
                              'raw'   => '0',
                             ),
                        3 => array(
                              'start' => 7,
                              'end'   => 8,
                              'raw'   => '0',
                             ),
                        4 => array(
                              'start' => 10,
                              'end'   => 14,
                              'raw'   => '$arrStDt[0]',
                             ),
                        5 => array(
                              'start' => 16,
                              'end'   => 20,
                              'raw'   => '$arrStDt[1]',
                             ),
                        6 => array(
                              'start' => 22,
                              'end'   => 26,
                              'raw'   => '$arrStDt[2]',
                             ),
                       ),

            ),
            array('/* Case S4 */', array(
                        1 => array(
                              'start' => 2,
                              'end'   => 5,
                              'raw'   => 'array()',
                             ),
                       ),

            ),
            array('/* Case S5 */', array(
                        1 => array(
                              'start' => 2,
                              'end'   => 34,
                              'raw'   => '[\'a\' => $a,] + (isset($b) ? [\'b\' => $b,] : [])',
                             ),
                       ),

            ),

            // Long array.
            array('/* Case A1 */', array(
                        1 => array(
                              'start' => 2,
                              'end'   => 8,
                              'raw'   => 'some_call(5, 1)',
                             ),
                        2 => array(
                              'start' => 10,
                              'end'   => 14,
                              'raw'   => 'another(1)',
                             ),
                        3 => array(
                              'start' => 16,
                              'end'   => 26,
                              'raw'   => 'why(5, 1, 2)',
                             ),
                        4 => array(
                              'start' => 28,
                              'end'   => 29,
                              'raw'   => '4',
                             ),
                        5 => array(
                              'start' => 31,
                              'end'   => 32,
                              'raw'   => '5',
                             ),
                        6 => array(
                              'start' => 34,
                              'end'   => 35,
                              'raw'   => '6',
                             ),
                       ),
            ),

            // Short array.
            array('/* Case A2 */', array(
                        1 => array(
                              'start' => 1,
                              'end'   => 1,
                              'raw'   => '0',
                             ),
                        2 => array(
                              'start' => 3,
                              'end'   => 4,
                              'raw'   => '0',
                             ),
                        3 => array(
                              'start' => 6,
                              'end'   => 10,
                              'raw'   => 'date(\'s\')',
                             ),
                        4 => array(
                              'start' => 12,
                              'end'   => 16,
                              'raw'   => 'date(\'m\')',
                             ),
                        5 => array(
                              'start' => 18,
                              'end'   => 22,
                              'raw'   => 'date(\'d\')',
                             ),
                        6 => array(
                              'start' => 24,
                              'end'   => 28,
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
     * @param string $commentString The comment which prefaces the target token in the test file.
     * @param string $expected The expected array for the specific parameter.
     */
    public function testGetFunctionCallParameter($commentString, $paramPosition, $expected)
    {
        $stackPtr = $this->getTargetToken($commentString, array(T_STRING, T_ARRAY, T_OPEN_SHORT_ARRAY));
        /*
         * Start/end token position values in the expected array are set as offsets
         * in relation to the target token.
         *
         * Change these to exact positions based on the retrieved stackPtr.
         */
        $expected['start'] += $stackPtr;
        $expected['end']   += $stackPtr;

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
            array('/* Case S1 */', 4, array(
                          'start' => 11,
                          'end'   => 12,
                          'raw'   => '4',
                         ),
            ),
            array('/* Case S2 */', 1, array(
                           'start' => 2,
                           'end'   => 9,
                           'raw'   => 'dirname( __FILE__ )',
                          ),
            ),
            array('/* Case S3 */', 1, array(
                           'start' => 2,
                           'end'   => 2,
                           'raw'   => '$stHour',
                          ),
            ),
            array('/* Case S3 */', 6, array(
                           'start' => 22,
                           'end'   => 26,
                           'raw'   => '$arrStDt[2]',
                          ),
            ),
            array('/* Case A3 */', 1, array(
                           'start' => 2,
                           'end'   => 3,
                           'raw'   => '1',
                          ),
            ),
            array('/* Case A3 */', 7, array(
                           'start' => 20,
                           'end'   => 22,
                           'raw'   => 'true',
                          ),
            ),
            array('/* Case A1 */', 3, array(
                           'start' => 16,
                           'end'   => 26,
                           'raw'   => 'why(5, 1, 2)',
                          ),
            ),
            array('/* Case A4 */', 2, array(
                           'start' => 8,
                           'end'   => 13,
                           'raw'   => '\'b\' => $b',
                          ),
            ),
            array('/* Case A5 */', 1, array(
                           'start' => 1,
                           'end'   => 13,
                           'raw'   => 'str_replace("../", "/", trim($value))',
                          ),
            ),
            array('/* Case A6 */', 3, array(
                           'start' => 14,
                           'end'   => 36,
                           'raw'   => '(isset($c) ? 6 => $c : 6 => null)',
                          ),
            ),
        );
    }

}
