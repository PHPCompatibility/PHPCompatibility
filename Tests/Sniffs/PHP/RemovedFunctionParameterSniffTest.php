<?php
/**
 * Removed Functions Parameter Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Removed Functions Parameter Sniff test file
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class RemovedFunctionParameterSniffTest extends BaseSniffTest
{
    /**
     * Test mktime() is_dst parameter
     *
     * @return void
     */
    public function testMktimeIsdst()
    {
        $file = $this->sniffFile('sniff-examples/removed_function_parameter.php', '5.0');
        $this->assertNoViolation($file, 3);
        
        $file = $this->sniffFile('sniff-examples/removed_function_parameter.php', '7.0');
        $this->assertError($file, 3, "The function mktime does not have a parameter is_dst in PHP version 7.0 or later");
    }
    
    /**
     * Test gmmktime() is_dst parameter
     *
     * @return void
     */
    public function testGmmktimeIsdst()
    {
        $file = $this->sniffFile('sniff-examples/removed_function_parameter.php', '5.6');
        $this->assertNoViolation($file, 5);
    
        $file = $this->sniffFile('sniff-examples/removed_function_parameter.php', '7.0');
        $this->assertError($file, 5, "The function gmmktime does not have a parameter is_dst in PHP version 7.0 or later");
    }
}
