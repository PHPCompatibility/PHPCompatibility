<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Namespaces;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the ReservedNames sniff.
 *
 * @group reservedNames
 * @group namespaces
 *
 * @covers \PHPCompatibility\Sniffs\Namespaces\ReservedNamesSniff
 *
 * @since 10.0.0
 */
class ReservedNamesUnitTest extends BaseSniffTestCase
{

    /**
     * Verify correctly detecting reserved namespace names.
     *
     * @dataProvider dataReservedNames
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testReservedNames($line)
    {
        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertWarning($file, $line, ' is discouraged; PHP has reserved the namespace name "PHP" and compound names starting with "PHP" for internal language use.');
    }

    /**
     * Data provider.
     *
     * @see testReservedNames()
     *
     * @return array
     */
    public static function dataReservedNames()
    {
        return [
            [11],
            [12],
            [13],
        ];
    }


    /**
     * Verify the sniff does not throw false positives.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '5.3');
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
            [4],
            [5],
            [6],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.2');
        $this->assertNoViolation($file);
    }
}
