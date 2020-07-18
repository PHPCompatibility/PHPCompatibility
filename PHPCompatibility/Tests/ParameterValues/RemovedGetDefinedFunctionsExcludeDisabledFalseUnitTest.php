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
 * Test the RemovedGetDefinedFunctionsExcludeDisabledFalse sniff.
 *
 * @group removedGetDefinedFunctionsExcludeDisabledFalse
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedGetDefinedFunctionsExcludeDisabledFalseSniff
 *
 * @since 10.0.0
 */
class RemovedGetDefinedFunctionsExcludeDisabledFalseUnitTest extends BaseSniffTest
{

    /**
     * testRemovedGetDefinedFunctionsExcludeDisabledFalse
     *
     * @dataProvider dataRemovedGetDefinedFunctionsExcludeDisabledFalse
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testRemovedGetDefinedFunctionsExcludeDisabledFalse($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertWarning($file, $line, 'Explicitly passing "false" as the value for $exclude_disabled to get_defined_functions() is deprecated since PHP 8.0.');
    }

    /**
     * Data provider.
     *
     * @see testRemovedGetDefinedFunctionsExcludeDisabledFalse()
     *
     * @return array
     */
    public function dataRemovedGetDefinedFunctionsExcludeDisabledFalse()
    {
        return array(
            array(11),
        );
    }


    /**
     * Verify the sniff does not throw false positives for valid code.
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '8.0');

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
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertNoViolation($file);
    }
}
