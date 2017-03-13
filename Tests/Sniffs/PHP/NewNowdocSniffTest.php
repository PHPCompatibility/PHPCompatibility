<?php
/**
 * New nowdoc sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New nowdoc sniff tests
 *
 * @group newNowdoc
 * @group reservedKeywords
 *
 * @covers PHPCompatibility_Sniffs_PHP_NewNowdocSniff
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewNowdocSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_nowdoc.php';


    /**
     * testNowdoc
     *
     * @dataProvider dataNowdoc
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNowdoc($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertError($file, $line, 'Nowdocs are not present in PHP version 5.2 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testNowdoc()
     *
     * @return array
     */
    public function dataNowdoc()
    {
        return array(
            array(11),
            array(15),
            array(17),
            array(21),
            array(25),
            array(28),
            array(30),
            array(33),
            array(36),
            array(44),
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
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
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
        $data = array(
            array(4),
            array(8),
            array(26),
            array(32),
        );

        // PHPCS 1.x does not support skipping forward.
        if (version_compare(PHP_CodeSniffer::VERSION, '2.0', '>=')) {
            $data[] = array(38);
            $data[] = array(42);
        }

        return $data;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertNoViolation($file);
    }

}
