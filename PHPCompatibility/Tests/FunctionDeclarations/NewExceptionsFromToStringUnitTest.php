<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionDeclarations;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewExceptionsFromToString sniff.
 *
 * @group newExceptionsFromToString
 * @group functionDeclarations
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\NewExceptionsFromToStringSniff
 *
 * @since 9.2.0
 */
class NewExceptionsFromToStringUnitTest extends BaseSniffTest
{

    /**
     * testNewExceptionsFromToString.
     *
     * @dataProvider dataNewExceptionsFromToString
     *
     * @param int $line The line number where a warning is expected.
     *
     * @return void
     */
    public function testNewExceptionsFromToString($line)
    {
        $file = $this->sniffFile(__FILE__, '7.3');
        $this->assertError($file, $line, 'Throwing exceptions from __toString() was not allowed prior to PHP 7.4');
    }

    /**
     * Data provider.
     *
     * @see testNewExceptionsFromToString()
     *
     * @return array
     */
    public static function dataNewExceptionsFromToString()
    {
        return [
            [39],
            [48],
            [57],
            [80],
            [83],
            [92],
            [130],
            [141],
            [152],
        ];
    }


    /**
     * testNoFalsePositives.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.3');
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
        $cases = [];
        // No errors expected on the first 34 lines.
        for ($line = 1; $line <= 34; $line++) {
            $cases[] = [$line];
        }

        $cases[] = [37];
        $cases[] = [46];
        $cases[] = [55];

        // No false positive for try/catch.
        for ($line = 64; $line <= 79; $line++) {
            $cases[] = [$line];
        }

        $cases[] = [90];

        // No false positive for docblock check.
        for ($line = 103; $line <= 122; $line++) {
            $cases[] = [$line];
        }

        $cases[] = [154];

        return $cases;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertNoViolation($file);
    }
}
