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

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the RemovedCurlyBraceArrayAccess sniff.
 *
 * @group removedCurlyBraceArrayAccess
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\RemovedCurlyBraceArrayAccessSniff
 *
 * @since 9.3.0
 */
class RemovedCurlyBraceArrayAccessUnitTest extends BaseSniffTest
{

    /**
     * testRemovedCurlyBraceArrayAccess
     *
     * @dataProvider dataRemovedCurlyBraceArrayAccess
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testRemovedCurlyBraceArrayAccess($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertWarning($file, $line, 'Curly brace syntax for accessing array elements and string offsets has been deprecated in PHP 7.4');

        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, $line, 'Curly brace syntax for accessing array elements and string offsets has been deprecated in PHP 7.4 and removed in PHP 8.0');
    }

    /**
     * Data provider.
     *
     * @see testRemovedCurlyBraceArrayAccess()
     *
     * @return array
     */
    public function dataRemovedCurlyBraceArrayAccess()
    {
        return [
            [53],
            [54],
            [56],
            [57],
            [58],
            [60], // x2.
            [63],
            [64],
            [65], // x2.
            [68],
            [69],
            [71],
            [74],
            [79],
            [80],
            [84],
            [85],
            [90],
            [91],
            [92], // x2.
            [93],
            [95],
            [96],
            [98], // x2.
            [99], // x2.
            [100], // x2.
            [105],
            [106],
            [107], // x2.
            [108],
            [109],
            [110],
            [115],
            [116], // x2.
            [117],
            [120],
            [121],
            [126],
            [127], // x2.
            [128],
            [129],
            [132],
            [133],
            [136],
        ];
    }


    /**
     * testNoFalsePositives
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '7.4');

        // No errors expected on the first 49 lines.
        for ($line = 1; $line <= 49; $line++) {
            $this->assertNoViolation($file, $line);
        }

        // ...and on the last few lines.
        for ($line = 137; $line <= 140; $line++) {
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
        $file = $this->sniffFile(__FILE__, '7.3');
        $this->assertNoViolation($file);
    }
}
