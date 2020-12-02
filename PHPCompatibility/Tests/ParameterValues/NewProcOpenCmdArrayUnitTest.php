<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewProcOpenCmdArray sniff.
 *
 * @group newProcOpenCmdArray
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\NewProcOpenCmdArraySniff
 *
 * @since 9.3.0
 */
class NewProcOpenCmdArrayUnitTest extends BaseSniffTest
{

    /**
     * testNewProcOpenCmdArray
     *
     * @dataProvider dataNewProcOpenCmdArray
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testNewProcOpenCmdArray($line)
    {
        $file  = $this->sniffFile(__FILE__, '7.3');
        $error = 'The proc_open() function did not accept $command to be passed in array format in PHP 7.3 and earlier.';

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testNewProcOpenCmdArray()
     *
     * @return array
     */
    public function dataNewProcOpenCmdArray()
    {
        return [
            [18],
            [20],
            [30],
            [32],
            [61],
        ];
    }


    /**
     * testInvalidProcOpenCmdArray
     *
     * @dataProvider dataInvalidProcOpenCmdArray
     *
     * @param int  $line      Line number where the error should occur.
     * @param bool $itemValue The parameter value detected.
     *
     * @return void
     */
    public function testInvalidProcOpenCmdArray($line, $itemValue)
    {
        $file  = $this->sniffFile(__FILE__, '7.4');
        $error = 'When passing the $command parameter to proc_open() as an array, PHP will take care of any necessary argument escaping. Found: ' . $itemValue;

        $this->assertWarning($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testInvalidProcOpenCmdArray()
     *
     * @return array
     */
    public function dataInvalidProcOpenCmdArray()
    {
        return [
            [30, 'escapeshellarg($echo)'],
            [34, '\'--standard=\' . escapeshellarg($standard)'],
            [35, '\'./path/to/\' . escapeshellarg($file)'],
            [61, 'escapeshellarg($echo)'],
        ];
    }


    /**
     * Test the sniff does not throw false positives.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param string $testVersion The testVersion to use.
     *
     * @return void
     */
    public function testNoFalsePositives($testVersion)
    {
        $file = $this->sniffFile(__FILE__, $testVersion);

        // No errors expected on the first 16 lines.
        for ($line = 1; $line <= 16; $line++) {
            $this->assertNoViolation($file, $line);
        }

        // Nor on line 41 to 51.
        for ($line = 41; $line <= 51; $line++) {
            $this->assertNoViolation($file, $line);
        }
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
        return [
            ['7.3'],
            ['7.4'],
        ];
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will throw warnings/errors
     * about independently of the testVersion.
     */
}
