<?php
/**
 * New Interfaces Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New Interfaces Sniff tests
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewInterfacesSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/new_interfaces.php';

    /**
     * testNewInterface
     *
     * @dataProvider dataNewInterface
     *
     * @param string $interfaceName     Interface name.
     * @param string $lastVersionBefore The PHP version just *before* the class was introduced.
     * @param array  $lines             The line numbers in the test file which apply to this class.
     * @param string $okVersion         A PHP version in which the class was ok to be used.
     *
     * @return void
     */
    public function testNewInterface($interfaceName, $lastVersionBefore, $lines, $okVersion)
    {
        $file = $this->sniffFile(self::TEST_FILE, $lastVersionBefore);
        foreach ($lines as $line) {
            $this->assertError($file, $line, "The built-in interface {$interfaceName} is not present in PHP version {$lastVersionBefore} or earlier");
        }

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testNewInterface()
     *
     * @return array
     */
    public function dataNewInterface()
    {
        return array(
            array('Countable', '5.0', array(3, 17), '5.5'),
            array('OuterIterator', '5.0', array(4), '5.5'),
            array('RecursiveIterator', '5.0', array(5), '5.5'),
            array('SeekableIterator', '5.0', array(6), '5.5'),
            array('Serializable', '5.0', array(7), '5.5'),
            array('SplObserver', '5.0', array(11), '5.5'),
            array('SplSubject', '5.0', array(12, 17), '5.5'),
            array('JsonSerializable', '5.3', array(13, 17), '5.5'),
            array('SessionHandlerInterface', '5.3', array(14), '5.5'),
        );
    }

    /**
     * Test unsupported methods
     *
     * @return void
     */
    public function testUnsupportedMethods()
    {
        $file = $this->sniffFile('sniff-examples/new_interfaces.php');
        $this->assertError($file, 8, 'Classes that implement interface Serializable do not support the method __sleep(). See http://php.net/serializable');
        $this->assertError($file, 9, 'Classes that implement interface Serializable do not support the method __wakeup(). See http://php.net/serializable');
    }

    /**
     * Test interfaces in different cases.
     *
     * @return void
     */
    public function testCaseInsensitive()
    {
        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.0');
        $this->assertError($file, 20, 'The built-in interface COUNTABLE is not present in PHP version 5.0 or earlier');
        $this->assertError($file, 21, 'The built-in interface countable is not present in PHP version 5.0 or earlier');
    }

}
