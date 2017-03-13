<?php
/**
 * Removed hash algorithms sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Removed hash algorithms sniff tests
 *
 * @group removedHashAlgorithms
 * @group hashAlgorithms
 *
 * @covers PHPCompatibility_Sniffs_PHP_RemovedHashAlgorithmsSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class RemovedHashAlgorithmsSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/removed_hash_algorithms.php';

    /**
     * testRemovedHashAlgorithms
     *
     * @dataProvider dataRemovedHashAlgorithms
     *
     * @param string $algorithm Name of the algorithm.
     * @param string $removedIn The PHP version in which the algorithm was removed.
     * @param array  $line      The line number on which the error should occur.
     * @param string $okVersion A PHP version in which the algorithm was still valid.
     *
     * return void
     */
    public function testRemovedHashAlgorithms($algorithm, $removedIn, $line, $okVersion)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        $this->assertNoViolation($file, $line);

        $file = $this->sniffFile(self::TEST_FILE, $removedIn);
        $this->assertError($file, $line, "The {$algorithm} hash algorithm is removed since PHP {$removedIn}");
    }

    /**
     * Data provider.
     *
     * @see testRemovedHashAlgorithms()
     *
     * @return array
     */
    public function dataRemovedHashAlgorithms()
    {
        return array(
            array('salsa10', '5.4', 13, '5.3'),
            array('salsa20', '5.4', 14, '5.3'),
            array('salsa10', '5.4', 15, '5.3'),
            array('salsa20', '5.4', 16, '5.3'),
            array('salsa10', '5.4', 18, '5.3'),
            array('salsa20', '5.4', 19, '5.3'),
            array('salsa10', '5.4', 20, '5.3'),
            array('salsa10', '5.4', 22, '5.3'),
            array('salsa10', '5.4', 23, '5.3'),
            array('salsa20', '5.4', 25, '5.3'),
        );
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
        $file = $this->sniffFile(self::TEST_FILE, '99.0'); // High version beyond latest deprecation.
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
            array(6),
            array(7),
            array(8),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3'); // Low version below the first removal.
        $this->assertNoViolation($file);
    }

}
