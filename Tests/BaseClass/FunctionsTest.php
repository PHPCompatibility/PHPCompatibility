<?php
/**
 * Generic sniff functions test file
 *
 * @package PHPCompatibility
 */


/**
 * Generic sniff functions sniff tests
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
     * testStripQuotes
     *
     * @group utilityFunctions
     *
     * @dataProvider dataStripQuotes
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

}
