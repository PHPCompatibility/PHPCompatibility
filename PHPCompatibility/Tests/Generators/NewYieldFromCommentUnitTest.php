<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Generators;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewYieldFromComment sniff.
 *
 * @group newYieldFromComment
 * @group generators
 *
 * @covers \PHPCompatibility\Sniffs\Generators\NewYieldFromCommentSniff
 *
 * @since 10.0.0
 */
final class NewYieldFromCommentUnitTest extends BaseSniffTestCase
{

    /**
     * Test detection of comments between "yield" and "from".
     *
     * @dataProvider dataNewYieldFromComment
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewYieldFromComment($line)
    {
        $file = $this->sniffFile(__FILE__, '8.2');
        $this->assertError($file, $line, 'Comment(s) between the "yield" and "from" keywords were not supported in PHP 8.2 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testNewYieldFromComment()
     *
     * @return array<array<int>>
     */
    public static function dataNewYieldFromComment()
    {
        return [
            [23],
            [27],
            [33],
        ];
    }


    /**
     * Test the sniff doesn't throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.2');
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

        // No errors expected on the first 20 lines.
        for ($line = 1; $line <= 20; $line++) {
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
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file);
    }
}
