<?php
/**
 * Parameter type of the array_reduce() $initial param sniff test file.
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Parameter type of the array_reduce() $initial param sniff tests.
 *
 * @group newArrayReduceInitialType
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\NewArrayReduceInitialTypeSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewArrayReduceInitialTypeUnitTest extends BaseSniffTest
{

    const TEST_FILE = 'Sniffs/FunctionParameters/ArrayReduceInitialTypeTestCases.inc';

    /**
     * testArrayReduceInitialType
     *
     * @dataProvider dataArrayReduceInitialType
     *
     * @param int  $line    Line number where the error should occur.
     * @param bool $isError Whether an error or a warning is expected.
     *                      Defaults to `true` (= error).
     *
     * @return void
     */
    public function testArrayReduceInitialType($line, $isError = true)
    {
        $file  = $this->sniffFile(self::TEST_FILE, '5.2');
        $error = 'Passing a non-integer as the value for $initial to array_reduce() is not supported in PHP 5.2 or lower.';

        if ($isError === true) {
            $this->assertError($file, $line, $error);
        } else {
            $this->assertWarning($file, $line, $error);
        }
    }

    /**
     * dataArrayReduceInitialType
     *
     * @see testArrayReduceInitialType()
     *
     * @return array
     */
    public function dataArrayReduceInitialType()
    {
        return array(
            array(16),

            array(19, false),
            array(20, false),
            array(21, false),
            array(22, false),
            array(23, false),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');

        // No errors expected on the first 14 lines.
        for ($line = 1; $line <= 14; $line++) {
            $this->assertNoViolation($file, $line);
        }
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertNoViolation($file);
    }
}
