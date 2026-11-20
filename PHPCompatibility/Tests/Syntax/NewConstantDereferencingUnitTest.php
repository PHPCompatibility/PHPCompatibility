<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2021 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Syntax;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewConstantDereferencing sniff.
 *
 * @group newConstantDereferencing
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\NewConstantDereferencingSniff
 *
 * @since 10.0.0
 */
class NewConstantDereferencingUnitTest extends BaseSniffTest
{

    /**
     * Test for errors related to array dereferencing of constants in
     * PHP 5.5 and below.
     *
     * @dataProvider dataArrayDereferencing
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testArrayDereferencing($line)
    {
        $file = $this->sniffFile(__FILE__, '5.5');
        $this->assertError($file, $line, 'Array dereferencing of constants is not present in PHP version 5.5 or earlier');

        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testArrayDereferencing()
     *
     * @return array
     */
    public function dataArrayDereferencing()
    {
        return [
            [21],
            [22],
            [23],
            [24],
            [25],
            [26],
            [27],
        ];
    }

    /**
     * Test for errors related to object dereferencing of constants in
     * PHP 7.4 and below.
     *
     * @dataProvider dataObjectDereferencing
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testObjectDereferencing($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertError($file, $line, 'Object dereferencing of constants is not present in PHP version 7.4 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testObjectDereferencing()
     *
     * @return array
     */
    public function dataObjectDereferencing()
    {
        return [
            [30],
            [31],
            [32],
        ];
    }

    /**
     * Test against false positives for dereferencing non-constants.
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
    public function dataNoFalsePositives()
    {
        return [
            // Non-constants using array or object dereferencing
            [4],
            [5],
            [6],

            // References to non-constants
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
        ];
    }

    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertNoViolation($file);
    }
}
