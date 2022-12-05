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
 * Test the NewFirstClassCallables sniff.
 *
 * @group newFirstClassCallables
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\NewFirstClassCallablesSniff
 *
 * @since 10.0.0
 */
final class NewFirstClassCallablesUnitTest extends BaseSniffTest
{

    /**
     * Verify that first class callables are correctly detected.
     *
     * @dataProvider dataFirstClassCallables
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testFirstClassCallables($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, $line, 'First class callables using the CallableExpr(...) syntax are not supported in PHP 8.0 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testFirstClassCallables()
     *
     * @return array
     */
    public function dataFirstClassCallables()
    {
        return [
            [26],
            [27],
            [28],
            [29],
            [30],
            [31],
            [32],
            [35],
            [37],
            [38],
            [39],
            [40],
        ];
    }


    /**
     * Verify there are no false positives for similar syntaxes.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
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
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertNoViolation($file);
    }
}
