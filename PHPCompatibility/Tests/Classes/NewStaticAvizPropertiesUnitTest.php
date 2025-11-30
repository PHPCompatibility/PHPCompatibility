<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Classes;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewStaticAvizProperties sniff.
 *
 * @group newStaticAvizProperties
 * @group classes
 *
 * @covers \PHPCompatibility\Sniffs\Classes\NewStaticAvizPropertiesSniff
 *
 * @since 10.0.0
 */
final class NewStaticAvizPropertiesUnitTest extends BaseSniffTestCase
{

    /**
     * Verify that asymmetric visibility on static properties is correctly detected.
     *
     * @dataProvider dataNewStaticAvizProperties
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewStaticAvizProperties($line)
    {
        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertError($file, $line, 'Asymmetric visibility on static properties is not supported in PHP 8.4 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testNewStaticAvizProperties()
     *
     * @return array<array<int>>
     */
    public static function dataNewStaticAvizProperties()
    {
        return [
            [58],
            [59],
            [60],
            [63],
            [66],
            [69],
            [70],
        ];
    }


    /**
     * Verify there are no false positives for similar syntaxes.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array<array<int>>
     */
    public static function dataNoFalsePositives()
    {
        $data = [];

        // No errors expected on the first 53 lines.
        for ($line = 1; $line <= 53; $line++) {
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
        $file = $this->sniffFile(__FILE__, '8.5');
        $this->assertNoViolation($file);
    }
}
