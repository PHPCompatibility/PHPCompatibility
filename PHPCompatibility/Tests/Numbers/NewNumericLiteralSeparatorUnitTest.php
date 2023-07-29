<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Numbers;

use PHPCompatibility\Tests\BaseSniffTestCase;
use PHPCSUtils\BackCompat\Helper;

/**
 * New Numeric Literal Separator Sniff tests
 *
 * @group newNumericLiteralSeparator
 * @group numbers
 *
 * @covers \PHPCompatibility\Sniffs\Numbers\NewNumericLiteralSeparatorSniff
 *
 * @since 10.0.0
 */
class NewNumericLiteralSeparatorUnitTest extends BaseSniffTestCase
{

    /**
     * Test recognizing numeric literals with underscores correctly.
     *
     * @dataProvider dataNewNumericLiteralSeparator
     *
     * @param array $line The line number on which the error should occur.
     *
     * @return void
     */
    public function testNewNumericLiteralSeparator($line)
    {
        if (\version_compare(Helper::getVersion(), '3.5.3', '==')) {
            $this->markTestSkipped('PHPCS 3.5.3 is not supported for this sniff');
        }

        $file = $this->sniffFile(__FILE__, '7.3');
        $this->assertError($file, $line, 'The use of underscore separators in numeric literals is not supported in PHP 7.3 or lower. Found:');
    }

    /**
     * Data provider.
     *
     * @see testNewNumericLiteralSeparator()
     *
     * @return array
     */
    public static function dataNewNumericLiteralSeparator()
    {
        $data = [
            [14],
            [15],
            [16],
            [18],
            [19],
            [20],
            [21],
            [22],
            [23],
            [26],
            [44],
        ];

        // The test case on line 39 is half a valid numeric literal with underscore, half parse error.
        // The sniff will behave differently on PHP 7.4 vs PHP < 7.4.
        if (\version_compare(\PHP_VERSION_ID, '70399', '>')) {
            $data[] = [41];
        }

        return $data;
    }


    /**
     * Verify there are no false positives for a PHP version on which this sniff throws errors.
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
        $data = [];

        // No issues expected on the first 12 lines.
        for ($i = 1; $i <= 12; $i++) {
            $data[] = [$i];
        }

        // Parse errors, should be ignored by the sniff.
        $data[] = [31];
        $data[] = [32];
        $data[] = [33];
        $data[] = [34];
        $data[] = [35];
        $data[] = [36];
        $data[] = [37];
        $data[] = [38];

        // The test case on line 39 is half a valid numeric literal with underscore, half parse error.
        // The sniff will behave differently on PHP 7.4 vs PHP < 7.4.
        if (\version_compare(\PHP_VERSION_ID, '70399', '<=')) {
            $data[] = [41];
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
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertNoViolation($file);
    }
}
