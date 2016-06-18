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
        $file = $this->sniffFile('sniff-examples/forbidden_parenthesis_around_function_parameters.php', '5.6');
        $this->assertNoViolation($file, 3);
        $this->assertNoViolation($file, 5);
        $this->assertNoViolation($file, 7);
        $this->assertNoViolation($file, 9);
        
        $file = $this->sniffFile('sniff-examples/forbidden_parenthesis_around_function_parameters.php', '7.0');
        $this->assertWarning($file, 3, 'Parentheses around function parameters throws warning in PHP 7.0');
        $this->assertWarning($file, 5, 'Parentheses around function parameters throws warning in PHP 7.0');
        $this->assertWarning($file, 7, 'Parentheses around function parameters throws warning in PHP 7.0');
        $this->assertNoViolation($file, 9);
    }
}

