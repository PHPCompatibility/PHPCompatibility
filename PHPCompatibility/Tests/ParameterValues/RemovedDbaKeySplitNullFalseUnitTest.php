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
 * Test the RemovedDbaKeySplitNullFalse sniff.
 *
 * @group removedDbaKeySplitNullFalse
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedDbaKeySplitNullFalseSniff
 *
 * @since 10.0.0
 */
final class RemovedDbaKeySplitNullFalseUnitTest extends BaseSniffTestCase
{

    /**
     * Test the sniff correctly detects null or false being passed as the $key parameter.
     *
     * @dataProvider dataRemovedDbaKeySplitNullFalse
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testRemovedDbaKeySplitNullFalse($line)
    {
        $file  = $this->sniffFile(__FILE__, '8.4');
        $error = 'Passing false or null as the $key to dba_key_split() is deprecated since PHP 8.4.';

        $this->assertWarning($file, $line, $error);

        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testRemovedDbaKeySplitNullFalse()
     *
     * @return array<array<int>>
     */
    public static function dataRemovedDbaKeySplitNullFalse()
    {
        return [
            [24],
            [25],
            [28],
            [30],
            [37],
            [38],
            [39],
            [42],
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
        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array<array<int>>
     */
    public static function dataNoFalsePositives()
    {
        $data = [];

        // No errors expected on the first 22 lines.
        for ($line = 1; $line <= 22; $line++) {
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
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file);
    }
}
