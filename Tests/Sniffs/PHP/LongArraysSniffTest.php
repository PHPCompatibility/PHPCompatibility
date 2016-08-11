<?php
/**
 * Long Arrays Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Long Arrays Sniff tests
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
        $file = $this->sniffFile(self::TEST_FILE);
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, "The use of long predefined variables has been deprecated in {$deprecatedIn} and removed in {$removedIn}; Found '{$longVariable}'");
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
            array('$HTTP_POST_VARS', array(3), '5.3', '5.4', '5.2'),
            array('$HTTP_GET_VARS', array(4), '5.3', '5.4', '5.2'),
            array('$HTTP_ENV_VARS', array(5), '5.3', '5.4', '5.2'),
            array('$HTTP_SERVER_VARS', array(6), '5.3', '5.4', '5.2'),
            array('$HTTP_COOKIE_VARS', array(7), '5.3', '5.4', '5.2'),
            array('$HTTP_SESSION_VARS', array(8), '5.3', '5.4', '5.2'),
            array('$HTTP_POST_FILES', array(9), '5.3', '5.4', '5.2'),
        );
    }

}
