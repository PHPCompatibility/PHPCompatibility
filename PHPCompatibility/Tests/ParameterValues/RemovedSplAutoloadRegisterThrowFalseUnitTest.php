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
 * Test the RemovedSplAutoloadRegisterThrowFalse sniff.
 *
 * @group removedSplAutoloadRegisterThrowFalse
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedSplAutoloadRegisterThrowFalseSniff
 *
 * @since 10.0.0
 */
class RemovedSplAutoloadRegisterThrowFalseUnitTest extends BaseSniffTest
{

    /**
     * Verify a warning is thrown when an explicit false is passed as the second parameter.
     *
     * @dataProvider dataRemovedSplAutoloadRegisterThrowFalse
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testRemovedSplAutoloadRegisterThrowFalse($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertWarning($file, $line, 'Explicitly passing "false" as the value for $throw to spl_autoload_register() is deprecated since PHP 8.0.');
    }

    /**
     * Data provider.
     *
     * @see testRemovedSplAutoloadRegisterThrowFalse()
     *
     * @return array
     */
    public function dataRemovedSplAutoloadRegisterThrowFalse()
    {
        return [
            [13],
        ];
    }


    /**
     * Verify the sniff does not throw false positives for valid code.
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '8.0');

        // No errors expected on the first 11 lines.
        for ($line = 1; $line <= 11; $line++) {
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
