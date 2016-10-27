<?php
/**
 * New hash algorithms sniff test file.
 *
 * @package PHPCompatibility
 */


/**
 * New hash algorithms sniff tests.
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewHashAlgorithmsSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_hash_algorithms.php';

    /**
     * testNewHashAlgorithms
     *
     * @group hashAlgorithms
     *
     * @dataProvider dataNewHashAlgorithms
     *
     * @param string $algorithm         Name of the algorithm.
     * @param string $lastVersionBefore The PHP version just *before* the algorithm was introduced.
     * @param array  $lines             The line number in the test file on which an error should occur.
     * @param string $okVersion         A PHP version in which the algorithm was valid.
     *
     * @return void
     */
    public function testNewHashAlgorithms($algorithm, $lastVersionBefore, $line, $okVersion)
    {
        $file = $this->sniffFile(self::TEST_FILE, $lastVersionBefore);
        $this->assertError($file, $line, "The {$algorithm} hash algorithm is not present in PHP version {$lastVersionBefore} or earlier");

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNewHashAlgorithms()
     *
     * @return array
     */
    public function dataNewHashAlgorithms()
    {
        return array(
            array('md2', '5.2', 13, '5.3'),
            array('ripemd256', '5.2', 14, '5.3'),
            array('ripemd320', '5.2', 15, '5.3'),
            array('salsa10', '5.2', 16, '5.3'),
            array('salsa20', '5.2', 18, '5.3'),
            array('snefru256', '5.2', 19, '5.3'),
            array('sha224', '5.2', 20, '5.3'),
            array('joaat', '5.3', 22, '5.4'),
            array('fnv132', '5.3', 23, '5.4'),
            array('fnv164', '5.3', 24, '5.4'),
            array('gost-crypto', '5.5', 26, '5.6'),
        );
    }


    /**
     * testNoViolation
     *
     * @group hashAlgorithms
     *
     * @dataProvider dataNoViolation
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoViolation($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.0-99.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoViolation()
     *
     * @return array
     */
    public function dataNoViolation()
    {
        return array(
            array(6),
            array(7),
            array(8),
        );
    }

}
