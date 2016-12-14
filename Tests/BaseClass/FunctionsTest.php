<?php
/**
 * Generic sniff functions test file
 *
 * @package PHPCompatibility
 */


/**
 * Generic sniff functions sniff tests
 *
 * @group utilityMiscFunctions
 * @group utilityFunctions
 *
 * @uses    PHPUnit_Framework_TestCase
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class BaseClass_FunctionsTest extends PHPUnit_Framework_TestCase
{

    /**
     * A wrapper for the abstract PHPCompatibility sniff.
     *
     * @var PHPCompatibility_Sniff
     */
    protected $helperClass;


    public static function setUpBeforeClass()
    {
        require_once dirname(__FILE__) . '/TestHelperPHPCompatibility.php';
    }

    protected function setUp()
    {
        parent::setUp();

        $this->helperClass = new BaseClass_TestHelperPHPCompatibility;
    }


   /**
     * testStringToErrorCode
     *
     * @dataProvider dataStringToErrorCode
     *
     * @covers PHPCompatibility_Sniff::stringToErrorCode
     *
     * @param string $input    The input string.
     * @param string $expected The expected error code.
     */
    public function testStringToErrorCode($input, $expected)
    {
        $this->assertSame($expected, $this->helperClass->stringToErrorCode($input));
    }

    /**
     * dataStringToErrorCode
     *
     * @see testStringToErrorCode()
     *
     * @return array
     */
    public function dataStringToErrorCode()
    {
        return array(
            array('dir_name', 'dir_name'), // No change.
            array('soap.wsdl_cache', 'soap_wsdl_cache'), // No dot.
            array('arbitrary-string with space', 'arbitrary_string_with_space'), // No dashes, no spaces.
            array('^%*&%*€יבר', '__________'), // No non alpha-numeric characters.
        );
    }


   /**
     * testStripQuotes
     *
     * @dataProvider dataStripQuotes
     *
     * @covers PHPCompatibility_Sniff::stripQuotes
     *
     * @param string $input    The input string.
     * @param string $expected The expected function output.
     */
    public function testStripQuotes($input, $expected)
    {
        $this->assertSame($expected, $this->helperClass->stripQuotes($input));
    }

    /**
     * dataStripQuotes
     *
     * @see testStripQuotes()
     *
     * @return array
     */
    public function dataStripQuotes()
    {
        return array(
            array('"dir_name"', 'dir_name'),
            array("'soap.wsdl_cache'", 'soap.wsdl_cache'),
            array('"arbitrary-\'string\" with\' quotes within"', 'arbitrary-\'string\" with\' quotes within'),
            array('"\'quoted_name\'"', "'quoted_name'"),
            array("'\"quoted\" start of string'", '"quoted" start of string'),
        );
    }


    /**
     * testArrayKeysToLowercase
     *
     * @dataProvider dataArrayKeysToLowercase
     *
     * @covers PHPCompatibility_Sniff::arrayKeysToLowercase
     *
     * @param string $input    The input string.
     * @param string $expected The expected function output.
     */
    public function testArrayKeysToLowercase($input, $expected)
    {
        $this->assertSame($expected, $this->helperClass->arrayKeysToLowercase($input));
    }

    /**
     * dataArrayKeysToLowercase
     *
     * @see testArrayKeysToLowercase()
     *
     * @return array
     */
    public function dataArrayKeysToLowercase()
    {
        return array(
            array(
                array(
                    'UPPERCASE' => true,
                    'MIXEDcase' => false,
                    'lowercase' => '123',
                ),
                array(
                    'uppercase' => true,
                    'mixedcase' => false,
                    'lowercase' => '123',
                ),
            ),
        );
    }

}
