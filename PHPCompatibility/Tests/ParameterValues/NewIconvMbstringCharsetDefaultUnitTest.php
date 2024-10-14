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
 * Test the NewIconvMbstringCharsetDefault sniff.
 *
 * @group newIconvMbstringCharsetDefault
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\NewIconvMbstringCharsetDefaultSniff
 *
 * @since 9.3.0
 */
class NewIconvMbstringCharsetDefaultUnitTest extends BaseSniffTestCase
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
            [41, 'iconv_mime_decode_headers'],
            [42, 'iconv_mime_decode'],
            [43, 'Iconv_Strlen'],
            [44, 'iconv_strpos'],
            [45, 'iconv_strrpos'],
            [46, 'iconv_substr'],

            [48, 'mb_check_encoding'],
            [49, 'mb_convert_case'],
            [50, 'mb_convert_encoding', '$from_encoding'],
            [51, 'mb_convert_kana'],
            [52, 'mb_decode_numericentity'],
            [53, 'mb_encode_numericentity'],
            [54, 'mb_strcut'],
            [55, 'mb_stripos'],
            [56, 'mb_stristr'],
            [57, 'mb_strlen'],
            [58, 'mb_strpos'],
            [59, 'mb_strrchr'],
            [60, 'mb_strrichr'],
            [61, 'mb_strripos'],
            [62, 'mb_strrpos'],
            [63, 'mb_strstr'],
            [64, 'mb_strtolower'],
            [65, 'mb_strtoupper'],
            [66, 'mb_strwidth'],
            [67, 'mb_substr_count'],
            [68, 'mb_substr'],
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

        // No errors expected on the first 37 lines.
        for ($line = 1; $line <= 37; $line++) {
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
            [85, '$options[\'input/output-charset\']'],
            [86, '$options[\'input/output-charset\']', 'warning'],
            [90, '$options[\'output-charset\']'],
            [100, '$options[\'input-charset\']'],
            [109, '$options[\'input-charset\']'],
            [116, '$options[\'input/output-charset\']'],
            [117, '$options[\'input/output-charset\']'],
            [122, '$options[\'input/output-charset\']', 'warning'],
            [128, '$options[\'input/output-charset\']', 'warning'],
            [137, '$options[\'input-charset\']'],
            [137, '$options[\'output-charset\']'],
            [159, '$options[\'input-charset\']'],
            [169, '$options[\'input-charset\']', 'warning'],
            [169, '$options[\'output-charset\']', 'warning'],
            [187, '$options[\'output-charset\']', 'warning'],
            [196, '$options[\'input-charset\']', 'warning'],
        ];
    }


    /**
     * Test that there are no false positives.
     *
     * @dataProvider dataNoFalsePositivesIconvMimeEncode
     *
     * @param int $line Line number.
     *
     * @return void
     */
    public function testNoFalsePositivesIconvMimeEncode($line)
    {
        $file = $this->sniffFile(__FILE__, '5.4-7.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesIconvMimeEncode()
     *
     * @return array
     */
    public static function dataNoFalsePositivesIconvMimeEncode()
    {
        $data = [];

        // No errors expected on line 73 - 83.
        for ($line = 73; $line <= 83; $line++) {
            $data[] = [$line];
        }

        $data[] = [132];

        for ($line = 146; $line <= 155; $line++) {
            $data[] = [$line];
        }

        for ($line = 174; $line <= 182; $line++) {
            $data[] = [$line];
        }

        return $data;
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
