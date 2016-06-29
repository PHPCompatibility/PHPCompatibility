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
        $this->assertNoViolation($file, 5);
        $this->assertNoViolation($file, 7);
        $this->assertError($file, 9, "The function dirname does not have a parameter depth in PHP version 5.6 or earlier");

        $file = $this->sniffFile('sniff-examples/new_function_parameter.php', '7.0');
        $this->assertNoViolation($file, 3);
        $this->assertNoViolation($file, 5);
        $this->assertNoViolation($file, 7);
        $this->assertNoViolation($file, 9);
    }
    
    /**
     * Test unserialize() options parameter
     *
     * @return void
     */
    public function testUnserializeOptions()
    {
        $file = $this->sniffFile('sniff-examples/new_function_parameter.php', '5.6');
        $this->assertError($file, 11, "The function unserialize does not have a parameter options in PHP version 5.6 or earlier");
    
        $file = $this->sniffFile('sniff-examples/new_function_parameter.php', '7.0');
        $this->assertNoViolation($file, 11);
    }

    /**
     * Test session_start() options parameter
     *
     * @return void
     */
    public function testSessionStartOptions()
    {
        $file = $this->sniffFile('sniff-examples/new_function_parameter.php', '5.6');
        $this->assertError($file, 13, "The function session_start does not have a parameter options in PHP version 5.6 or earlier");
    
        $file = $this->sniffFile('sniff-examples/new_function_parameter.php', '7.0');
        $this->assertNoViolation($file, 13);
        
    }
    
    /**
     * Test strstr() before_needle parameter
     *
     * @return void
     */
    public function testStrstrBeforeNeedleOptions()
    {
        $file = $this->sniffFile('sniff-examples/new_function_parameter.php', '5.2');
        $this->assertError($file, 15, "The function strstr does not have a parameter before_needle in PHP version 5.2 or earlier");
    
        $file = $this->sniffFile('sniff-examples/new_function_parameter.php', '5.3');
        $this->assertNoViolation($file, 15);
    
    }
    
}
