<?php
/**
 * New const visibility sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New const visibility sniff test file
 *
 * @group constVisibility
 * @group constants
 *
 * @covers PHPCompatibility_Sniffs_PHP_NewConstVisibilitySniff
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewConstVisibilitySniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_const_visibility.php';

    /**
     * testConstVisibility
     *
     * @dataProvider dataConstVisibility
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testConstVisibility($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertError($file, $line, 'Visibility indicators for class constants are not supported in PHP 7.0 or earlier.');

        $file = $this->sniffFile(self::TEST_FILE, '7.1');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testConstVisibility()
     *
     * @return array
     */
    public function dataConstVisibility()
    {
        return array(
            array(10),
            array(11),
            array(12),

            array(20),
            array(23),
            array(24),
        );
    }


    /**
     * testNoViolation
     *
     * @dataProvider dataNoViolation
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoViolation($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3'); // Arbitrary pre-PHP 7.1 version.
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
            array(3),
            array(7),
            array(17),
        );
    }
}
