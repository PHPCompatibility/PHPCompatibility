<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2021 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewHTMLEntitiesFlagsDefault sniff.
 *
 * @group newHTMLEntitiesFlagsDefault
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\NewHTMLEntitiesFlagsDefaultSniff
 *
 * @since 10.0.0
 */
class NewHTMLEntitiesFlagsDefaultUnitTest extends BaseSniffTest
{

    /**
     * Verify that an error gets thrown when the $flags parameter is not set and both PHP < 8.1
     * as well as PHP 8.1+ needs to be supported.
     *
     * @dataProvider dataNewHTMLEntitiesFlagsDefault
     *
     * @param int    $line         Line number where the error should occur.
     * @param string $functionName The name of the function called.
     *
     * @return void
     */
    public function testNewHTMLEntitiesFlagsDefault($line, $functionName)
    {
        $file  = $this->sniffFile(__FILE__, '8.0-8.1');
        $error = "The default value of the \$flags parameter for {$functionName}() was changed from ENT_COMPAT to ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 in PHP 8.1";

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testNewHTMLEntitiesFlagsDefault()
     *
     * @return array
     */
    public function dataNewHTMLEntitiesFlagsDefault()
    {
        return [
            [11, 'htmlentities'],
            [12, 'htmlspecialchars'],
            [13, 'HTML_entity_decode'],
            [14, 'htmlspecialchars_decode'],
            [15, 'get_html_translation_table'],
        ];
    }


    /**
     * Verify there are no false positives on code this sniff should ignore.
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '8.0-8.1');

        // No errors expected on the first 9 lines.
        for ($line = 1; $line <= 9; $line++) {
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
    public function dataNoViolationsInFileOnValidVersion()
    {
        return [
            ['5.6-8.0'],
            ['8.1-'],
        ];
    }
}
