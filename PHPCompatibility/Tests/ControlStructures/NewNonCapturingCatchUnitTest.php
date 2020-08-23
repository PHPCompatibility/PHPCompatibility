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
 * Test the NewNonCapturingCatch sniff.
 *
 * @group newNonCapturingCatch
 * @group controlStructures
 * @group exceptions
 *
 * @covers \PHPCompatibility\Sniffs\ControlStructures\NewNonCapturingCatchSniff
 *
 * @since 10.0.0
 */
class NewNonCapturingCatchUnitTest extends BaseSniffTest
{

    /**
     * testNewNonCapturingCatch
     *
     * @dataProvider dataNewNonCapturingCatch
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewNonCapturingCatch($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertError($file, $line, 'Catching exceptions without capturing them to a variable is not supported in PHP 7.4 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testNewNonCapturingCatch()
     *
     * @return array
     */
    public function dataNewNonCapturingCatch()
    {
        return [
            [21],
            [23],
            [25],
            [27],
        ];
    }


    /**
     * Verify no false positives are thrown for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return [
            [8],
            [10],
            [12],
            [34], // Live coding.
        ];
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
