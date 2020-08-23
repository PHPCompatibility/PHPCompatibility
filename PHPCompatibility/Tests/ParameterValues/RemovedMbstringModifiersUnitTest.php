<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the RemovedMbstringModifiers sniff.
 *
 * @group removedMbstringModifiers
 * @group parameterValues
 * @group regexModifiers
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedMbstringModifiersSniff
 *
 * @since 7.0.5
 */
class RemovedMbstringModifiersUnitTest extends BaseSniffTest
{

    /**
     * testMbstringEModifier
     *
     * @dataProvider dataMbstringEModifier
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testMbstringEModifier($line)
    {
        $file = $this->sniffFile(__FILE__, '5.3-7.1');
        $this->assertWarning($file, $line, 'The Mbstring regex "e" modifier is deprecated since PHP 7.1.');

        $file = $this->sniffFile(__FILE__, '7.1');
        $this->assertWarning($file, $line, 'The Mbstring regex "e" modifier is deprecated since PHP 7.1. Use mb_ereg_replace_callback() instead (PHP 5.4.1+).');

        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, $line, 'The Mbstring regex "e" modifier is deprecated since PHP 7.1 and removed since PHP 8.0. Use mb_ereg_replace_callback() instead (PHP 5.4.1+).');
    }

    /**
     * Data provider.
     *
     * @see testMbstringEModifier()
     *
     * @return array
     */
    public function dataMbstringEModifier()
    {
        return [
            [14],
            [15],
            [16],
            [24],
            [25],
            [26],
            [29],
            [30],
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
    public function dataNoFalsePositives()
    {
        return [
            [4],
            [5],
            [6],
            [9],
            [10],
            [11],
            [19],
            [20],
            [21],
            [33],
            [34],
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
