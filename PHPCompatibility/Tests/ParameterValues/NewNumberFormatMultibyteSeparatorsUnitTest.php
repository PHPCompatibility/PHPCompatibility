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
 * Test the NewNumberFormatMultibyteSeparators sniff.
 *
 * @group newNumberFormatMultibyteSeparators
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\NewNumberFormatMultibyteSeparatorsSniff
 *
 * @since 10.0.0
 */
class NewNumberFormatMultibyteSeparatorsUnitTest extends BaseSniffTestCase
{

    /**
     * testNewNumberFormatMultibyteSeparators
     *
     * @dataProvider dataNewNumberFormatMultibyteSeparators
     *
     * @param int    $line      Line number where the error should occur.
     * @param string $paramName The name of the parameter for which to expect the error.
     *
     * @return void
     */
    public function testNewNumberFormatMultibyteSeparators($line, $paramName)
    {
        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertError($file, $line, "Passing a multi-byte separator as the \${$paramName} to number_format() is not supported in PHP 5.3 or earlier");
    }

    /**
     * Data provider.
     *
     * @see testNewNumberFormatMultibyteSeparators()
     *
     * @return array
     */
    public static function dataNewNumberFormatMultibyteSeparators()
    {
        return [
            [20, 'thousands_separator'],
            [24, 'decimal_separator'],
            [29, 'decimal_separator'],
            [33, 'thousands_separator'],
        ];
    }


    /**
     * Verify there are no false positives on valid code.
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '5.3');

        // No errors expected on the first 16 lines.
        for ($line = 1; $line <= 16; $line++) {
            $this->assertNoViolation($file, $line);
        }
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertNoViolation($file);
    }
}
