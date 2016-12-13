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
     * @param string $deprecatedIn The PHP version in which the variable became deprecated.
     * @param string $removedIn    The PHP version in which the variable was removed.
     * @param string $okVersion    A PHP version in which the variable was still ok to be used.
     *
     * @return void
     */
    public function testLongVariable($longVariable, $lines, $deprecatedIn, $removedIn, $okVersion)
    {
        $file = $this->sniffFile(self::TEST_FILE, $deprecatedIn);
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, "The use of long predefined variables has been deprecated in PHP {$deprecatedIn}; Found '{$longVariable}'");
        }

        $file = $this->sniffFile(self::TEST_FILE, $removedIn);
        foreach ($lines as $line) {
            $this->assertError($file, $line, "The use of long predefined variables has been deprecated in PHP {$deprecatedIn} and removed in PHP {$removedIn}; Found '{$longVariable}'");
        }

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
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
            array('$HTTP_POST_VARS', array(3, 24), '5.3', '5.4', '5.2'),
            array('$HTTP_GET_VARS', array(4, 25, 42), '5.3', '5.4', '5.2'),
            array('$HTTP_ENV_VARS', array(5, 26, 43), '5.3', '5.4', '5.2'),
            array('$HTTP_SERVER_VARS', array(6, 27), '5.3', '5.4', '5.2'),
            array('$HTTP_COOKIE_VARS', array(7, 28), '5.3', '5.4', '5.2'),
            array('$HTTP_SESSION_VARS', array(8, 29), '5.3', '5.4', '5.2'),
            array('$HTTP_POST_FILES', array(9, 30), '5.3', '5.4', '5.2'),
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
        $file = $this->sniffFile(self::TEST_FILE, '5.4');
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
