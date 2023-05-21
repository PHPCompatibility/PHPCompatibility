<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionNameRestrictions;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the ReservedFunctionNames sniff.
 *
 * @group reservedFunctionNames
 * @group functionNameRestrictions
 *
 * @covers \PHPCompatibility\Sniffs\FunctionNameRestrictions\ReservedFunctionNamesSniff
 *
 * @since 8.2.0
 */
class ReservedFunctionNamesUnitTest extends BaseSniffTest
{

    /**
     * Test that double underscore prefixed functions/methods which aren't reserved names trigger an error.
     *
     * @dataProvider dataReservedFunctionNames
     *
     * @param int    $line The line number.
     * @param string $type Either 'function' or 'method'.
     *
     * @return void
     */
    public function testReservedFunctionNames($line, $type)
    {
        $file = $this->sniffFile(__FILE__);
        $this->assertWarning($file, $line, " is discouraged; PHP has reserved all $type names with a double underscore prefix for future use.");
    }

    /**
     * Data provider.
     *
     * @see testReservedFunctionNames()
     *
     * @return array
     */
    public function dataReservedFunctionNames()
    {
        return [
            [20, 'method'],
            [21, 'method'],
            [22, 'method'],

            [25, 'function'],
            [26, 'function'],
            [27, 'function'],
            [28, 'function'],
            [29, 'function'],
            [30, 'function'],
            [31, 'function'],
            [32, 'function'],
            [33, 'function'],
            [34, 'function'],
            [35, 'function'],
            [37, 'function'],
            [38, 'function'],
            [39, 'function'],
            [41, 'function'],
            [42, 'function'],

            [92, 'method'],
            [93, 'method'],
            [94, 'method'],

            [107, 'method'],
            [109, 'method'],

            [139, 'function'],
            [142, 'method'],

            [149, 'function'],
            [150, 'function'],

            [160, 'function'],
            [161, 'function'],

            [168, 'function'],

            [198, 'function'],

            [207, 'method'],
            [208, 'method'],
        ];
    }


    /**
     * Verify the sniff doesn't throw false positives.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__);
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
            [15],
            [16],
            [17],
            [18],
            [19],

            [40],
            [46],
            [50],
            [51],
            [52],
            [54],

            [58],
            [63],
            [66],
            [69],
            [72],

            [77],
            [78],
            [79],
            [80],
            [81],
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

            [98],
            [101],
            [102],

            [124],
            [135],

            [148],

            [156],
            [157],

            [179],

            [188],
            [195],

            [203],
            [204],
            [205],

            // Live coding/parse error test.
            [214],
        ];
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff operates
     *  independently of the testVersion.
     */
}
