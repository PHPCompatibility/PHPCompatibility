<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\UseDeclarations;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewUseConstFunction sniff.
 *
 * @group newUseConstFunction
 * @group useDeclarations
 *
 * @covers \PHPCompatibility\Sniffs\UseDeclarations\NewUseConstFunctionSniff
 *
 * @since 7.1.4
 */
class NewUseConstFunctionUnitTest extends BaseSniffTest
{

    /**
     * testNewUseConstFunction
     *
     * @dataProvider dataNewUseConstFunction
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewUseConstFunction($line)
    {
        $file = $this->sniffFile(__FILE__, '5.5');
        $this->assertError($file, $line, 'Importing functions and constants through a "use" statement is not supported in PHP 5.5 or lower.');
    }

    /**
     * Data provider dataNewUseConstFunction.
     *
     * @see testNewUseConstFunction()
     *
     * @return array
     */
    public static function dataNewUseConstFunction()
    {
        return [
            [29],
            [30],
            [31],
            [32],
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
        $file = $this->sniffFile(__FILE__, '5.5');
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
            [7],
            [8],
            [9],
            [15],
            [19],
            [24],
            [37],
            [38],
            [39],
            [45],
            [46],
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
