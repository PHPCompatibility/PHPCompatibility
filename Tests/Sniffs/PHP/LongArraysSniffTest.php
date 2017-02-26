<?php
/**
 * Long Arrays Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Long Arrays Sniff tests
 *
 * @group longArrays
 * @group superglobals
 *
 * @covers PHPCompatibility_Sniffs_PHP_LongArraysSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class LongArraysSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/long_arrays.php';

    /**
     * testLongVariable
     *
     * @dataProvider dataLongVariable
     *
     * @param string $longVariable Variable name.
     * @param array  $lines        The line numbers in the test file which apply to this variable.
     *
     * @return void
     */
    public function testLongVariable($longVariable, $lines)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, "The use of long predefined variables has been deprecated in PHP 5.3; Found '{$longVariable}'");
        }

        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        foreach ($lines as $line) {
            $this->assertError($file, $line, "The use of long predefined variables has been deprecated in PHP 5.3 and removed in PHP 5.4; Found '{$longVariable}'");
        }

        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testLongVariable()
     *
     * @return array
     */
    public function dataLongVariable()
    {
        return array(
            array('$HTTP_POST_VARS', array(3, 24)),
            array('$HTTP_GET_VARS', array(4, 25, 42)),
            array('$HTTP_ENV_VARS', array(5, 26, 43)),
            array('$HTTP_SERVER_VARS', array(6, 27)),
            array('$HTTP_COOKIE_VARS', array(7, 28)),
            array('$HTTP_SESSION_VARS', array(8, 29)),
            array('$HTTP_POST_FILES', array(9, 30)),
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
        $file = $this->sniffFile(self::TEST_FILE, '5.4');
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
            // Issue #268 - class properties named after long array variables.
            array(14),
            array(15),
            array(16),
            array(17),
            array(18),
            array(19),
            array(20),

            array(33),
            array(34),
            array(35),
            array(36),
            array(37),
            array(38),
            array(39),
        );
    }

}
