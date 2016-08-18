<?php
/**
 * Functions can not have multiple parameters with the same name since PHP 7.0 test file
 *
 * @package PHPCompatibility
 */


/**
 * Functions can not have multiple parameters with the same name since PHP 7.0 sniff test
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class ForbiddenFunctionParametersWithSameNameSniffTest extends BaseSniffTest
{
    /**
     * testSettingTestVersion
     *
     * @group forbiddenFunctionParamsSameName
     *
     * @return void
     */
    public function testSettingTestVersion()
    {
        $file = $this->sniffFile('sniff-examples/forbidden_function_parameters_with_same_name.php', '5.6');
        $this->assertNoViolation($file, 3);
        
        $file = $this->sniffFile('sniff-examples/forbidden_function_parameters_with_same_name.php', '7.0');
        $this->assertError($file, 3, 'Functions can not have multiple parameters with the same name since PHP 7.0');
    }
}

