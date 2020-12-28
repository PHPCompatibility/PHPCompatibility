<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Syntax;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewNestedStaticAccess sniff.
 *
 * @group newNestedStaticAccess
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\NewNestedStaticAccessSniff
 *
 * @since 10.0.0
 */
class NewNestedStaticAccessUnitTest extends BaseSniffTest
{

    /**
     * testNestedStaticAccess
     *
     * @dataProvider dataNestedStaticAccess
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNestedStaticAccess($line)
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertError($file, $line, 'Nested access to static properties, constants and methods was not supported in PHP 5.6 or earlier.');

        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNestedStaticAccess()
     *
     * @return array
     */
    public function dataNestedStaticAccess()
    {
        return [
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
            [23],
            [38],
        ];
    }


    /**
     * testClassConstantDereferencing
     *
     * @dataProvider dataClassConstantDereferencing
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testClassConstantDereferencing($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertError($file, $line, 'Dereferencing class constants was not supported in PHP 7.4 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testClassConstantDereferencing()
     *
     * @return array
     */
    public function dataClassConstantDereferencing()
    {
        return [
            [34],
            [35],
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
            [27],
            [28],
            [31],
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
