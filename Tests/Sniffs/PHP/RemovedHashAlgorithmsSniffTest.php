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
     * testRemovedHashAlgorithms
     *
     * @dataProvider dataRemovedHashAlgorithms
     *
     * @param int $line Line number on which an error should occur.
     *
     * @return void
     */
    public function testRemovedHashAlgorithms($line)
    {
        $this->assertError($this->_sniffFile, $line, 'The Salsa10 and Salsa20 hash algorithms have been removed since PHP 5.4');
    }

    /**
     * dataRemovedHashAlgorithms
     *
     * @see testRemovedHashAlgorithms()
     *
     * @return array
     */
    public function dataRemovedHashAlgorithms()
    {
        return array(
            array(4),
            array(5),
            array(6),
            array(7),
            array(9),
            array(11),
            array(13),
            array(15),
            array(16),
        );
    }


    /**
     * testOkHashAlgorithm
     *
     * @return void
     */
    public function testOkHashAlgorithm()
    {
        $this->assertNoViolation($this->_sniffFile, 3);
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
