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

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewDynamicClassConstantFetch sniff.
 *
 * @group newDynamicClassConstantFetch
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\NewDynamicClassConstantFetchSniff
 *
 * @since 10.0.0
 */
final class NewDynamicClassConstantFetchUnitTest extends BaseSniffTestCase
{

    /**
     * Ensure an error is thrown when the new syntax is used.
     *
     * @dataProvider dataDynamicClassConstantFetch
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testDynamicClassConstantFetch($line)
    {
        $file = $this->sniffFile(__FILE__, '8.2');
        $this->assertError($file, $line, 'Dynamic class constant fetch is not available in PHP 8.2 or earlier.');
    }

    /**
     * Data provider.
     *
     * @return array<array<int>>
     * @see    testDynamicClassConstantFetch()
     */
    public static function dataDynamicClassConstantFetch()
    {
        return [
            [17],
            [18],
            [19],
            [20],
            [23],
        ];
    }

    /**
     * Verify no notices are thrown at all on PHP versions on which the syntax is supported.
     *
     * @return void
     */
    public function testNoViolationsOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file);
    }

    /**
     * Data provider.
     *
     * @return array<array<int>>
     * @see    testNoViolationsOnValidVersion()
     */
    public static function dataNoViolationsOnValidVersion()
    {
        return [
            [17],
            [18],
            [19],
            [20],
            [23],
        ];
    }

    /**
     * Verify there are no false positives on valid code.
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
     * @return array<array<int>>
     * @see    testNoFalsePositives()
     */
    public static function dataNoFalsePositives()
    {
        return [
            [4],
            [5],
            [6],
            [7],
            [8],
            [9],
            [10],
            [11],
            [12],
            [13],
            [14],
            [25], // last because parse error
        ];
    }
}
