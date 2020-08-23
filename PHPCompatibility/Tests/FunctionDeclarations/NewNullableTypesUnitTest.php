<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionDeclarations;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewNullableTypes sniff.
 *
 * @group nullableTypes
 * @group functionDeclarations
 * @group typeDeclarations
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\NewNullableTypesSniff
 *
 * @since 7.0.7
 */
class NewNullableTypesUnitTest extends BaseSniffTest
{

    /**
     * testNewNullableReturnTypes
     *
     * @dataProvider dataNewNullableReturnTypes
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewNullableReturnTypes($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertError($file, $line, 'Nullable return types are not supported in PHP 7.0 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testNewNullableReturnTypes()
     *
     * @return array
     */
    public function dataNewNullableReturnTypes()
    {
        return [
            [21],
            [22],
            [23],
            [24],
            [25],
            [26],
            [27],
            [28],
            [29],

            [63],
            [77],
            [86],
        ];
    }


    /**
     * testNewNullableTypeHints
     *
     * @dataProvider dataNewNullableTypeHints
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewNullableTypeHints($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertError($file, $line, 'Nullable type declarations are not supported in PHP 7.0 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testNewNullableTypeHints()
     *
     * @return array
     */
    public function dataNewNullableTypeHints()
    {
        return [
            [48],
            [49],
            [50],
            [51],
            [52],
            [53],
            [54],
            [55],
            [56],

            [59], // Three errors of the same.

            [64],
            [68],
            [74],
            [86],
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
    public function dataNoFalsePositives()
    {
        return [
            [8],
            [9],
            [10],
            [11],
            [12],
            [13],
            [14],
            [15],
            [16],

            [35],
            [36],
            [37],
            [38],
            [39],
            [40],
            [41],
            [42],
            [43],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.1');
        $this->assertNoViolation($file);
    }
}
