<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Keywords;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the CaseSensitiveKeywords sniff.
 *
 * @group caseSensitiveKeywords
 * @group keywords
 *
 * @covers \PHPCompatibility\Sniffs\Keywords\CaseSensitiveKeywordsSniff
 *
 * @since 7.1.4
 */
class CaseSensitiveKeywordsUnitTest extends BaseSniffTestCase
{

    /**
     * testCaseSensitiveKeywords
     *
     * @dataProvider dataCaseSensitiveKeywords
     *
     * @param int    $line    The line number.
     * @param string $keyword The keyword.
     *
     * @return void
     */
    public function testCaseSensitiveKeywords($line, $keyword)
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertError($file, $line, "The keyword '{$keyword}' was treated in a case-sensitive fashion in certain cases in PHP 5.4 or earlier. Use the lowercase version for consistent support.");
    }

    /**
     * Data provider dataCaseSensitiveKeywords.
     *
     * @see testCaseSensitiveKeywords()
     *
     * @return array
     */
    public static function dataCaseSensitiveKeywords()
    {
        return [
            [18, 'self'],
            [19, 'static'],
            [20, 'parent'],
            [21, 'self'],
            [22, 'static'],
            [23, 'parent'],
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
        $file = $this->sniffFile(__FILE__, '5.4');
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
            [10],
            [11],
            [12],
            [13],
            [14],
            [15],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.5');
        $this->assertNoViolation($file);
    }
}
