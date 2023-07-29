<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ControlStructures;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewForeachExpressionReferencing sniff.
 *
 * @group newForeachExpressionReferencing
 * @group controlStructures
 *
 * @covers \PHPCompatibility\Sniffs\ControlStructures\NewForeachExpressionReferencingSniff
 * @covers \PHPCompatibility\Helpers\TokenGroup::isVariable
 *
 * @since 9.0.0
 */
class NewForeachExpressionReferencingUnitTest extends BaseSniffTestCase
{

    /**
     * testNewForeachExpressionReferencing
     *
     * @dataProvider dataNewForeachExpressionReferencing
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testNewForeachExpressionReferencing($line)
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertError($file, $line, 'Referencing $value is only possible if the iterated array is a variable in PHP 5.4 or earlier.');
    }

    /**
     * dataNewForeachExpressionReferencing
     *
     * @see testNewForeachExpressionReferencing()
     *
     * @return array
     */
    public static function dataNewForeachExpressionReferencing()
    {
        return [
            [17],
            [18],
            [20],
            [21],
            [23],
            [24],
        ];
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number with a valid list assignment.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataNoFalsePositives
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public static function dataNoFalsePositives()
    {
        return [
            [6],
            [7],
            [8],
            [9],
            [10],
            [11],
            [12],
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
