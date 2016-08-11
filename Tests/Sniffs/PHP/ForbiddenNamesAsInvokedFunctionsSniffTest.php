<?php
/**
 * Forbidden names as function invocations sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Forbidden names as function invocations sniff test file
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class ForbiddenNamesAsInvokedFunctionsSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/forbidden_names_function_invocation.php';

    /**
     * testReservedKeyword
     *
     * @dataProvider dataReservedKeyword
     *
     * @param string $keyword      Reserved keyword.
     * @param array  $lines        The line numbers in the test file which apply to this keyword.
     * @param string $introducedIn The PHP version in which the keyword became a reserved word.
     * @param string $okVersion    A PHP version in which the keyword was not yet reserved.
     *
     * @return void
     */
    public function testReservedKeyword($keyword, $lines, $introducedIn, $okVersion)
    {
        $file = $this->sniffFile(self::TEST_FILE, $introducedIn);
        foreach ($lines as $line) {
            $this->assertError($file, $line, "'{$keyword}' is a reserved keyword introduced in PHP version {$introducedIn} and cannot be invoked as a function");
        }

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testReservedKeyword()
     *
     * @return array
     */
    public function dataReservedKeyword()
    {
        return array(
            array('abstract', array(6), '5.0', '4.4'),
            array('callable', array(7), '5.4', '5.3'),
            array('catch', array(8), '5.0', '4.4'),
            array('clone', array(9), '5.0', '4.4'),
            array('final', array(10), '5.0', '4.4'),
            array('finally', array(11), '5.5', '5.4'),
            array('goto', array(12), '5.3', '5.2'),
            array('implements', array(13), '5.0', '4.4'),
            array('interface', array(14), '5.0', '4.4'),
            array('instanceof', array(15), '5.0', '4.4'),
            array('insteadof', array(16), '5.4', '5.3'),
            array('namespace', array(17), '5.3', '5.2'),
            array('private', array(18), '5.0', '4.4'),
            array('protected', array(19), '5.0', '4.4'),
            array('public', array(20), '5.0', '4.4'),
            array('trait', array(22), '5.4', '5.3'),
            array('try', array(23), '5.0', '4.4'),
            array('bool', array(34), '7.0', '5.6'),
            array('int', array(35), '7.0', '5.6'),
            array('float', array(36), '7.0', '5.6'),
            array('string', array(37), '7.0', '5.6'),
            array('null', array(38, 39), '7.0', '5.6'),
            array('true', array(40, 41), '7.0', '5.6'),
            array('false', array(42, 43), '7.0', '5.6'),
            array('resource', array(44), '7.0', '5.6'),
            array('object', array(45), '7.0', '5.6'),
            array('mixed', array(46), '7.0', '5.6'),
            array('numeric', array(47), '7.0', '5.6'),
        );
    }

}
