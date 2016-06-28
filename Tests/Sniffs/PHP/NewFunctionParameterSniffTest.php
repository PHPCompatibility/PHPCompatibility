<?php
/**
 * New Functions Parameter Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New Functions Parameter Sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewFunctionParameterSniffTest extends BaseSniffTest
{
    /**
     * Test dirname() depth parameter
     *
     * @return void
     */
    public function testDirnameDepth()
    {
        $file = $this->sniffFile('sniff-examples/new_function_parameter.php', '5.6');
        $this->assertError($file, 3, "The function dirname does not have a parameter depth in PHP version 5.6 or earlier");

        $file = $this->sniffFile('sniff-examples/new_function_parameter.php', '7.0');
        $this->assertNoViolation($file, 3);
    }
    
    /**
     * Test unserialize() options parameter
     *
     * @return void
     */
    public function testUnserializeOptions()
    {
        $file = $this->sniffFile('sniff-examples/new_function_parameter.php', '5.6');
        $this->assertError($file, 5, "The function unserialize does not have a parameter options in PHP version 5.6 or earlier");
    
        $file = $this->sniffFile('sniff-examples/new_function_parameter.php', '7.0');
        $this->assertNoViolation($file, 5);
    }

    /**
     * Test session_start() options parameter
     *
     * @return void
     */
    public function testSessionStartOptions()
    {
        $file = $this->sniffFile('sniff-examples/new_function_parameter.php', '5.6');
        $this->assertError($file, 7, "The function session_start does not have a parameter options in PHP version 5.6 or earlier");
    
        $file = $this->sniffFile('sniff-examples/new_function_parameter.php', '7.0');
        $this->assertNoViolation($file, 7);
    }
}
