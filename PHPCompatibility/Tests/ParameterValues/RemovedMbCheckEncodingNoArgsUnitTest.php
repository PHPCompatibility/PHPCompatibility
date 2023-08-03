<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2022 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedMbCheckEncodingNoArgs sniff.
 *
 * @group removedMbCheckEncodingNoArgs
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedMbCheckEncodingNoArgsSniff
 *
 * @since 10.0.0
 */
class RemovedMbCheckEncodingNoArgsUnitTest extends BaseSniffTestCase
{

    /**
     * Test receiving an expected warning for calling mb_check_encoding() without arguments.
     *
     * @dataProvider dataRemovedMbCheckEncodingNoArgs
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testRemovedMbCheckEncodingNoArgs($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertWarning($file, $line, 'without arguments is deprecated since PHP 8.1.');
    }

    /**
     * Data provider.
     *
     * @see testRemovedMbCheckEncodingNoArgs()
     *
     * @return array
     */
    public static function dataRemovedMbCheckEncodingNoArgs()
    {
        return [
            [12],
            [13],
        ];
    }


    /**
     * Test that there are no false positives for valid code.
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '8.1');

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
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertNoViolation($file);
    }
}
