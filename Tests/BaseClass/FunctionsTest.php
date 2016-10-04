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


    /**
     * Set up fixtures for this unit test.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        require_once dirname(__FILE__) . '/TestHelperPHPCompatibility.php';
        parent::setUpBeforeClass();
    }

    /**
     * Sets up this unit test.
     *
     * @return void
     */
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


   /**
     * testStripVariables
     *
     * @dataProvider dataStripVariables
     *
     * @covers PHPCompatibility_Sniff::stripQuotes
     *
     * @param string $input    The input string.
     * @param string $expected The expected function output.
     */
    public function testStripVariables($input, $expected)
    {
        $this->assertSame($expected, $this->helperClass->stripVariables($input));
    }

    /**
     * dataStripVariables
     *
     * @see testStripVariables()
     *
     * @return array
     */
    public function dataStripVariables()
    {
        return array(
            // These would need to be matched when testing double quoted strings for variables.
            array('"He drank some $juice juice."', '"He drank some  juice."'),
            array('"He drank some juice made of $juices."', '"He drank some juice made of ."'),
            array('"He drank some juice made of ${juice}s."', '"He drank some juice made of s."'),
            array('"He drank some $juices[0] juice."', '"He drank some  juice."'),
            array('"He drank some $juices[1] juice."', '"He drank some  juice."'),
            array('"He drank some $juices[koolaid1] juice."', '"He drank some  juice."'),
            array('"$people->john drank some $juices[0] juice."', '" drank some  juice."'),
            array('"$people->john then said hello to $people->jane."', '" then said hello to ."'),
            array('"$people->john\'s wife greeted $people->robert."', '"\'s wife greeted ."'),
            array('"The element at index -3 is $array[-3]."', '"The element at index -3 is ."'),
            array('"This is {$great}"', '"This is "'),
            array('"This square is {$square->width}00 centimeters broad."', '"This square is 00 centimeters broad."'),
            array('"This works: {$arr[\'key\']}"', '"This works: "'),
            array('"This works: {$arr[4][3]}"', '"This works: "'),
            array('"This is wrong: {$arr[foo][3]}"', '"This is wrong: "'),
            array('"This works: {$arr[\'foo\'][3]}"', '"This works: "'),
            array('"This works too: {$obj->values[3]->name}"', '"This works too: "'),

            array('"This is the value of the var named $name: {${$name}}"', '"This is the value of the var named : "'),
            array('"This is the value of the var named \$name: {${$name}}"', '"This is the value of the var named \$name: "'),
            array('"This is the value of the var named by the return value of getName(): {${getName()}}"', '"This is the value of the var named by the return value of getName(): "'),
            array('"This is the value of the var named by the return value of getName(): {${getName( $test )}}"', '"This is the value of the var named by the return value of getName(): "'),
            array('"This is the value of the var named by the return value of getName(): {${getName( \'abc\' )}}"', '"This is the value of the var named by the return value of getName(): "'),
            array('"This is the value of the var named by the return value of \$object->getName(): {${$object->getName()}}"', '"This is the value of the var named by the return value of \$object->getName(): "'),
            array('"{$foo->$bar}\n"', '"\n"'),
            array('"I\'d like an {${beers::softdrink}}\n"', '"I\'d like an \n"'),
            array('"I\'d like an {${beers::$ale}}\n"', '"I\'d like an \n"'),

            array('"{$foo->{$baz[1]}}\n"', '"{->}\n"'), // Problem var, only one I haven't managed to work out properly.

            // These shouldn't match and should be returned as is.
            array('"He drank some \\\\$juice juice."', '"He drank some \\\\$juice juice."'),
            array('"This is { $great}"', '"This is { }"'),
            array('"This is the return value of getName(): {getName()}"', '"This is the return value of getName(): {getName()}"'),
        );
    }

}
