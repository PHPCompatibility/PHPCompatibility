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
 * Test the NewClassMemberAccessWithoutParentheses sniff.
 *
 * @group newClassMemberAccessWithoutParentheses
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\NewClassMemberAccessWithoutParenthesesSniff
 *
 * @since 10.0.0
 */
final class NewClassMemberAccessWithoutParenthesesUnitTest extends BaseSniffTestCase
{

    /**
     * Test detection of new expressions with member access without wrapping parentheses.
     *
     * @dataProvider dataNewClassMemberAccessWithoutParentheses
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewClassMemberAccessWithoutParentheses($line)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertError($file, $line, 'Class member access on object instantiation, without parentheses around the new expression, was not supported in PHP 8.3 or earlier');
    }

    /**
     * Data provider.
     *
     * @return array<array<int>>
     */
    public static function dataNewClassMemberAccessWithoutParentheses()
    {
        return [
            [68],
            [69],
            [70],
            [71],
            [72],
            [73],
            [74],
            [75],
            [76],
            [77],
            [78],

            [82],
            [83],
            [84],
            [85],
            [86],
            [87],
            [88],
            [89],
            [90],
            [91],
            [92],

            [95],
            [96],
            [97],
            [98],
            [99],
            [100],
            [101],
            [102],
            [103],
            [104],
            [105],

            [109],
            [110],
            [111],
            [112],
            [113],
            [114],
            [115],
            [116],
        ];
    }


    /**
     * Verify that there are no false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @return array<array<int>>
     */
    public static function dataNoFalsePositives()
    {
        $data = [];
        for ($line = 1; $line <= 63; $line++) {
            $data[] = [$line];
        }

        // Live coding/parse error.
        $data[] = [120];
        $data[] = [124];

        return $data;
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
