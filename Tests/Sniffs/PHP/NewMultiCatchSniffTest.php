<?php
/**
 * New catching multiple exception types sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New catching multiple exception types sniff test file
 *
 * @group multiCatch
 * @group exceptions
 *
 * @covers PHPCompatibility_Sniffs_PHP_NewMultiCatchSniff
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewMultiCatchSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_multi_catch.php';

    /**
     * testNewMultiCatch
     *
     * @dataProvider dataNewMultiCatch
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewMultiCatch($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertError($file, $line, 'Catching multiple exceptions within one statement is not supported in PHP 7.0 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testNewMultiCatch()
     *
     * @return array
     */
    public function dataNewMultiCatch()
    {
        return array(
            array(21),
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
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
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
            array(8),
            array(10),
            array(12),
            array(23),
            array(30), // Live coding.
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.1');
        $this->assertNoViolation($file);
    }

}
