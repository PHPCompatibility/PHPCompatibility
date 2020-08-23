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
 * Test the NewFunctionCallTrailingComma sniff.
 *
 * @group newFunctionCallTrailingComma
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\NewFunctionCallTrailingCommaSniff
 *
 * @since 8.2.0
 */
class NewFunctionCallTrailingCommaUnitTest extends BaseSniffTest
{

    /**
     * testTrailingComma
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
        $this->assertError($file, $line, "Trailing comma's are not allowed in {$type} in PHP 7.2 or earlier");
    }

    /**
     * Data provider.
     *
     * @see testTrailingComma()
     *
     * @return array
     */
    public function dataTrailingComma()
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
    public function dataNoFalsePositives()
    {
        return [
            [6],
            [7],
            [8],
            [9],
            [51],
            [58],
            [59],
            [68],
            [71],
        ];
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
