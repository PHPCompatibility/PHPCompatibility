<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Interfaces;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewPropertiesInInterfaces sniff.
 *
 * @group newPropertiesInInterfaces
 * @group interfaces
 *
 * @covers \PHPCompatibility\Sniffs\Interfaces\NewPropertiesInInterfacesSniff
 *
 * @since 10.0.0
 */
final class NewPropertiesInInterfacesUnitTest extends BaseSniffTestCase
{

    /**
     * Test that an error is thrown for properties declared in interfaces.
     *
     * @dataProvider dataPropertyInInterface
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testPropertyInInterface($line)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertError($file, $line, 'Declaring properties in interfaces is not supported in PHP 8.3 or earlier.');
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public static function dataPropertyInInterface()
    {
        return [
            [41],
            [44],
            [46],
            [47],
            [48],

            // Invalid, still flagged.
            [61],
            [62],
            [66],
            [71],
            [76],
            [80],
            [84],
        ];
    }


    /**
     * Verify that there are no false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
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
        $data = [];
        for ($line = 1; $line <= 34; $line++) {
            $data[] = [$line];
        }

        return $data;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertNoViolation($file);
    }
}
