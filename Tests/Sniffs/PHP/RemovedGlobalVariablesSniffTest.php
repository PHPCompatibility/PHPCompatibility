<?php
/**
 * Removed global variables sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Removed global variables sniff tests
 *
 * @group removedGlobalVariables
 * @group superglobals
 *
 * @covers PHPCompatibility_Sniffs_PHP_RemovedGlobalVariablesSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class RemovedGlobalVariablesSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/removed_global_variables.php';

    /**
     * testRemovedGlobalVariables
     *
     * @dataProvider dataRemovedGlobalVariables
     *
     * @param string $varName      The name of the removed global variable.
     * @param string $deprecatedIn The PHP version in which the global variable was deprecated.
     * @param string $removedIn    The PHP version in which the global variable was removed.
     * @param array  $lines        The line numbers in the test file which apply to this variable.
     * @param string $alternative  What to use as an alternative.
     * @param string $okVersion    A PHP version in which the global variable was ok to be used.
     *
     * @return void
     */
    public function testRemovedGlobalVariables($varName, $deprecatedIn, $removedIn, $lines, $alternative, $okVersion)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $file  = $this->sniffFile(self::TEST_FILE, $deprecatedIn);
        $error = "Global variable '$" . $varName . "' is deprecated since PHP {$deprecatedIn}; Use {$alternative} instead";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }

        $file  = $this->sniffFile(self::TEST_FILE, $removedIn);
        $error = "Global variable '$" . $varName . "' is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}; Use {$alternative} instead";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testRemovedGlobalVariables()
     *
     * @return array
     */
    public function dataRemovedGlobalVariables()
    {
        return array(
            array('HTTP_POST_VARS', '5.3', '5.4', array(9, 31), '$_POST', '5.2'),
            array('HTTP_GET_VARS', '5.3', '5.4', array(10, 32, 51), '$_GET', '5.2'),
            array('HTTP_ENV_VARS', '5.3', '5.4', array(11, 33, 52), '$_ENV', '5.2'),
            array('HTTP_SERVER_VARS', '5.3', '5.4', array(12, 34), '$_SERVER', '5.2'),
            array('HTTP_COOKIE_VARS', '5.3', '5.4', array(13, 35), '$_COOKIE', '5.2'),
            array('HTTP_SESSION_VARS', '5.3', '5.4', array(14, 36), '$_SESSION', '5.2'),
            array('HTTP_POST_FILES', '5.3', '5.4', array(15, 37), '$_FILES', '5.2'),

            array('HTTP_RAW_POST_DATA', '5.6', '7.0', array(3, 38, 53), 'php://input', '5.5'),
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
            // Variable names are case-sensitive.
            array(5),
            array(6),

            // Issue #268 - class properties named after long array variables.
            array(20),
            array(21),
            array(22),
            array(23),
            array(24),
            array(25),
            array(26),
            array(27),

            array(41),
            array(42),
            array(43),
            array(44),
            array(45),
            array(46),
            array(47),
            array(48),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2'); // Low version below the first deprecation.
        $this->assertNoViolation($file);
    }

}
