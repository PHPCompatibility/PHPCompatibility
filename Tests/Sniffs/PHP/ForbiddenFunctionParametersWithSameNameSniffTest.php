<?php
/**
 * Functions can not have multiple parameters with the same name since PHP 7.0 test file
 *
 * @package PHPCompatibility
 */


/**
 * Functions can not have multiple parameters with the same name since PHP 7.0 sniff test
 *
 * @group forbiddenFunctionParametersWithSameName
 * @group functionDeclarations
 *
 * @covers PHPCompatibility_Sniffs_PHP_ForbiddenFunctionParametersWithSameNameSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class ForbiddenFunctionParametersWithSameNameSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/forbidden_function_parameters_with_same_name.php';


    /**
     * testSettingTestVersion
     *
     * @return void
     */
    public function testSettingTestVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertNoViolation($file, 3);

        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertError($file, 3, 'Functions can not have multiple parameters with the same name since PHP 7.0');
    }

    /**
     * testNoViolation
     *
     * @dataProvider dataNoViolation
     *
     * @param int $line Line number with valid code.
     *
     * @return void
     */
    public function testNoViolation($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataNoViolation
     *
     * @see testNoViolation()
     *
     * @return array
     */
    public function dataNoViolation() {
        return array(
            array(5),
            array(8),
        );
    }
}

