<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Miscellaneous;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewPHPOpenTagEOF sniff.
 *
 * @group newPHPOpenTagEOF
 * @group miscellaneous
 *
 * @covers \PHPCompatibility\Sniffs\Miscellaneous\NewPHPOpenTagEOFSniff
 *
 * @since 9.3.0
 */
class NewPHPOpenTagEOFUnitTest extends BaseSniffTest
{

    /**
     * Sprintf template for the names of the numbered test case files.
     *
     * @var string
     */
    const TEST_FILE = 'NewPHPOpenTagEOFUnitTest.%d.inc';

    /**
     * Test detection of stand alone PHP open tag at end of file.
     *
     * @dataProvider dataNewPHPOpenTagEOF
     *
     * @param int $fileNumber The number of the test case file.
     * @param int $line       The line number.
     *
     * @return void
     */
    public function testNewPHPOpenTagEOF($fileNumber, $line)
    {
        $fileName = __DIR__ . '/' . \sprintf(self::TEST_FILE, $fileNumber);

        $file = $this->sniffFile($fileName, '7.3');
        $this->assertError($file, $line, 'A PHP open tag at the end of a file, without trailing newline, was not supported in PHP 7.3 or earlier and would result in a syntax error or be interpreted as a literal string');
    }

    /**
     * Data provider.
     *
     * @see testNewPHPOpenTagEOF()
     *
     * @return array
     */
    public function dataNewPHPOpenTagEOF()
    {
        return [
            [4, 1],
            [5, 13],
            [6, 4],
            [7, 6],
        ];
    }


    /**
     * Verify no false positives are thrown for non-violation open tags in a file
     * containing multiple open tags.
     *
     * @dataProvider dataNoFalsePositivesOnLine
     *
     * @param int $fileNumber The number of the test case file.
     * @param int $line       The line number.
     *
     * @return void
     */
    public function testNoFalsePositivesOnLine($fileNumber, $line)
    {
        $fileName = __DIR__ . '/' . \sprintf(self::TEST_FILE, $fileNumber);

        $file = $this->sniffFile($fileName, '7.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesOnLine()
     *
     * @return array
     */
    public function dataNoFalsePositivesOnLine()
    {
        return [
            [5, 1],
            [5, 8],
            [6, 1],
            [7, 1],
        ];
    }


    /**
     * Verify no false positives are thrown for valid files.
     *
     * @dataProvider dataNoFalsePositivesOnFile
     *
     * @param int $fileNumber The number of the test case file.
     *
     * @return void
     */
    public function testNoFalsePositivesOnFile($fileNumber)
    {
        $fileName = __DIR__ . '/' . \sprintf(self::TEST_FILE, $fileNumber);

        $file = $this->sniffFile($fileName, '7.3');
        $this->assertNoViolation($file);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesOnFile()
     *
     * @return array
     */
    public function dataNoFalsePositivesOnFile()
    {
        return [
            [1],
            [2],
            [3],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @dataProvider dataNoViolationsInFileOnValidVersion
     *
     * @param int $fileNumber The number of the test case file.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion($fileNumber)
    {
        $fileName = __DIR__ . '/' . \sprintf(self::TEST_FILE, $fileNumber);

        $file = $this->sniffFile($fileName, '7.4');
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
        $data = [
            [1],
            [2],
            [3],
            [4],
            [5],
            [6],
            [7],
        ];

        return $data;
    }
}
