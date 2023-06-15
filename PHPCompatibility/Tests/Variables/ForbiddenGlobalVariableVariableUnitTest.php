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
 * Test the ForbiddenGlobalVariableVariable sniff.
 *
 * @group forbiddenGlobalVariableVariable
 * @group variables
 *
 * @covers \PHPCompatibility\Sniffs\Variables\ForbiddenGlobalVariableVariableSniff
 *
 * @since 7.0.0
 */
class ForbiddenGlobalVariableVariableUnitTest extends BaseSniffTest
{

    /**
     * testGlobalVariableVariable
     *
     * @dataProvider dataGlobalVariableVariable
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testGlobalVariableVariable($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertError($file, $line, 'Global with variable variables is not allowed since PHP 7.0');
    }

    /**
     * Data provider dataGlobalVariableVariable.
     *
     * @see testGlobalVariableVariable()
     *
     * @return array
     */
    public static function dataGlobalVariableVariable()
    {
        return [
            [21],
            [22],
            [23],
            [24],
            [25],
            [29],
            [31],
            [55],
        ];
    }


    /**
     * testGlobalNonBareVariable
     *
     * @dataProvider dataGlobalNonBareVariable
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testGlobalNonBareVariable($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertWarning($file, $line, 'Global with anything other than bare variables is discouraged since PHP 7.0');
    }

    /**
     * Data provider dataGlobalNonBareVariable.
     *
     * @see testGlobalNonBareVariable()
     *
     * @return array
     */
    public static function dataGlobalNonBareVariable()
    {
        return [
            [11], // x2
            [17],
            [18],
            [35],
            [36],
            [37],
            [38],
            [39],
            [42],
            [43],
            [44],
            [45],
            [46],
            [47],
            [51],
            [52],
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
            [8],
            [14],
            [15],
            [16],
            [50],
            [58],
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
