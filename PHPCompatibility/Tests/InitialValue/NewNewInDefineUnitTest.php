<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\InitialValue;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewNewInDefine sniff.
 *
 * @group newNewInDefine
 * @group initialValue
 *
 * @covers \PHPCompatibility\Sniffs\InitialValue\NewNewInDefineSniff
 *
 * @since 10.0.0
 */
final class NewNewInDefineUnitTest extends BaseSniffTestCase
{

    /**
     * Verify that objects (new class instantiations) being passed to define() are detected correctly.
     *
     * @dataProvider dataNewInDefine
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewInDefine($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, $line, 'Passing an object as the value when declaring constants using define is not allowed in PHP 8.0 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testNewInDefine()
     *
     * @return array
     */
    public static function dataNewInDefine()
    {
        return [
            [38],
            [39],
            [40],
            [41],
            [44],
            [45],
            [46],
            [51],
        ];
    }


    /**
     * Verify the sniff doesn't throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
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
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertNoViolation($file);
    }
}
