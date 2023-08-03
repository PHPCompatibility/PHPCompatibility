<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Syntax;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewFlexibleHeredocNowdoc sniff.
 *
 * @group newFlexibleHeredocNowdoc
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\NewFlexibleHeredocNowdocSniff
 *
 * @since 9.0.0
 */
class NewFlexibleHeredocNowdocUnitTest extends BaseSniffTestCase
{

    /**
     * Sprintf template for the names of the numbered test case files.
     *
     * @var string
     */
    const TEST_FILE = 'NewFlexibleHeredocNowdocUnitTest.%d.inc';

    /**
     * Whether PHP 7.3+ is used.
     *
     * @var bool
     */
    protected static $php73plus;


    /**
     * Set up skip condition based on used PHP version.
     *
     * {@internal The data providers are run before the setUpClass method is run, so
     * we can't use that method for this skip condition.}
     *
     * @return bool True if PHPCS is running on PHP 7.3 or higher. False otherwise.
     */
    public static function getSetSkipCondition()
    {
        if (isset(self::$php73plus) === false) {
            self::$php73plus = false;
            // When using PHP 7.3+, the closing marker will be misidentified if the
            // body contains the heredoc/nowdoc identifier.
            if (\version_compare(\PHP_VERSION_ID, '70299', '>') === true) {
                self::$php73plus = true;
            }
        }

        return self::$php73plus;
    }


    /**
     * Test detection of indented heredoc/nowdoc closing markers.
     *
     * @dataProvider dataIndentedHeredocNowdoc
     *
     * @param int  $fileNumber      The number of the test case file.
     * @param int  $line            The line number.
     * @param bool $skipNoViolation Whether to skip the no violation test on PHP 7.3.
     *
     * @return void
     */
    public function testIndentedHeredocNowdoc($fileNumber, $line, $skipNoViolation = false)
    {
        $fileName = __DIR__ . '/' . \sprintf(self::TEST_FILE, $fileNumber);

        $file = $this->sniffFile($fileName, '7.2');
        $this->assertError($file, $line, 'Heredoc/nowdoc with an indented closing marker is not supported in PHP 7.2 or earlier.');

        if ($skipNoViolation === false || self::getSetSkipCondition() === false) {
            $file = $this->sniffFile($fileName, '7.3');
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testIndentedHeredocNowdoc()
     *
     * @return array
     */
    public static function dataIndentedHeredocNowdoc()
    {
        $data = [
            [3, 13],
            [4, 13],
            [5, 15],
            [6, 15],
            [7, 13],
        ];

        if (self::getSetSkipCondition() === true) {
            // PHP 7.3+ will misidentify the closing marker (parse error) when the identifier is in the body.
            $data[] = [2, 23, true];
            $data[] = [2, 30, true];
        }

        return $data;
    }


    /**
     * Test detection of non-stand alone heredoc/nowdoc closing markers.
     *
     * @dataProvider dataCodeAfterHeredocNowdoc
     *
     * @param int  $fileNumber      The number of the test case file.
     * @param int  $line            The line number.
     * @param bool $skipNoViolation Whether to skip the no violation test on PHP 7.3.
     *
     * @return void
     */
    public function testCodeAfterHeredocNowdoc($fileNumber, $line, $skipNoViolation = false)
    {
        $fileName = __DIR__ . '/' . \sprintf(self::TEST_FILE, $fileNumber);

        $file = $this->sniffFile($fileName, '7.2');
        $this->assertError($file, $line, 'Having code - other than a semi-colon or new line - after the closing marker of a heredoc/nowdoc is not supported in PHP 7.2 or earlier.');

        if ($skipNoViolation === false || self::getSetSkipCondition() === false) {
            $file = $this->sniffFile($fileName, '7.3');
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testCodeAfterHeredocNowdoc()
     *
     * @return array
     */
    public static function dataCodeAfterHeredocNowdoc()
    {
        $data = [
            [8, 15],
            [9, 15],
        ];

        if (self::getSetSkipCondition() === true) {
            // PHP 7.3+ will misidentify the closing marker (parse error) when the identifier is in the body.
            $data[] = [2, 12, true];
            $data[] = [2, 18, true];
            $data[] = [2, 38, true];
        }

        return $data;
    }


    /**
     * Test detection of closing marker within the heredoc/nowdoc body.
     *
     * @dataProvider dataForbiddenClosingMarkerInBody
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testForbiddenClosingMarkerInBody($line)
    {
        $fileName = __DIR__ . '/' . \sprintf(self::TEST_FILE, 2);

        $file = $this->sniffFile($fileName, '7.3');
        $this->assertError($file, $line, 'The body of a heredoc/nowdoc can not contain the heredoc/nowdoc closing marker as text at the start of a line since PHP 7.3.');

        if (self::getSetSkipCondition() === false) {
            $file = $this->sniffFile($fileName, '7.2');
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testForbiddenClosingMarkerInBody()
     *
     * @return array
     */
    public static function dataForbiddenClosingMarkerInBody()
    {
        $lines = [
            [12],
            [18],
            [23],
            [30],
            [38],
        ];

        if (self::getSetSkipCondition() === false) {
            // PHP < 7.3 can reliably throw errors for all lines in the heredoc/nowdoc containing the identifier.
            $lines[] = [40];
            $lines[] = [42];
        }

        return $lines;
    }


    /**
     * Verify no notices are thrown at all on the file containing cross-version compatible code.
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $fileName = __DIR__ . '/' . \sprintf(self::TEST_FILE, 1);

        $file = $this->sniffFile($fileName, '7.2');
        $this->assertNoViolation($file);

        $file = $this->sniffFile($fileName, '7.3');
        $this->assertNoViolation($file);
    }
}
