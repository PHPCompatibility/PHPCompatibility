<?php
/**
 * Parentheses around function parameters throws warning in PHP 7.0  
 *
 * @package PHPCompatibility
 */


/**
 * Parentheses around function parameters throws warning in PHP 7.0
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class ForbiddenParenthesisAroundFunctionParametersSniffTest extends BaseSniffTest
{
    /**
     * testSettingTestVersion
     *
     * @return void
     */
    public function testSettingTestVersion()
    {
        $file = $this->sniffFile('sniff-examples/forbidden_switch_with_multiple_default_blocks.php', '5.6');
        $this->assertNoViolation($file, 3);
        $this->assertNoViolation($file, 14);
        $this->assertNoViolation($file, 23);
        
        $file = $this->sniffFile('sniff-examples/forbidden_switch_with_multiple_default_blocks.php', '7.0');
        $this->assertError($file, 3, 'Switch statements can not have multiple default blocks since PHP 7.0');
        $this->assertNoViolation($file, 14);
        $this->assertNoViolation($file, 23);
    }
}

