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
    const TEST_FILE = 'sniff-examples/internal_interfaces.php';

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

        // Sniff file without testVersion as all checks run independently of testVersion being set.
        $this->_sniffFile = $this->sniffFile(self::TEST_FILE);
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
        $this->assertNoViolation($this->_sniffFile, $line);
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
            array(13),
            array(14),
        );
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff is version independent.
     */

}
