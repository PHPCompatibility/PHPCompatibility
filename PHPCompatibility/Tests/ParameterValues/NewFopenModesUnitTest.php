<?php
/**
 * Allowed values for the fopen() $mode parameter sniff test file.
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Sniffs\FunctionParameters;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Allowed values for the fopen() $mode parameter sniff tests.
 *
 * @group fopenMode
 * @group functionParameterValues
 *
 * @covers \PHPCompatibility\Sniffs\FunctionParameters\FopenModeSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class FopenModeSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'Sniffs/FunctionParameters/FopenModeTestCases.inc';

    /**
     * testFopenMode
     *
     * @dataProvider dataFopenMode
     *
     * @param int    $line           Line number where the error should occur.
     * @param string $mode           The fopen() $mode which should be detected.
     * @param string $errorVersion   The PHP version to use to test for the error.
     * @param string $okVersion      A PHP version in which the mode is valid.
     * @param string $displayVersion Optional PHP version which is shown in the error message
     *                               if different from the $errorVersion.
     *
     * @return void
     */
    public function testFopenMode($line, $mode, $errorVersion, $okVersion, $displayVersion = null)
    {
        $file  = $this->sniffFile(self::TEST_FILE, $errorVersion);
        $error = sprintf(
            'Passing "%s" as the $mode to fopen() is not supported in PHP %s or lower.',
            $mode,
            isset($displayVersion) ? $displayVersion : $errorVersion
        );
        $this->assertError($file, $line, $error);

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataFopenMode
     *
     * @see testFopenMode()
     *
     * @return array
     */
    public function dataFopenMode()
    {
        return array(
            array(9, 'e', '7.0', '7.1', '7.0.15'),
            array(10, 'c+', '5.2', '5.3', '5.2.5'),
            array(11, 'c', '5.2', '5.3', '5.2.5'),
            array(12, 'c', '5.2', '7.1', '5.2.5'), // High okVersion, to pass by the second violation.
            array(12, 'e', '7.0', '7.1', '7.0.15'),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2'); // Version below first new mode was added.
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return array(
            array(4),
            array(5),
            array(6),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.1'); // Above last new mode added.
        $this->assertNoViolation($file);
    }
}
