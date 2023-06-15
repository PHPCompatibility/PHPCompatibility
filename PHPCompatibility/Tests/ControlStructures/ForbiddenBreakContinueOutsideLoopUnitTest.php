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

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the ForbiddenBreakContinueOutsideLoop sniff.
 *
 * @group forbiddenBreakContinueOutsideLoop
 * @group controlStructures
 *
 * @covers \PHPCompatibility\Sniffs\ControlStructures\ForbiddenBreakContinueOutsideLoopSniff
 *
 * @since 7.0.7
 */
class ForbiddenBreakContinueOutsideLoopUnitTest extends BaseSniffTest
{

    /**
     * testForbiddenBreakContinueOutsideLoop
     *
     * @dataProvider dataBreakContinueOutsideLoop
     *
     * @param int    $line  The line number.
     * @param string $found Either 'break' or 'continue'.
     *
     * @return void
     */
    public function testBreakContinueOutsideLoop($line, $found)
    {
        $file = $this->sniffFile(__FILE__, '5.4'); // Arbitrary pre-PHP7 version.
        $this->assertWarning($file, $line, "Using '{$found}' outside of a loop or switch structure is invalid");

        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertError($file, $line, "Using '{$found}' outside of a loop or switch structure is invalid and will throw a fatal error since PHP 7.0");
    }

    /**
     * Data provider.
     *
     * @see testBreakContinueOutsideLoop()
     *
     * @return array
     */
    public static function dataBreakContinueOutsideLoop()
    {
        return [
            [116, 'continue'],
            [118, 'continue'],
            [120, 'break'],
            [124, 'continue'],
            [128, 'break'],
            [131, 'continue'],
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
        $file = $this->sniffFile(__FILE__, '7.0');
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
        return [
            [8],
            [11],
            [17],
            [20],
            [26],
            [29],
            [36],
            [39],
            [47],
            [51],
            [54],
            [60],
            [63],
            [69],
            [72],
            [78],
            [81],
            [89],
            [93],
            [96],
            [103],
            [106],
        ];
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will throw a warning
     * on invalid use of the construct in pre-PHP 7 versions.
     */
}
