<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2022 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\TextStrings;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the RemovedDollarBraceStringEmbeds sniff.
 *
 * @group removedDollarBraceStringEmbeds
 * @group textstrings
 *
 * @covers \PHPCompatibility\Sniffs\TextStrings\RemovedDollarBraceStringEmbedsSniff
 *
 * @since 10.0.0
 */
class RemovedDollarBraceStringEmbedsUnitTest extends BaseSniffTest
{

    /**
     * The name of the primary test case file.
     *
     * @var string
     */
    const TEST_FILE = 'RemovedDollarBraceStringEmbedsUnitTest.1.inc';

    /**
     * The name of a secondary test case file containing PHP 7.3+ indented heredocs.
     *
     * @var string
     */
    const TEST_FILE_PHP73HEREDOCS = 'RemovedDollarBraceStringEmbedsUnitTest.2.inc';

    /**
     * Test that variable embeds of "type 3" - Braces after the dollar sign (“${foo}”) -
     * are correctly detected.
     *
     * @dataProvider dataRemovedDollarBraceStringEmbedsType3
     *
     * @param int    $line  The line number.
     * @param string $found The embedded variable found.
     *
     * @return void
     */
    public function testRemovedDollarBraceStringEmbedsType3($line, $found)
    {
        $file = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE, '8.2');
        $this->assertWarning($file, $line, 'Using ${var} in strings is deprecated since PHP 8.2, use {$var} instead. Found: ' . $found);
    }

    /**
     * Data provider.
     *
     * @see testRemovedDollarBraceStringEmbedsType3()
     *
     * @return array
     */
    public function dataRemovedDollarBraceStringEmbedsType3()
    {
        return [
            [57, '${foo}'],
            [58, '${foo[\'bar\']}'],
            [59, '${foo}'],
            [59, '${text}'],
            [62, '${foo}'],
            [65, '${foo}'],
        ];
    }


    /**
     * Test that variable embeds of "type 4" - Variable variables (“${expr}”, equivalent to
     * (string) ${expr}) - are correctly detected.
     *
     * @dataProvider dataRemovedDollarBraceStringEmbedsType4
     *
     * @param int    $line  The line number.
     * @param string $found The embedded expression found.
     *
     * @return void
     */
    public function testRemovedDollarBraceStringEmbedsType4($line, $found)
    {
        $file = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE, '8.2');
        $this->assertWarning($file, $line, "Using {$found} (variable variables) in strings is deprecated since PHP 8.2, use {\${expr}} instead.");
    }

    /**
     * Data provider.
     *
     * @see testRemovedDollarBraceStringEmbedsType4()
     *
     * @return array
     */
    public function dataRemovedDollarBraceStringEmbedsType4()
    {
        return [
            [68, '${$bar}'],
            [69, '${(foo)}'],
            [70, '${foo->bar}'],
            [71, '${$object->getMethod()}'],
            [72, '${(foo)}'],
            [73, '${substr(\'laruence\', 0, 2)}'],
            [75, '${foo["${bar}"]}'],
            [76, '${foo["${bar[\'baz\']}"]}'],
            [77, '${foo->{$baz}}'],
            [78, '${foo->{${\'a\'}}}'],
            [79, '${foo->{"${\'a\'}"}}'],
            [83, '${foo["${bar}"]}'],
            [84, '${foo["${bar[\'baz\']}"]}'],
            [85, '${foo->{${\'a\'}}}'],
            [89, '${(foo)}'],
        ];
    }


    /**
     * Test that variable embeds of "type 3" - Braces after the dollar sign (“${foo}”) -
     * are correctly detected in PHP 7.3+ indented heredocs.
     *
     * @dataProvider dataRemovedDollarBraceStringEmbedsType3InIndentedHeredoc
     *
     * @param int    $line  The line number.
     * @param string $found The embedded variable found.
     *
     * @return void
     */
    public function testRemovedDollarBraceStringEmbedsType3InIndentedHeredoc($line, $found)
    {
        if (\PHP_VERSION_ID < 70300) {
            $this->markTestSkipped('Test code involving PHP 7.3 heredocs will not tokenize correctly on PHP < 7.3');
        }

        $file = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE_PHP73HEREDOCS, '8.2');
        $this->assertWarning($file, $line, 'Using ${var} in strings is deprecated since PHP 8.2, use {$var} instead. Found: ' . $found);
    }

    /**
     * Data provider.
     *
     * @see testRemovedDollarBraceStringEmbedsType3InIndentedHeredoc()
     *
     * @return array
     */
    public function dataRemovedDollarBraceStringEmbedsType3InIndentedHeredoc()
    {
        return [
            [33, '${foo[\'bar\']}'],
        ];
    }


    /**
     * Test that variable embeds of "type 4" - Variable variables (“${expr}”, equivalent to
     * (string) ${expr}) - are correctly detected in PHP 7.3+ indented heredocs.
     *
     * @dataProvider dataRemovedDollarBraceStringEmbedsType4InIndentedHeredoc
     *
     * @param int    $line  The line number.
     * @param string $found The embedded expression found.
     *
     * @return void
     */
    public function testRemovedDollarBraceStringEmbedsType4InIndentedHeredoc($line, $found)
    {
        if (\PHP_VERSION_ID < 70300) {
            $this->markTestSkipped('Test code involving PHP 7.3 heredocs will not tokenize correctly on PHP < 7.3');
        }

        $file = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE_PHP73HEREDOCS, '8.2');
        $this->assertWarning($file, $line, "Using {$found} (variable variables) in strings is deprecated since PHP 8.2, use {\${expr}} instead.");
    }

    /**
     * Data provider.
     *
     * @see testRemovedDollarBraceStringEmbedsType4InIndentedHeredoc()
     *
     * @return array
     */
    public function dataRemovedDollarBraceStringEmbedsType4InIndentedHeredoc()
    {
        return [
            [39, '${$object->getMethod()}'],
            [40, '${foo["${bar[\'baz\']}"]}'],
            [41, '${foo->{${\'a\'}}}'],
        ];
    }


    /**
     * Verify the sniff does not throw false positives for valid code.
     *
     * @dataProvider dataTestFiles
     *
     * @param string $testFile File name for the test case file to use.
     * @param int    $lines    Number of lines at the top of the file for which we don't expect errors.
     *
     * @return void
     */
    public function testNoFalsePositives($testFile, $lines)
    {
        if ($testFile === self::TEST_FILE_PHP73HEREDOCS && \PHP_VERSION_ID < 70300) {
            $this->markTestSkipped('Test code involving PHP 7.3 heredocs will not tokenize correctly on PHP < 7.3');
        }

        $file = $this->sniffFile(__DIR__ . '/' . $testFile, '8.2');

        // No errors expected on the first # lines.
        for ($line = 1; $line <= $lines; $line++) {
            $this->assertNoViolation($file, $line);
        }
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

        $file = $this->sniffFile(__DIR__ . '/' . $testFile, '8.1');
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
            [self::TEST_FILE, 51],
            [self::TEST_FILE_PHP73HEREDOCS, 26],
        ];
    }
}
