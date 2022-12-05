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
 * Test the NewConstructorPropertyPromotion sniff.
 *
 * @group newConstructorPropertyPromotion
 * @group classes
 *
 * @covers \PHPCompatibility\Sniffs\Classes\NewConstructorPropertyPromotionSniff
 *
 * @since 10.0.0
 */
final class NewConstructorPropertyPromotionUnitTest extends BaseSniffTest
{

    /**
     * Verify that the sniff throws an error for constructor property promotion.
     *
     * @dataProvider dataNewConstructorPropertyPromotion
     *
     * @param int $line The line number where the error is expected.
     *
     * @return void
     */
    public function testNewConstructorPropertyPromotion($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertError($file, $line, 'Constructor property promotion is not available in PHP version 7.4 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testNewConstructorPropertyPromotion()
     *
     * @return array
     */
    public function dataNewConstructorPropertyPromotion()
    {
        return [
            [32],
            [33],
            [34],
            [39], // x3.
            [44],
            [54], // x2.
        ];
    }


    /**
     * Verify the sniff does not throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
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
        $cases = [];
        // No errors expected on the first 26 lines.
        for ($line = 1; $line <= 26; $line++) {
            $cases[] = [$line];
        }

        $cases[] = [45];

        return $cases;
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
