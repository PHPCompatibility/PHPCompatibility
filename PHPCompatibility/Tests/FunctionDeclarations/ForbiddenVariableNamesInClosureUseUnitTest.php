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
 * Test the ForbiddenVariableNamesInClosureUse sniff.
 *
 * @group forbiddenVariableNamesInClosureUse
 * @group closures
 * @group functionDeclarations
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\ForbiddenVariableNamesInClosureUseSniff
 *
 * @since 7.1.4
 */
class ForbiddenVariableNamesInClosureUseUnitTest extends BaseSniffTest
{

    /**
     * testForbiddenVariableNamesInClosureUse
     *
     * @dataProvider dataForbiddenVariableNamesInClosureUse
     *
     * @param int    $line    The line number.
     * @param string $varName Variable name which should be found.
     *
     * @return void
     */
    public function testForbiddenVariableNamesInClosureUse($line, $varName)
    {
        $file = $this->sniffFile(__FILE__, '7.1');
        $this->assertError($file, $line, 'Variables bound to a closure via the use construct cannot use the same name as superglobals, $this, or a declared parameter since PHP 7.1. Found: ' . $varName);
    }

    /**
     * Data provider.
     *
     * @see testForbiddenVariableNamesInClosureUse()
     *
     * @return array
     */
    public static function dataForbiddenVariableNamesInClosureUse()
    {
        return [
            [4, '$_SERVER'],
            [5, '$_REQUEST'],
            [6, '$GLOBALS'],
            [7, '$this'],
            [8, '$param'],
            [9, '$param'],
            [10, '$c'],
            [11, '$b'],
            [11, '$d'],
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
        $file = $this->sniffFile(__FILE__, '7.1');
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
            [18],
            [19],
            [22],
            [23],
            [24],
            [27],
            [31],
            [32],
            [33],
            [36],
            [41],
            [44],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertNoViolation($file);
    }
}
