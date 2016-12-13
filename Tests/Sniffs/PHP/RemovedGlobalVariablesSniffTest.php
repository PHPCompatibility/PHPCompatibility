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
class RemovedGlobalVariablesSniffTest  extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/removed_global_variables.php';

    /**
     * testRemovedGlobalVariables
     *
     * @dataProvider dataRemovedGlobalVariables
     *
     * @param string $varName The name of the removed global variable.
     * @param int    $line    The line number where the error is expected.
     *
     * @return void
     */
    public function testRemovedGlobalVariables($varName, $line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.5');
        $this->assertNoViolation($file, $line);

        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertWarning($file, $line, "Global variable '$" . $varName . "' is deprecated since PHP 5.6; Use php://input instead");

        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertError($file, $line, "Global variable '$" . $varName . "' is deprecated since PHP 5.6 and removed since PHP 7.0; Use php://input instead");
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
            array('HTTP_RAW_POST_DATA', 3),
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
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
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
        );
    }
}
