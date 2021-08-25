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
 * Test the NewGroupUseDeclarations sniff.
 *
 * @group newGroupUseDeclarations
 * @group useDeclarations
 *
 * @covers \PHPCompatibility\Sniffs\UseDeclarations\NewGroupUseDeclarationsSniff
 *
 * @since 7.0.0
 */
class NewGroupUseDeclarationsUnitTest extends BaseSniffTest
{

    /**
     * testGroupUseDeclaration
     *
     * @dataProvider dataGroupUseDeclaration
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testGroupUseDeclaration($line)
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertError($file, $line, 'Group use declarations are not allowed in PHP 5.6 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testGroupUseDeclaration()
     *
     * @return array
     */
    public function dataGroupUseDeclaration()
    {
        return [
            [23],
            [24],
            [25],
            [26],
            [33],
            [34],
            [35],
            [36],
        ];
    }


    /**
     * testGroupUseTrailingComma
     *
     * @dataProvider dataGroupUseTrailingComma
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testGroupUseTrailingComma($line)
    {
        $file = $this->sniffFile(__FILE__, '7.1');
        $this->assertError($file, $line, 'Trailing commas are not allowed in group use statements in PHP 7.1 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testGroupUseTrailingComma()
     *
     * @return array
     */
    public function dataGroupUseTrailingComma()
    {
        return [
            [33],
            [34],
            [35],
            [39],
        ];
    }


    /**
     * testNoFalsePositivesTrailingComma
     *
     * @dataProvider dataNoFalsePositivesTrailingComma
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositivesTrailingComma($line)
    {
        $file = $this->sniffFile(__FILE__, '7.1');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesTrailingComma()
     *
     * @return array
     */
    public function dataNoFalsePositivesTrailingComma()
    {
        return [
            [23],
            [24],
            [25],
            [29],
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
        $file = $this->sniffFile(__FILE__, '5.6');
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
            [4],
            [5],
            [6],
            [8],
            [9],
            [11],
            [13],
            [15],
            [19],
            [20],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.2');
        $this->assertNoViolation($file);
    }
}
