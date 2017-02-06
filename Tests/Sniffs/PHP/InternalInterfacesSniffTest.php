<?php
/**
 * Internal Interfaces Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Internal Interfaces Sniff tests
 *
 * @group internalInterfaces
 * @group interfaces
 *
 * @covers PHPCompatibility_Sniffs_PHP_InternalInterfacesSniff
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class InternalInterfacesSniffTest extends BaseSniffTest
{
    /**
     * Sniffed file
     *
     * @var PHP_CodeSniffer_File
     */
    protected $_sniffFile;

    /**
     * Set up the test file for this unit test.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_sniffFile = $this->sniffFile('sniff-examples/internal_interfaces.php');
    }

    /**
     * Test Traversable interface
     *
     * @return void
     */
    public function testTraversable()
    {
        $this->assertError($this->_sniffFile, 3, 'The interface Traversable shouldn\'t be implemented directly, implement the Iterator or IteratorAggregate interface instead.');
    }

    /**
     * Test DateTimeInterface interface
     *
     * @return void
     */
    public function testDateTimeInterface()
    {
        $this->assertError($this->_sniffFile, 4, 'The interface DateTimeInterface is intended for type hints only and is not implementable.');
    }

    /**
     * Test Throwable interface
     *
     * @return void
     */
    public function testThrowable()
    {
        $this->assertError($this->_sniffFile, 5, 'The interface Throwable cannot be implemented directly, extend the Exception class instead.');
    }

    /**
     * Test multiple interfaces
     *
     * @return void
     */
    public function testMultipleInterfaces()
    {
        $this->assertError($this->_sniffFile, 7, 'The interface Traversable shouldn\'t be implemented directly, implement the Iterator or IteratorAggregate interface instead.');
        $this->assertError($this->_sniffFile, 7, 'The interface Throwable cannot be implemented directly, extend the Exception class instead.');
    }

    /**
     * Test interfaces in different cases.
     *
     * @return void
     */
    public function testCaseInsensitive()
    {
        $this->assertError($this->_sniffFile, 9, 'The interface DATETIMEINTERFACE is intended for type hints only and is not implementable.');
        $this->assertError($this->_sniffFile, 10, 'The interface datetimeinterface is intended for type hints only and is not implementable.');
    }
}
