<?php
/**
 * Global with variable variables have been removed in PHP 7.0 sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Sniffs\PHP;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Global with variable variables have been removed in PHP 7.0 sniff test file
 *
 * @group forbiddenGlobalVariableVariable
 * @group variableVariables
 *
 * @covers \PHPCompatibility\Sniffs\PHP\ForbiddenGlobalVariableVariableSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Wim Godden <wim@cu.be>
 */
class ForbiddenGlobalVariableVariableSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/forbidden_global_variable_variable.php';

    /**
     * testGlobalVariableVariable
     *
     * @dataProvider dataGlobalVariableVariable
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testGlobalVariableVariable($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertError($file, $line, 'Global with variable variables is not allowed since PHP 7.0');
    }

    /**
     * Data provider dataGlobalVariableVariable.
     *
     * @see testGlobalVariableVariable()
     *
     * @return array
     */
    public function dataGlobalVariableVariable()
    {
        return array(
            array(6),
            array(9),
            array(11),
            array(28),
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
            array(19),
            array(20),
            array(21),
            array(22),
            array(23),
            array(24),
            array(25),
            array(31),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertNoViolation($file);
    }

}
