<?php
/**
 * Passing `null` to get_class() sniff test file.
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Passing `null` to get_class() sniff tests.
 *
 * @group forbiddenGetClassNull
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\ForbiddenGetClassNullSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class ForbiddenGetClassNullUnitTest extends BaseSniffTest
{

    const TEST_FILE = 'Sniffs/FunctionParameters/GetClassNullTestCases.inc';

    /**
     * testGetClassNull
     *
     * @dataProvider dataGetClassNull
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testGetClassNull($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.2');
        $this->assertError($file, $line, 'Passing "null" as the $object to get_class() is not allowed since PHP 7.2.');
    }

    /**
     * dataGetClassNull
     *
     * @see testGetClassNull()
     *
     * @return array
     */
    public function dataGetClassNull()
    {
        return array(
            array(11),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.2');

        // No errors expected on the first 9 lines.
        for ($line = 1; $line <= 9; $line++) {
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
        $file = $this->sniffFile(self::TEST_FILE, '7.1');
        $this->assertNoViolation($file);
    }
}
