<?php
/**
 * Global with variable variables have been removed in PHP 7.0 sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Global with variable variables have been removed in PHP 7.0 sniff test file
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class ForbiddenGlobalVariableVariableSniffTest extends BaseSniffTest
{
    /**
     * Verify that checking for a specific version works
     *
     * @return void
     */
    public function testGlobalVariableVariable()
    {
        $file = $this->sniffFile('sniff-examples/forbidden_global_variable_variable.php', '5.6');
        $this->assertNoViolation($file, 3);
        
        $file = $this->sniffFile('sniff-examples/forbidden_global_variable_variable.php', '7.0');
        $this->assertError($file, 3, "Global with variable variables is not allowed since PHP 7.0");
    }
}
