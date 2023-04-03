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
 * Test the ForbiddenSessionModuleNameUser sniff.
 *
 * @group forbiddenSessionModuleNameUser
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\ForbiddenSessionModuleNameUserSniff
 *
 * @since 10.0.0
 */
class ForbiddenSessionModuleNameUserUnitTest extends BaseSniffTest
{

    /**
     * The name of the primary test case file.
     *
     * @var string
     */
    const TEST_FILE = 'ForbiddenSessionModuleNameUserUnitTest.1.inc';

    /**
     * The name of a secondary test case file containing PHP 7.3+ indented heredocs.
     *
     * @var string
     */
    const TEST_FILE_PHP73HEREDOCS = 'ForbiddenSessionModuleNameUserUnitTest.2.inc';

    /**
     * Verify detection of the issue.
     *
     * @dataProvider dataForbiddenSessionModuleNameUser
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testForbiddenSessionModuleNameUser($line)
    {
        $file = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE, '7.2');
        $this->assertError($file, $line, 'Passing "user" as the $module to session_module_name() is not allowed since PHP 7.2.');
    }

    /**
     * Data provider.
     *
     * @see testForbiddenSessionModuleNameUser()
     *
     * @return array
     */
    public function dataForbiddenSessionModuleNameUser()
    {
        return [
            [16],
            [17],
            [18],
        ];
    }


    /**
     * Verify there are no false positives on valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE, '7.2');
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

        // No errors expected on the first 14 lines.
        for ($line = 1; $line <= 14; $line++) {
            $cases[] = [$line];
        }

        $cases[] = [24];
        $cases[] = [25];
        $cases[] = [26];

        return $cases;
    }


    /**
     * Verify correct detection in combination with indented heredoc/nowdocs.
     *
     * @dataProvider dataForbiddenSessionModuleNameUserInIndentedHeredoc
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testForbiddenSessionModuleNameUserInIndentedHeredoc($line)
    {
        if (\PHP_VERSION_ID < 70300) {
            $this->markTestSkipped('Test code involving PHP 7.3 heredocs will not tokenize correctly on PHP < 7.3');
        }

        $file = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE_PHP73HEREDOCS, '7.2');
        $this->assertError($file, $line, 'Passing "user" as the $module to session_module_name() is not allowed since PHP 7.2.');
    }

    /**
     * Data provider.
     *
     * @see testForbiddenSessionModuleNameUserInIndentedHeredoc()
     *
     * @return array
     */
    public function dataForbiddenSessionModuleNameUserInIndentedHeredoc()
    {
        return [
            [16],
        ];
    }


    /**
     * Verify there are no false positives on valid code with indented heredoc/nowdocs.
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

        $file = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE_PHP73HEREDOCS, '7.2');
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

        // No errors expected on the first 14 lines.
        for ($line = 1; $line <= 14; $line++) {
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

        $file = $this->sniffFile(__DIR__ . '/' . $testFile, '7.1');
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
