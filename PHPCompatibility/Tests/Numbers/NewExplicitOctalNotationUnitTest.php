<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2022 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Numbers;

use PHPCompatibility\Tests\BaseSniffTest;
use PHPCSUtils\BackCompat\Helper;

/**
 * Tests for the NewExplicitOctalNotationSniff sniff.
 *
 * @group newExplicitOctalNotation
 * @group numbers
 *
 * @covers \PHPCompatibility\Sniffs\Numbers\NewExplicitOctalNotationSniff
 *
 * @since 10.0.0
 */
class NewExplicitOctalNotationUnitTest extends BaseSniffTest
{

    /**
     * Test recognizing the explicit octal notation correctly.
     *
     * @dataProvider dataExplicitOctalNotation
     *
     * @param array $line The line number on which the error should occur.
     *
     * @return void
     */
    public function testExplicitOctalNotation($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, $line, 'The explicit integer octal literal prefix "0o" is not supported in PHP 8.0 or lower. Found:');
    }

    /**
     * Data provider.
     *
     * @see testExplicitOctalNotation()
     *
     * @return array
     */
    public function dataExplicitOctalNotation()
    {
        return [
            [20],
            [21],
            [22],
        ];
    }


    /**
     * Verify there are no false positives for valid code with a PHP version on which this sniff throws errors.
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
    public function dataNoFalsePositives()
    {
        $data = [];

        // No issues expected on the first 16 lines.
        for ($i = 1; $i <= 16; $i++) {
            $data[] = [$i];
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
