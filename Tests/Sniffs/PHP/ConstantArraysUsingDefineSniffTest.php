<?php
/**
 * Constant arrays using define in PHP 7.0 sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Constant arrays using define in PHP 7.0 sniff test file
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class ConstantArraysUsingDefineSniffTest  extends BaseSniffTest
{
    /**
     * Verify that checking for a specific version works
     *
     * @return void
     */
    public function testConstantArraysUsingDefine()
    {
        $file = $this->sniffFile('sniff-examples/constant_arrays_using_define.php', '7.0');
        $this->assertNoViolation($file, 3);
        $this->assertNoViolation($file, 9);
        $this->assertNoViolation($file, 15);
        
        $file = $this->sniffFile('sniff-examples/constant_arrays_using_define.php', '5.6');
        $this->assertError($file, 3, "Constant arrays using define are not allowed in PHP 5.6 or earlier");
        $this->assertError($file, 9, "Constant arrays using define are not allowed in PHP 5.6 or earlier");
        $this->assertNoViolation($file, 15);
    }
}
