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
     * testFunctionParametersWithSameName
     *
     * @dataProvider dataFunctionParametersWithSameName
     *
     * @param int $line Line number.
     *
     * @return void
     */
    public function testFunctionParametersWithSameName($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertError($file, $line, 'Functions can not have multiple parameters with the same name since PHP 7.0');
    }

    /**
     * dataFunctionParametersWithSameName
     *
     * @see testFunctionParametersWithSameName()
     *
     * @return array
     */
    public function dataFunctionParametersWithSameName()
    {
        return array(
            array(3),
            array(7),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number with valid code.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataNoFalsePositives
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return array(
            array(5),
            array(10),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertNoViolation($file);
    }

}
