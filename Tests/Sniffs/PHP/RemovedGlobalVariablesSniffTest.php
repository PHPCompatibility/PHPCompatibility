<?php
/**
 * Removed global variables sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Removed global variables sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class RemovedGlobalVariablesSniffTest  extends BaseSniffTest
{
    /**
     * HTTP_RAW_POST_DATA
     *
     * @return void
     */
    public function testHttpRawPostData()
    {
        $file = $this->sniffFile('sniff-examples/removed_global_variables.php', '5.5');
        $this->assertNoViolation($file);
        
        $file = $this->sniffFile('sniff-examples/removed_global_variables.php', '5.6');
        $this->assertWarning($file, 3, 'Global variable \'$HTTP_RAW_POST_DATA\' is deprecated since PHP 5.6 - use php://input instead');
        
        $file = $this->sniffFile('sniff-examples/removed_global_variables.php', '7.0');
        $this->assertError($file, 3, 'Global variable \'$HTTP_RAW_POST_DATA\' is deprecated since PHP 5.6 and removed since PHP 7.0 - use php://input instead');
    }
}
