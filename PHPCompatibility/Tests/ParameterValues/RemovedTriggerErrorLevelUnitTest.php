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
 * Test the RemovedTriggerErrorLevel sniff.
 *
 * @group removedTriggerErrorLevel
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedTriggerErrorLevelSniff
 *
 * @since 10.0.0
 */
final class RemovedTriggerErrorLevelUnitTest extends BaseSniffTestCase
{

    /**
     * Test the sniff correctly detects passing E_USER_ERROR to trigger_error().
     *
     * @dataProvider dataRemovedTriggerErrorLevel
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testRemovedTriggerErrorLevel($line)
    {
        $file  = $this->sniffFile(__FILE__, '8.4');
        $error = 'Passing E_USER_ERROR to trigger_error() is deprecated since 8.4. Throw an exception or call exit with a string message instead.';

        $this->assertWarning($file, $line, $error);

        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testRemovedTriggerErrorLevel()
     *
     * @return array<array<int>>
     */
    public static function dataRemovedTriggerErrorLevel()
    {
        return [
            [29],
            [30],
            [34],
            [36],
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

        // No errors expected on the first 27 lines.
        for ($line = 1; $line <= 27; $line++) {
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
