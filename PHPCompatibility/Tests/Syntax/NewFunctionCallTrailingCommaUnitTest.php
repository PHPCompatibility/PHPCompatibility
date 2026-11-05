<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Syntax;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewFunctionCallTrailingComma sniff.
 *
 * @group newFunctionCallTrailingComma
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\NewFunctionCallTrailingCommaSniff
 *
 * @since 8.2.0
 */
final class NewFunctionCallTrailingCommaUnitTest extends BaseSniffTestCase
{

    /**
     * Verify that trailing commas in function calls, call to isset() and unset() are flagged correctly.
     *
     * @dataProvider dataTrailingComma
     *
     * @param int    $line The line number.
     * @param string $type The type detected.
     *
     * @return void
     */
    public function testTrailingComma($line, $type = 'function calls')
    {
        $file = $this->sniffFile(__FILE__, '7.2');
        $this->assertError($file, $line, "Trailing commas are not allowed in {$type} in PHP 7.2 or earlier");

        $file = $this->sniffFile(__FILE__, '7.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testTrailingComma()
     *
     * @return array
     */
    public static function dataTrailingComma()
    {
        return [
            [15, 'calls to unset()'],
            [16, 'calls to isset()'],
            [21, 'calls to unset()'],
            [27], // x2.
            [33],
            [36],
            [38],
            [40],
            [44],
            [47],
            [49],
            [52],
            [62],
            [65],
            [95],
            [96],
            [97],
            [98],
            [99],
            [105],
            [112],
            [115],
            [116],
            [117],
        ];
    }


    /**
     * Verify that trailing commas in calls to exit() and die() are flagged correctly.
     *
     * @dataProvider dataTrailingCommaExit
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testTrailingCommaExit($line)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertError($file, $line, 'Trailing commas are not allowed in calls to exit() or die() in PHP 8.3 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testTrailingCommaExit()
     *
     * @return array
     */
    public static function dataTrailingCommaExit()
    {
        return [
            [126, 'calls to exit() or die()'],
            [127, 'calls to exit() or die()'],
        ];
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
        $file = $this->sniffFile(__FILE__, '7.2');
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

        // No errors expected on the first 10 lines.
        for ($line = 1; $line <= 10; $line++) {
            $data[] = [$line];
        }

        $data[] = [51];
        $data[] = [58];
        $data[] = [59];
        $data[] = [108];

        for ($line = 66; $line <= 93; $line++) {
            $data[] = [$line];
        }

        for ($line = 100; $line <= 103; $line++) {
            $data[] = [$line];
        }

        for ($line = 119; $line <= 124; $line++) {
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
        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertNoViolation($file);
    }

    /**
     * Ignore trailing attribute argument commas, including the existing regression fixture.
     *
     * @return void
     */
    public function testAttributeTrailingCommas()
    {
        foreach (['7.2', '7.2-8.0'] as $version) {
            $file = $this->sniffFile(__FILE__, $version);
            foreach ([108, 130, 133, 134, 135, 138] as $line) {
                $this->assertNoViolation($file, $line);
            }
            $this->assertError($file, 140, 'Trailing commas are not allowed in function calls in PHP 7.2 or earlier');
        }
    }

    /**
     * Preserve diagnostics for trailing commas introduced in PHP 8.4.
     *
     * @return void
     */
    public function testAttributeExitTrailingCommaRemainsReported()
    {
        foreach (['8.3', '7.2-8.4'] as $version) {
            $file = $this->sniffFile(__FILE__, $version);
            $this->assertError($file, 144, 'Trailing commas are not allowed in calls to exit() or die() in PHP 8.3 or earlier');
        }

        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertNoViolation($file, 144);
    }
}
