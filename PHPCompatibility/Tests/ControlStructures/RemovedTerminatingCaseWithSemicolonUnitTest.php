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
 * Test the RemovedTerminatingCaseWithSemicolon sniff.
 *
 * @group removedTerminatingCaseWithSemicolon
 * @group controlStructures
 *
 * @covers \PHPCompatibility\Sniffs\ControlStructures\RemovedTerminatingCaseWithSemicolonSniff
 *
 * @since 10.0.0
 */
final class RemovedTerminatingCaseWithSemicolonUnitTest extends BaseSniffTestCase
{

    /**
     * Verify the use of semicolon for a switch-case statement is flagged correctly.
     *
     * @dataProvider dataRemovedTerminatingCaseWithSemicolon
     *
     * @param int    $line   The line number.
     * @param string $insert Optional. Text snippet to insert into the error message.
     *
     * @return void
     */
    public function testRemovedTerminatingCaseWithSemicolonn($line, $insert = '')
    {
        $file = $this->sniffFile(__FILE__, '8.5');
        $this->assertWarning($file, $line, "Terminating a switch case statement with a{$insert} semicolon is deprecated since PHP 8.5.");
    }

    /**
     * Data provider.
     *
     * @see testRemovedTerminatingCaseWithSemicolon()
     *
     * @return array<array<int|string>>
     */
    public static function dataRemovedTerminatingCaseWithSemicolon()
    {
        return [
            [73],
            [74],
            [75],
            [77],
            [79],
            [83],
            [87],
            [89],
            [95],
            [96],
            [99],
            [102],
            [104],
            [106],
            [111],
            [117, 'n implied'],
            [120],
            [123, 'n implied'],
            [127, 'n implied'],
            [130, 'n implied'],
            [136, 'n implied'],
            [141],
            [146, 'n implied'],
            [151, 'n implied'],
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
        $file = $this->sniffFile(__FILE__, '8.5');
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

        // No errors expected on the first 68 lines.
        for ($line = 1; $line <= 68; $line++) {
            $cases[] = [$line];
        }

        return $cases;
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
}
