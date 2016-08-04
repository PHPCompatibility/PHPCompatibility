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
    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

    }

    /**
     * Test Countable interface
     *
     * @return void
     */
    public function testCountable()
    {
        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.0');
        $this->assertError($file, 3, 'The built-in interface Countable is not present in PHP version 5.0 or earlier');

        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.5');
        $this->assertNoViolation($file, 3);
    }

    /**
     * Test OuterIterator interface
     *
     * @return void
     */
    public function testOuterIterator()
    {
        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.0');
        $this->assertError($file, 4, 'The built-in interface OuterIterator is not present in PHP version 5.0 or earlier');

        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.5');
        $this->assertNoViolation($file, 4);
    }

    /**
     * Test RecursiveIterator interface
     *
     * @return void
     */
    public function testRecursiveIterator()
    {
        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.0');
        $this->assertError($file, 5, 'The built-in interface RecursiveIterator is not present in PHP version 5.0 or earlier');

        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.5');
        $this->assertNoViolation($file, 5);
    }

    /**
     * Test SeekableIterator interface
     *
     * @return void
     */
    public function testSeekableIterator()
    {
        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.0');
        $this->assertError($file, 6, 'The built-in interface SeekableIterator is not present in PHP version 5.0 or earlier');

        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.5');
        $this->assertNoViolation($file, 6);
    }

    /**
     * Test Serializable interface
     *
     * @return void
     */
    public function testSerializable()
    {
        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.0');
        $this->assertError($file, 7, 'The built-in interface Serializable is not present in PHP version 5.0 or earlier');

        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.5');
        $this->assertNoViolation($file, 7);
    }

    /**
     * Test SplObserver interface
     *
     * @return void
     */
    public function testSplObserver()
    {
        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.0');
        $this->assertError($file, 11, 'The built-in interface SplObserver is not present in PHP version 5.0 or earlier');

        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.5');
        $this->assertNoViolation($file, 11);
    }

    /**
     * Test SplSubject interface
     *
     * @return void
     */
    public function testSplSubject()
    {
        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.0');
        $this->assertError($file, 12, 'The built-in interface SplSubject is not present in PHP version 5.0 or earlier');

        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.5');
        $this->assertNoViolation($file, 12);
    }

    /**
     * Test JsonSerializable interface
     *
     * @return void
     */
    public function testJsonSerializable()
    {
        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.3');
        $this->assertError($file, 13, 'The built-in interface JsonSerializable is not present in PHP version 5.3 or earlier');

        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.5');
        $this->assertNoViolation($file, 13);
    }

    /**
     * Test SessionHandlerInterface interface
     *
     * @return void
     */
    public function testSessionHandlerInterface()
    {
        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.3');
        $this->assertError($file, 14, 'The built-in interface SessionHandlerInterface is not present in PHP version 5.3 or earlier');

        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.5');
        $this->assertNoViolation($file, 14);
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
     * Test multiple interfaces
     *
     * @return void
     */
    public function testMultipleInterfaces()
    {
        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.0');
        $this->assertError($file, 16, 'The built-in interface SplSubject is not present in PHP version 5.0 or earlier');
        $this->assertError($file, 16, 'The built-in interface JsonSerializable is not present in PHP version 5.3 or earlier');
        $this->assertError($file, 16, 'The built-in interface Countable is not present in PHP version 5.0 or earlier');
    }

    /**
     * Test interfaces in different cases.
     *
     * @return void
     */
    public function testCaseInsensitive()
    {
        $file = $this->sniffFile('sniff-examples/new_interfaces.php', '5.0');
        $this->assertError($file, 18, 'The built-in interface COUNTABLE is not present in PHP version 5.0 or earlier');
        $this->assertError($file, 19, 'The built-in interface countable is not present in PHP version 5.0 or earlier');
    }

}
