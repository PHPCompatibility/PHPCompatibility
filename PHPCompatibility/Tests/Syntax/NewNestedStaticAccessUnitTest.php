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
        return array(
            array(7),
            array(8),
            array(9),
            array(10),
            array(11),
            array(12),
            array(13),
            array(14),
            array(15),
            array(16),
            array(17),
            array(18),
            array(19),
            array(23),
            array(38),
        );
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
        return array(
            array(4),
            array(27),
            array(28),
            array(31),
            array(34),
            array(35),
        );
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
