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
 * Test the RemovedDeclareEncodingBlockMode sniff.
 *
 * @group removedDeclareEncodingBlockMode
 * @group controlStructures
 *
 * @covers \PHPCompatibility\Sniffs\ControlStructures\RemovedDeclareEncodingBlockModeSniff
 *
 * @since 10.0.0
 */
final class RemovedDeclareEncodingBlockModeUnitTest extends BaseSniffTestCase
{

    /**
     * Test that block mode for declare(encoding=...) is detected correctly.
     *
     * @dataProvider dataRemovedDeclareEncodingBlockMode
     *
     * @param int $line Line number where the message should occur.
     *
     * @return void
     */
    public function testRemovedDeclareEncodingBlockMode($line)
    {
        $file = $this->sniffFile(__FILE__, '8.7');
        $this->assertError($file, $line, 'An encoding declaration can not use block mode since PHP 8.7');
    }

    /**
     * Data provider.
     *
     * @return array<array<int>>
     */
    public static function dataRemovedDeclareEncodingBlockMode()
    {
        return [
            [36],
            [39],
            [43],
            [46],
        ];
    }


    /**
     * Verify the sniff does not throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.7');
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

        // No errors expected on the first 32 lines.
        for ($line = 1; $line <= 32; $line++) {
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
        $file = $this->sniffFile(__FILE__, '8.6');
        $this->assertNoViolation($file);
    }
}
