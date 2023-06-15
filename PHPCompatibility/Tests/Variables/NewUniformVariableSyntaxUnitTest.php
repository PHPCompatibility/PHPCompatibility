<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Variables;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewUniformVariableSyntax sniff.
 *
 * @group newUniformVariableSyntax
 * @group variables
 *
 * @covers \PHPCompatibility\Sniffs\Variables\NewUniformVariableSyntaxSniff
 *
 * @since 7.1.2
 */
class NewUniformVariableSyntaxUnitTest extends BaseSniffTest
{

    /**
     * testVariableVariables
     *
     * @dataProvider dataVariableVariables
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testVariableVariables($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertError($file, $line, 'Indirect access to variables, properties and methods will be evaluated strictly in left-to-right order since PHP 7.0. Use curly braces to remove ambiguity.');
    }

    /**
     * Data provider.
     *
     * @see testVariableVariables()
     *
     * @return array
     */
    public static function dataVariableVariables()
    {
        return [
            [4],
            [5],
            [6],
            [7],
            [8],
            [33],
            [34],
            [35],
            [40],
            [41],
            [45],
            [46],
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
            [11],
            [12],
            [13],
            [14],
            [15],

            [18],
            [19],
            [20],
            [21],
            [22],
            [23],
            [24],
            [25],
            [26],
            [27],
            [28],
            [42],
            [47],
            [48],
            [51],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertNoViolation($file);
    }
}
