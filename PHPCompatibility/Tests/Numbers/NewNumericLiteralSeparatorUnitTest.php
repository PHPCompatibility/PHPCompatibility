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

use PHPCompatibility\Tests\BaseSniffTest;
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
class NewNumericLiteralSeparatorUnitTest extends BaseSniffTest
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
        if (version_compare(Helper::getVersion(), '3.5.3', '==')) {
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
    public function dataNewNumericLiteralSeparator()
    {
        $data = array(
            array(14),
            array(15),
            array(16),
            array(18),
            array(19),
            array(20),
            array(21),
            array(22),
            array(23),
            array(26),
        );

        // The test case on line 39 is half a valid numeric literal with underscore, half parse error.
        // The sniff will behave differently on PHP 7.4 vs PHP < 7.4.
        if (version_compare(\PHP_VERSION_ID, '70399', '>') || version_compare(Helper::getVersion(), '3.5.3', '<')) {
            $data[] = array(41);
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
    public function dataNoFalsePositives()
    {
        $data = array();

        // No issues expected on the first 12 lines.
        for ($i = 1; $i <= 12; $i++) {
            $data[] = array($i);
        }

        // Parse errors, should be ignored by the sniff.
        $data[] = array(31);
        $data[] = array(32);
        $data[] = array(33);
        $data[] = array(34);
        $data[] = array(35);
        $data[] = array(36);
        $data[] = array(37);
        $data[] = array(38);

        // The test case on line 39 is half a valid numeric literal with underscore, half parse error.
        // The sniff will behave differently on PHP 7.4 vs PHP < 7.4.
        if (version_compare(\PHP_VERSION_ID, '70399', '<=') && version_compare(Helper::getVersion(), '3.5.3', '>')) {
            $data[] = array(41);
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
