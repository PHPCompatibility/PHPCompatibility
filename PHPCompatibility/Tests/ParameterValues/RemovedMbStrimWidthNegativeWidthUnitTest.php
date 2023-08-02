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

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedMbStrimWidthNegativeWidth sniff.
 *
 * @group removedMbStrimWidthNegativeWidth
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedMbStrimWidthNegativeWidthSniff
 *
 * @since 10.0.0
 */
final class RemovedMbStrimWidthNegativeWidthUnitTest extends BaseSniffTestCase
{

    /**
     * Verify passing a negative $width to mb_strimwidth() is correctly detected.
     *
     * @dataProvider dataRemovedMbStrimWidthNegativeWidth
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testRemovedMbStrimWidthNegativeWidth($line)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertWarning($file, $line, 'Passing a negative $width to mb_strimwidth() is deprecated since PHP 8.3.');
    }

    /**
     * Data provider.
     *
     * @see testRemovedMbStrimWidthNegativeWidth()
     *
     * @return array
     */
    public static function dataRemovedMbStrimWidthNegativeWidth()
    {
        return [
            [40],
            [43],
        ];
    }


    /**
     * Test that there are no false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public static function dataNoFalsePositives()
    {
        $data = [];

        // No errors expected on the first 36 lines.
        for ($line = 1; $line <= 36; $line++) {
            $data[] = [$line];
        }

        return $data;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.2');
        $this->assertNoViolation($file);
    }
}
