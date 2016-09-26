<?php
/**
 * Short array syntax test file
 *
 * @package PHPCompatibility
 */


/**
 * Short array syntax sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 */
class ShortArraySniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/short_array.php';

    /**
     * testViolation
     *
     * @group shortArraySyntax
     *
     * @dataProvider dataViolation
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testViolation($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertError($file, $line, 'Short array syntax (open) is available since 5.4');
        $this->assertError($file, $line, 'Short array syntax (close) is available since 5.4');

        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testViolation()
     *
     * @return array
     */
    public function dataViolation()
    {
        return array(
            array(12),
            array(13),
            array(14),
        );
    }


    /**
     * testNoViolation
     *
     * @group shortArraySyntax
     *
     * @dataProvider dataNoViolation
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoViolation($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
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
            array(5),
            array(6),
            array(7),
        );
    }
}