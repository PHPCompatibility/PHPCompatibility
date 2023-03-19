<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2021 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the RemovedVersionCompareOperators sniff.
 *
 * @group removedVersionCompareOperators
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedVersionCompareOperatorsSniff
 *
 * @since 10.0.0
 */
class RemovedVersionCompareOperatorsUnitTest extends BaseSniffTest
{

    /**
     * Verify that version compare operators for which support has been removed, get flagged when used.
     *
     * @dataProvider dataRemovedVersionCompareOperators
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testRemovedVersionCompareOperators($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertError($file, $line, 'version_compare() no longer supports operator abbreviations since PHP 8.1.');
    }

    /**
     * Data Provider
     *
     * @see testRemovedVersionCompareOperators()
     *
     * @return array
     */
    public static function dataRemovedVersionCompareOperators()
    {
        return [
            [44],
            [45],
            [46],
            [47],
            [48],
            [52],
            [53],
        ];
    }


    /**
     * Verify there are no false positives on code this sniff should ignore.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1');
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

        // No errors expected on the first 42 lines.
        for ($line = 1; $line <= 42; $line++) {
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
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertNoViolation($file);
    }
}
