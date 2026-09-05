<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ControlStructures;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedReturnFromFinally sniff.
 *
 * @group removedReturnFromFinally
 * @group controlStructures
 * @group exceptions
 *
 * @covers \PHPCompatibility\Sniffs\ControlStructures\RemovedReturnFromFinallySniff
 *
 * @since 10.0.0
 */
final class RemovedReturnFromFinallyUnitTest extends BaseSniffTestCase
{

    /**
     * Verify return statements within a finally block are flagged correctly.
     *
     * @dataProvider dataRemovedReturnFromFinally
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testRemovedReturnFromFinally($line)
    {
        $file = $this->sniffFile(__FILE__, '8.6');
        $this->assertWarning($file, $line, 'Passing a return value from a finally block is deprecated since PHP 8.6.');
    }

    /**
     * Data provider.
     *
     * @return array<array<int|string>>
     */
    public static function dataRemovedReturnFromFinally()
    {
        return [
            [107],
            [115],
            [124],
            [126],
            [137],
            [139],
            [153],
        ];
    }


    /**
     * Verify the sniff does not throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.6');
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
        $cases = [];

        // No errors expected on the first 94 lines.
        for ($line = 1; $line <= 94; $line++) {
            $cases[] = [$line];
        }

        $cases[] = [103];

        return $cases;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.5');
        $this->assertNoViolation($file);
    }
}
