<?php
/**
 * New Interfaces Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New Interfaces Sniff tests
 *
 * @group newInterfaces
 * @group interfaces
 *
 * @covers PHPCompatibility_Sniffs_PHP_NewInterfacesSniff
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
        $file  = $this->sniffFile(self::TEST_FILE, $lastVersionBefore);
        $error = "The built-in interface {$interfaceName} is not present in PHP version {$lastVersionBefore} or earlier";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
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
            array('Countable', '5.0', array(3, 17), '5.1'),
            array('OuterIterator', '5.0', array(4), '5.1'),
            array('RecursiveIterator', '5.0', array(5), '5.1'),
            array('SeekableIterator', '5.0', array(6, 17), '5.1'),
            array('Serializable', '5.0', array(7), '5.1'),
            array('SplObserver', '5.0', array(11), '5.1'),
            array('SplSubject', '5.0', array(12, 17), '5.1'),
            array('JsonSerializable', '5.3', array(13), '5.4'),
            array('SessionHandlerInterface', '5.3', array(14), '5.4'),
        );
    }

    /**
     * Test unsupported methods
     *
     * @return void
     */
    public function testUnsupportedMethods()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.1'); // Version in which the Serializable interface was introduced.
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
        $file = $this->sniffFile(self::TEST_FILE, '5.0');
        $this->assertError($file, 20, 'The built-in interface COUNTABLE is not present in PHP version 5.0 or earlier');
        $this->assertError($file, 21, 'The built-in interface countable is not present in PHP version 5.0 or earlier');
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.0'); // Low version below the first addition.
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return array(
            array(24),
            array(25),
        );
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will throw an error
     * on invalid use of some magic methods for the Serializable Interface.
     */

}
