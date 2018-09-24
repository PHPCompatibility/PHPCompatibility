<?php
/**
 * Deprecated/Removed Iconv encoding types sniff test file.
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Deprecated/Removed Iconv encoding types sniff tests.
 *
 * @group removedIconvEncoding
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedIconvEncodingSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class RemovedIconvEncodingUnitTest extends BaseSniffTest
{

    const TEST_FILE = 'Sniffs/FunctionParameters/IconvEncodingTestCases.inc';

    /**
     * testIconvEncoding
     *
     * @dataProvider dataIconvEncoding
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testIconvEncoding($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertWarning($file, $line, 'All previously accepted values for the $type parameter of iconv_set_encoding() have been deprecated since PHP 5.6.');
    }

    /**
     * dataIconvEncoding
     *
     * @see testIconvEncoding()
     *
     * @return array
     */
    public function dataIconvEncoding()
    {
        return array(
            array(14),
            array(15),
            array(16),
            array(17),
            array(18),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');

        // No errors expected on the first 10 lines.
        for ($line = 1; $line <= 10; $line++) {
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
        $file = $this->sniffFile(self::TEST_FILE, '5.5');
        $this->assertNoViolation($file);
    }
}
