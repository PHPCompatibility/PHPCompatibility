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
 * Test the NewHTMLEntitiesEncoding sniff.
 *
 * @group newHTMLEntitiesEncodingDefault
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\NewHTMLEntitiesEncodingDefaultSniff
 *
 * @since 9.3.0
 */
class NewHTMLEntitiesEncodingDefaultUnitTest extends BaseSniffTestCase
{

    /**
     * testNewHTMLEntitiesEncodingDefault
     *
     * @dataProvider dataNewHTMLEntitiesEncodingDefault
     *
     * @param int    $line         Line number where the error should occur.
     * @param string $functionName The name of the function called.
     *
     * @return void
     */
    public function testNewHTMLEntitiesEncodingDefault($line, $functionName)
    {
        $file  = $this->sniffFile(__FILE__, '5.3-5.4');
        $error = "The default value of the \$encoding parameter for {$functionName}() was changed from ISO-8859-1 to UTF-8 in PHP 5.4";

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testNewHTMLEntitiesEncodingDefault()
     *
     * @return array
     */
    public static function dataNewHTMLEntitiesEncodingDefault()
    {
        return [
            [10, 'htmlentities'],
            [11, 'htmlspecialchars'],
            [12, 'HTML_entity_decode'],
            [13, 'get_html_translation_table'],
        ];
    }


    /**
     * Test that there are no false positives.
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '5.3-5.4');

        // No errors expected on the first 8 lines.
        for ($line = 1; $line <= 8; $line++) {
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
            ['5.0-5.3'],
            ['5.4-'],
        ];
    }
}
