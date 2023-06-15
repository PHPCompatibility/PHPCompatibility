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

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewReadonlyClasses sniff.
 *
 * @group newReadonlyClasses
 * @group constants
 *
 * @covers \PHPCompatibility\Sniffs\Classes\NewReadonlyClassesSniff
 *
 * @since 10.0.0
 */
final class NewReadonlyClassesUnitTest extends BaseSniffTest
{

    /**
     * Test that an error is thrown for class constants declared with visibility.
     *
     * @dataProvider dataReadonlyClass
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testReadonlyClass($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertError($file, $line, 'Readonly classes are not supported in PHP 8.1 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testReadonlyClass()
     *
     * @return array
     */
    public static function dataReadonlyClass()
    {
        return [
            [21],
            [31],
            [32],
            [34],
            [35],
            [38],
            [42],
            [45],
            [52],
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
        $file = $this->sniffFile(__FILE__, '8.1');
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
        for ($line = 1; $line <= 17; $line++) {
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
        $file = $this->sniffFile(__FILE__, '8.2');
        $this->assertNoViolation($file);
    }
}
