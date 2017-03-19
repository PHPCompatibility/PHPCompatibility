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
 * @uses    PHPCompatibility_Testcase_Wrapper
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class BaseClass_FunctionsTest extends PHPCompatibility_Testcase_Wrapper
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
     * testGetTestVersion
     *
     * @dataProvider dataGetTestVersion
     *
     * @covers PHPCompatibility_Sniff::getTestVersion
     *
     * @requires PHP 5.3.2
     * @internal Requirement is needed to be able to test the private method
     *           using `ReflectionMethod::setAccessible()`.
     *           The function itself works fine in PHP < 5.3.2.
     *
     * @param string $testVersion The testVersion as normally set via the command line or ruleset.
     * @param string $expected    The expected testVersion array.
     */
    public function testGetTestVersion($testVersion, $expected)
    {
        if (isset($testVersion)) {
            PHP_CodeSniffer::setConfigData('testVersion', $testVersion, true);
        }

        $this->assertSame($expected, $this->invokeMethod($this->helperClass, 'GetTestVersion'));

        // Clean up / reset the value.
        PHP_CodeSniffer::setConfigData('testVersion', null, true);
    }

    /**
     * dataGetTestVersion
     *
     * @see testGetTestVersion()
     *
     * @return array
     */
    public function dataGetTestVersion()
    {
        return array(
            array(null, array(null, null)), // No testVersion provided.
            array('5.0', array('5.0', '5.0')), // Single version.
            array('7.1', array('7.1', '7.1')), // Single version.
            array('4.0-99.0', array('4.0', '99.0')), // Range of versions.
            array('5.1-5.5', array('5.1', '5.5')), // Range of versions.
            array('7.0-7.5', array('7.0', '7.5')), // Range of versions.
            array('5.6-5.6', array('5.6', '5.6')), // Range of versions - min & max the same.
            array('4.0 - 99.0', array('4.0', '99.0')), // Range of versions with spaces around dash.
            array('-5.6', array('4.0', '5.6')), // Range, with no minimum.
            array('7.0-', array('7.0', '99.9')), // Range, with no maximum.
        );
    }


    /**
     * testGetTestVersionInvalidRange
     *
     * @dataProvider dataGetTestVersionInvalidRange
     *
     * @covers PHPCompatibility_Sniff::getTestVersion
     *
     * @requires PHP 5.3.2
     * @internal Requirement is needed to be able to test the private method
     *           using `ReflectionMethod::setAccessible()`.
     *           The function itself works fine in PHP < 5.3.2.
     *
     * @param string $testVersion The testVersion as normally set via the command line or ruleset.
     */
    public function testGetTestVersionInvalidRange($testVersion)
    {
        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException(
                'PHPUnit_Framework_Error_Warning',
                sprintf('Invalid range in testVersion setting: \'%s\'', $testVersion)
            );
        } else {
            $this->expectException('PHPUnit\Framework\Error\Warning');
            $this->expectExceptionMessage(sprintf('Invalid range in testVersion setting: \'%s\'', $testVersion));
        }

        $this->testGetTestVersion($testVersion, array(null, null));
    }

    /**
     * dataGetTestVersionInvalidRange
     *
     * @see testGetTestVersionInvalidRange()
     *
     * @return array
     */
    public function dataGetTestVersionInvalidRange()
    {
        return array(
            array('5.6-5.4'), // Range of versions - min > max.
        );
    }


    /**
     * testGetTestVersionInvalidVersion
     *
     * @dataProvider dataGetTestVersionInvalidVersion
     *
     * @covers PHPCompatibility_Sniff::getTestVersion
     *
     * @requires PHP 5.3.2
     * @internal Requirement is needed to be able to test the private method
     *           using `ReflectionMethod::setAccessible()`.
     *           The function itself works fine in PHP < 5.3.2.
     *
     * @param string $testVersion The testVersion as normally set via the command line or ruleset.
     */
    public function testGetTestVersionInvalidVersion($testVersion)
    {
        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException(
                'PHPUnit_Framework_Error_Warning',
                sprintf('Invalid testVersion setting: \'%s\'', $testVersion)
            );
        } else {
            $this->expectException('PHPUnit\Framework\Error\Warning');
            $this->expectExceptionMessage(sprintf('Invalid testVersion setting: \'%s\'', $testVersion));
        }

        $this->testGetTestVersion($testVersion, array(null, null));
    }

    /**
     * dataGetTestVersionInvalidVersion
     *
     * @see testGetTestVersionInvalidVersion()
     *
     * @return array
     */
    public function dataGetTestVersionInvalidVersion()
    {
        return array(
            array('5'), // Not in major.minor format.
            array('568'), // Not in major.minor format.
            array('5.6.28'), // Not in major.minor format.
            array('seven.one'), // Non numeric.
        );
    }


    /**
     * testSupportsAbove
     *
     * @dataProvider dataSupportsAbove
     *
     * @covers PHPCompatibility_Sniff::supportsAbove
     *
     * @param string $phpVersion  The PHP version we want to test.
     * @param string $testVersion The testVersion as normally set via the command line or ruleset.
     * @param string $expected    Expected result.
     */
    public function testSupportsAbove($phpVersion, $testVersion, $expected)
    {
        if (isset($testVersion)) {
            PHP_CodeSniffer::setConfigData('testVersion', $testVersion, true);
        }

        $this->assertSame($expected, $this->helperClass->supportsAbove($phpVersion));

        // Clean up / reset the value.
        PHP_CodeSniffer::setConfigData('testVersion', null, true);
    }

    /**
     * dataSupportsAbove
     *
     * @see testSupportsAbove()
     *
     * @return array
     */
    public function dataSupportsAbove()
    {
        return array(
            array('5.0', null, true),
            array('5.0', '5.2', true),
            array('5.0', '5.1-5.4', true),
            array('5.0', '5.3-7.0', true),

            array('7.1', null, true),
            array('7.1', '5.2', false),
            array('7.1', '5.1-5.4', false),
            array('7.1', '5.3-7.0', false),
        );
    }


    /**
     * testSupportsBelow
     *
     * @dataProvider dataSupportsBelow
     *
     * @covers PHPCompatibility_Sniff::supportsBelow
     *
     * @param string $phpVersion  The PHP version we want to test.
     * @param string $testVersion The testVersion as normally set via the command line or ruleset.
     * @param string $expected    Expected result.
     */
    public function testSupportsBelow($phpVersion, $testVersion, $expected)
    {
        if (isset($testVersion)) {
            PHP_CodeSniffer::setConfigData('testVersion', $testVersion, true);
        }

        $this->assertSame($expected, $this->helperClass->supportsBelow($phpVersion));

        // Clean up / reset the value.
        PHP_CodeSniffer::setConfigData('testVersion', null, true);
    }

    /**
     * dataSupportsBelow
     *
     * @see testSupportsBelow()
     *
     * @return array
     */
    public function dataSupportsBelow()
    {
        return array(
            array('5.0', null, false),
            array('5.0', '5.2', false),
            array('5.0', '5.1-5.4', false),
            array('5.0', '5.3-7.0', false),

            array('7.1', null, false),
            array('7.1', '5.2', true),
            array('7.1', '5.1-5.4', true),
            array('7.1', '5.3-7.0', true),
        );
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


    /**
     * Test helper: Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    private function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

}
