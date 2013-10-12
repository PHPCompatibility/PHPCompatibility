<?php
/**
 * Removed hash algorithms sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Removed hash algorithms sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class RemovedHashAlgorithmsSniffTest extends BaseSniffTest
{
    /**
     * Sniffed file
     *
     * @var PHP_CodeSniffer_File
     */
    protected $_sniffFile;

    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->_sniffFile = $this->sniffFile('sniff-examples/removed_hash_algorithms.php');
    }

    /**
     * testHashFile
     *
     * @return void
     */
    public function testHashFile()
    {
        $this->assertNoViolation($this->_sniffFile, 3);
        $this->assertError($this->_sniffFile, 4, "The Salsa10 and Salsa20 hash algorithms have been removed since PHP 5.4");
        $this->assertError($this->_sniffFile, 5, "The Salsa10 and Salsa20 hash algorithms have been removed since PHP 5.4");
        $this->assertError($this->_sniffFile, 6, "The Salsa10 and Salsa20 hash algorithms have been removed since PHP 5.4");
        $this->assertError($this->_sniffFile, 7, "The Salsa10 and Salsa20 hash algorithms have been removed since PHP 5.4");
    }

    /**
     * testHashHmacFile
     *
     * @return void
     */
    public function testHashHmacFile()
    {
        $this->assertError($this->_sniffFile, 9, "The Salsa10 and Salsa20 hash algorithms have been removed since PHP 5.4");
    }

    /**
     * testHashHmac
     *
     * @return void
     */
    public function testHashHmac()
    {
        $this->assertError($this->_sniffFile, 11, "The Salsa10 and Salsa20 hash algorithms have been removed since PHP 5.4");
    }

    /**
     * testHashInit
     *
     * @return void
     */
    public function testHashInit()
    {
        $this->assertError($this->_sniffFile, 13, "The Salsa10 and Salsa20 hash algorithms have been removed since PHP 5.4");
    }

    /**
     * testHash
     *
     * @return void
     */
    public function testHash()
    {
        $this->assertError($this->_sniffFile, 15, "The Salsa10 and Salsa20 hash algorithms have been removed since PHP 5.4");
        $this->assertError($this->_sniffFile, 16, "The Salsa10 and Salsa20 hash algorithms have been removed since PHP 5.4");
    }

    /**
     * testIgnoreSecondParameter
     *
     * @return void
     */
    public function testIgnoreSecondParameter()
    {
        $this->assertNoViolation($this->_sniffFile, 17);
    }

    /**
     * testIgnoreNonFunctionCall
     *
     * @return void
     */
    public function testIgnoreNonFunctionCall()
    {
        $this->assertNoViolation($this->_sniffFile, 18);
    }

}
