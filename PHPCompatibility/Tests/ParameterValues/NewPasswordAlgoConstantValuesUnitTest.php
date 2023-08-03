<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewPasswordAlgoConstantValues sniff.
 *
 * @group newPasswordAlgoConstantValues
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\NewPasswordAlgoConstantValuesSniff
 *
 * @since 9.3.0
 */
class NewPasswordAlgoConstantValuesUnitTest extends BaseSniffTestCase
{

    /**
     * testNewPasswordAlgoConstantValues
     *
     * @dataProvider dataNewPasswordAlgoConstantValues
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testNewPasswordAlgoConstantValues($line)
    {
        $file  = $this->sniffFile(__FILE__, '7.4');
        $error = 'The value of the password hash algorithm constants has changed in PHP 7.4';

        $this->assertWarning($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testNewPasswordAlgoConstantValues()
     *
     * @return array
     */
    public static function dataNewPasswordAlgoConstantValues()
    {
        return [
            [22],
            [23],
            [24],
            [25],
            [26],
            [27],
            [28],
        ];
    }


    /**
     * Test that there are no false positives.
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
    public static function dataNoFalsePositives()
    {
        $data = [];

        // No errors expected on the first 20 lines.
        for ($line = 1; $line <= 20; $line++) {
            $data[] = [$line];
        }

        for ($line = 30; $line <= 36; $line++) {
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
        $file = $this->sniffFile(__FILE__, '7.3');
        $this->assertNoViolation($file);
    }
}
