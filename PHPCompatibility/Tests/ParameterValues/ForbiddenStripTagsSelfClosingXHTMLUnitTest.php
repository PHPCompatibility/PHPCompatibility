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
 * Test the ForbiddenStripTagsSelfClosingXHTML sniff.
 *
 * @group forbiddenStripTagsSelfClosingXHTML
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\ForbiddenStripTagsSelfClosingXHTMLSniff
 *
 * @since 9.3.0
 */
class ForbiddenStripTagsSelfClosingXHTMLUnitTest extends BaseSniffTest
{

    /**
     * The name of the primary test case file.
     *
     * @var string
     */
    const TEST_FILE = 'ForbiddenStripTagsSelfClosingXHTMLUnitTest.1.inc';

    /**
     * The name of a secondary test case file containing PHP 7.3+ indented heredocs.
     *
     * @var string
     */
    const TEST_FILE_PHP73HEREDOCS = 'ForbiddenStripTagsSelfClosingXHTMLUnitTest.2.inc';

    /**
     * Verify detection of the issue.
     *
     * @dataProvider dataForbiddenStripTagsSelfClosingXHTML
     *
     * @param int  $line       Line number where the error should occur.
     * @param bool $paramValue The parameter value detected.
     *
     * @return void
     */
    public function testForbiddenStripTagsSelfClosingXHTML($line, $paramValue)
    {
        $file  = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE, '5.4');
        $error = 'Self-closing XHTML tags are ignored. Only non-self-closing tags should be used in the strip_tags() $allowed_tags parameter since PHP 5.3.4. Found: ' . $paramValue;

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testForbiddenStripTagsSelfClosingXHTML()
     *
     * @return array
     */
    public function dataForbiddenStripTagsSelfClosingXHTML()
    {
        return [
            [14, "'<br/>'"],
            [15, "'<img/><br/>' . '<meta/><input/>'"],
            [27, "<<<EOD\n<img/><br/>" . '$extraTags' . "\nEOD"],
        ];
    }


    /**
     * Test the sniff does not throw false positives.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE, '5.4');
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

        // No errors expected on the first 12 lines.
        for ($line = 1; $line <= 12; $line++) {
            $cases[] = [$line];
        }

        // No errors expected for the _correct_ heredoc/nowdocs.
        for ($line = 17; $line <= 23; $line++) {
            $cases[] = [$line];
        }

        return $cases;
    }


    /**
     * Verify correct detection in combination with indented heredoc/nowdocs.
     *
     * @dataProvider dataForbiddenStripTagsSelfClosingXHTMLInIndentedHeredoc
     *
     * @param int  $line       Line number where the error should occur.
     * @param bool $paramValue The parameter value detected.
     *
     * @return void
     */
    public function testForbiddenStripTagsSelfClosingXHTMLInIndentedHeredoc($line, $paramValue)
    {
        if (\PHP_VERSION_ID < 70300) {
            $this->markTestSkipped('Test code involving PHP 7.3 heredocs will not tokenize correctly on PHP < 7.3');
        }

        $file  = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE_PHP73HEREDOCS, '5.4');
        $error = 'Self-closing XHTML tags are ignored. Only non-self-closing tags should be used in the strip_tags() $allowed_tags parameter since PHP 5.3.4. Found: ' . $paramValue;

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testForbiddenStripTagsSelfClosingXHTMLInIndentedHeredoc()
     *
     * @return array
     */
    public function dataForbiddenStripTagsSelfClosingXHTMLInIndentedHeredoc()
    {
        return [
            [18, "<<<'EOD'\n    <meta/><input/>\n    EOD"],
        ];
    }


    /**
     * Test the sniff does not throw false positives.
     *
     * @dataProvider dataNoFalsePositivesInIndentedHeredoc
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositivesInIndentedHeredoc($line)
    {
        if (\PHP_VERSION_ID < 70300) {
            $this->markTestSkipped('Test code involving PHP 7.3 heredocs will not tokenize correctly on PHP < 7.3');
        }

        $file = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE_PHP73HEREDOCS, '5.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesInIndentedHeredoc()
     *
     * @return array
     */
    public function dataNoFalsePositivesInIndentedHeredoc()
    {
        $cases = [];

        // No errors expected on the first 15 lines.
        for ($line = 1; $line <= 15; $line++) {
            $cases[] = [$line];
        }

        return $cases;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @dataProvider dataTestFiles
     *
     * @param string $testFile File name for the test case file to use.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion($testFile)
    {
        if ($testFile === self::TEST_FILE_PHP73HEREDOCS && \PHP_VERSION_ID < 70300) {
            $this->markTestSkipped('Test code involving PHP 7.3 heredocs will not tokenize correctly on PHP < 7.3');
        }

        $file = $this->sniffFile(__DIR__ . '/' . $testFile, '5.3');
        $this->assertNoViolation($file);
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function dataTestFiles()
    {
        return [
            [self::TEST_FILE],
            [self::TEST_FILE_PHP73HEREDOCS],
        ];
    }
}
