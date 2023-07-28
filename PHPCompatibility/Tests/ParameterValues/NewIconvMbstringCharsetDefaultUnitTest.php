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

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewIconvMbstringCharsetDefault sniff.
 *
 * @group newIconvMbstringCharsetDefault
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\NewIconvMbstringCharsetDefaultSniff
 *
 * @since 9.3.0
 */
class NewIconvMbstringCharsetDefaultUnitTest extends BaseSniffTest
{

    /**
     * testNewIconvMbstringCharsetDefault
     *
     * @dataProvider dataNewIconvMbstringCharsetDefault
     *
     * @param int    $line         Line number where the error should occur.
     * @param string $functionName The name of the function called.
     * @param string $paramName    The name of parameter which is missing.
     *                             Defaults to `$encoding`.
     *
     * @return void
     */
    public function testNewIconvMbstringCharsetDefault($line, $functionName, $paramName = '$encoding')
    {
        $file  = $this->sniffFile(__FILE__, '5.4-7.0');
        $error = "The default value of the {$paramName} parameter for {$functionName}() was changed from ISO-8859-1 to UTF-8 in PHP 5.6";

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testNewIconvMbstringCharsetDefault()
     *
     * @return array
     */
    public static function dataNewIconvMbstringCharsetDefault()
    {
        return [
            [44, 'iconv_mime_decode_headers'],
            [45, 'iconv_mime_decode'],
            [46, 'Iconv_Strlen'],
            [47, 'iconv_strpos'],
            [48, 'iconv_strrpos'],
            [49, 'iconv_substr'],

            [51, 'mb_check_encoding'],
            [52, 'MB_chr'],
            [53, 'mb_convert_case'],
            [54, 'mb_convert_encoding', '$from_encoding'],
            [55, 'mb_convert_kana'],
            [56, 'mb_decode_numericentity'],
            [57, 'mb_encode_numericentity'],
            [58, 'mb_ord'],
            [59, 'mb_scrub'],
            [60, 'mb_strcut'],
            [61, 'mb_stripos'],
            [62, 'mb_stristr'],
            [63, 'mb_strlen'],
            [64, 'mb_strpos'],
            [65, 'mb_strrchr'],
            [66, 'mb_strrichr'],
            [67, 'mb_strripos'],
            [68, 'mb_strrpos'],
            [69, 'mb_strstr'],
            [70, 'mb_strtolower'],
            [71, 'mb_strtoupper'],
            [72, 'mb_strwidth'],
            [73, 'mb_substr_count'],
            [74, 'mb_substr'],
        ];
    }


    /**
     * Test that there are no false positives.
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '5.4-7.0');

        // No errors expected on the first 40 lines.
        for ($line = 1; $line <= 40; $line++) {
            $this->assertNoViolation($file, $line);
        }
    }


    /**
     * Test the special handling of calls to iconv_mime_encode().
     *
     * @dataProvider dataIconvMimeEncode
     *
     * @param int    $line    Line number where the error should occur.
     * @param string $missing The preferences which are missing.
     * @param string $type    Whether an error or a warning is expected. Defaults to 'error'.
     *
     * @return void
     */
    public function testIconvMimeEncode($line, $missing, $type = 'error')
    {
        $file  = $this->sniffFile(__FILE__, '5.4-7.0');
        $error = 'The default value of the %s parameter index for iconv_mime_encode() was changed from ISO-8859-1 to UTF-8 in PHP 5.6';
        $error = \sprintf($error, $missing);

        if ($type === 'error') {
            $this->assertError($file, $line, $error);
        } else {
            $this->assertWarning($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testIconvMimeEncode()
     *
     * @return array
     */
    public static function dataIconvMimeEncode()
    {
        return [
            [91, '$options[\'input/output-charset\']'],
            [92, '$options[\'input/output-charset\']', 'warning'],
            [96, '$options[\'output-charset\']'],
            [106, '$options[\'input-charset\']'],
            [115, '$options[\'input-charset\']'],
            [122, '$options[\'input/output-charset\']'],
            [123, '$options[\'input/output-charset\']'],
        ];
    }


    /**
     * Test that there are no false positives.
     *
     * @return void
     */
    public function testNoFalsePositivesIconvMimeEncode()
    {
        $file = $this->sniffFile(__FILE__, '5.4-7.0');

        // No errors expected on line 79 - 89.
        for ($line = 79; $line <= 89; $line++) {
            $this->assertNoViolation($file, $line);
        }
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @dataProvider dataNoViolationsInFileOnValidVersion
     *
     * @param string $testVersion The testVersion to use.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion($testVersion)
    {
        $file = $this->sniffFile(__FILE__, $testVersion);
        $this->assertNoViolation($file);
    }

    /**
     * Data provider.
     *
     * @see testNoViolationsInFileOnValidVersion()
     *
     * @return array
     */
    public static function dataNoViolationsInFileOnValidVersion()
    {
        return [
            ['-5.5'],
            ['5.6-'],
        ];
    }
}
