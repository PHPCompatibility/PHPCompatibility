<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionNameRestrictions;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the RemovedMagicAutoload sniff.
 *
 * @group removedMagicAutoload
 * @group functionNameRestrictions
 *
 * @covers \PHPCompatibility\Sniffs\FunctionNameRestrictions\RemovedMagicAutoloadSniff
 *
 * @since 8.1.0
 */
class RemovedMagicAutoloadUnitTest extends BaseSniffTest
{

    /**
     * The name of the main test case file.
     *
     * @var string
     */
    const TEST_FILE = 'RemovedMagicAutoloadUnitTest.1.inc';

    /**
     * The name of a secondary test case file to test against false positives
     * for namespaced function declarations.
     *
     * @var string
     */
    const TEST_FILE_NAMESPACED = 'RemovedMagicAutoloadUnitTest.2.inc';

    /**
     * Test __autoload deprecation not causing issue in 7.1.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE, '7.1');
        $this->assertNoViolation($file);
    }

    /**
     * Test detection of __autoload declarations for which support has been deprecated/removed.
     *
     * @dataProvider dataRemovedMagicAutoload
     *
     * @param int $line The line number where the error should occur.
     *
     * @return void
     */
    public function testRemovedMagicAutoload($line)
    {
        $file = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE, '7.2');
        $this->assertWarning($file, $line, 'Specifying an autoloader using an __autoload() function is deprecated since PHP 7.2');

        $file = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE, '8.0');
        $this->assertError($file, $line, 'Specifying an autoloader using an __autoload() function is deprecated since PHP 7.2 and no longer supported since PHP 8.0');
    }

    /**
     * Data provider.
     *
     * @see testRemovedMagicAutoload()
     *
     * @return array
     */
    public static function dataRemovedMagicAutoload()
    {
        return [
            [3],
            [32],
        ];
    }

    /**
     * Test not affected __autoload declarations.
     *
     * @dataProvider dataIsNotAffected
     *
     * @param string $testFile The file to test.
     * @param int    $line     The line number where the error should occur.
     *
     * @return void
     */
    public function testIsNotAffected($testFile, $line)
    {
        $file = $this->sniffFile(__DIR__ . '/' . $testFile, '7.2');
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataIsNotAffected
     *
     * @see testIsNotAffected()
     *
     * @return array
     */
    public static function dataIsNotAffected()
    {
        return [
            [self::TEST_FILE, 8],
            [self::TEST_FILE, 14],
            [self::TEST_FILE, 18],
            [self::TEST_FILE, 24],
            [self::TEST_FILE, 39],
            [self::TEST_FILE_NAMESPACED, 5],
            [self::TEST_FILE_NAMESPACED, 10],
            [self::TEST_FILE_NAMESPACED, 16],
            [self::TEST_FILE_NAMESPACED, 20],
            [self::TEST_FILE_NAMESPACED, 26],
            [self::TEST_FILE_NAMESPACED, 32],
        ];
    }
}
