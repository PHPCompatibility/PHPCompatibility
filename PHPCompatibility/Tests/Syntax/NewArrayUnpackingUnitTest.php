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
 * Test the NewArrayUnpacking sniff.
 *
 * @group newArrayUnpacking
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\NewArrayUnpackingSniff
 *
 * @since 9.2.0
 */
class NewArrayUnpackingUnitTest extends BaseSniffTest
{

    /**
     * testNewArrayUnpacking
     *
     * @dataProvider dataNewArrayUnpacking
     *
     * @param array $line The line number on which the error should occur.
     *
     * @return void
     */
    public function testNewArrayUnpacking($line)
    {
        $file = $this->sniffFile(__FILE__, '7.3');
        $this->assertError($file, $line, 'Array unpacking within array declarations using the spread operator is not supported in PHP 7.3 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testNewArrayUnpacking()
     *
     * @return array
     */
    public static function dataNewArrayUnpacking()
    {
        return [
            [11],
            [14],
            [15],
            [17],
            [18],
            [22],
            [23],
            [27],
            [28],
            [33],
            [34],
            [35],
            [38],
        ];
    }


    /**
     * Verify the sniff doesn't throw false positives.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param array $line The line number on which the error should occur.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.3');
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
        for ($line = 1; $line < 7; $line++) {
            $data[] = [$line];
        }

        // Short list.
        $data[] = [41];

        // Don't report for live coding.
        $data[] = [45];
        $data[] = [46];

        return $data;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertNoViolation($file);
    }
}
