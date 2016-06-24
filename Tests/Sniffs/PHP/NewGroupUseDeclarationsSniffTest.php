<?php
/**
 * New use group declaration sniff tests
 *
 * @package PHPCompatibility
 */


/**
 * New use group declaration sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewGroupUseDeclarationsSniffTest extends BaseSniffTest
{
    /**
     * Test use group declaration
     *
     * @return void
     */
    public function testUseGroupDeclaration()
    {
        $file = $this->sniffFile('sniff-examples/new_group_use_declarations.php', '5.6');
        $this->assertNoViolation($file, 4);
        $this->assertNoViolation($file, 5);
        $this->assertNoViolation($file, 6);
        $this->assertNoViolation($file, 8);
        $this->assertNoViolation($file, 9);
        $this->assertNoViolation($file, 10);
        $this->assertError($file, 13, "Group use declarations are not allowed in PHP 5.6 or earlier");
        $this->assertError($file, 14, "Group use declarations are not allowed in PHP 5.6 or earlier");

        $file = $this->sniffFile('sniff-examples/new_group_use_declarations.php', '7.0');
        $this->assertNoViolation($file, 4);
        $this->assertNoViolation($file, 5);
        $this->assertNoViolation($file, 6);
        $this->assertNoViolation($file, 8);
        $this->assertNoViolation($file, 9);
        $this->assertNoViolation($file, 10);
        $this->assertNoViolation($file, 13);
        $this->assertNoViolation($file, 14);
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
}
