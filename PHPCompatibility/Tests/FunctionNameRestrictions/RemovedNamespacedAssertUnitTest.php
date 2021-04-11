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
 * Test the RemovedNamespacedAssert sniff.
 *
 * @group removedNamespacedAssert
 * @group functionNameRestrictions
 *
 * @covers \PHPCompatibility\Sniffs\FunctionNameRestrictions\RemovedNamespacedAssertSniff
 *
 * @since 9.0.0
 */
class RemovedNamespacedAssertUnitTest extends BaseSniffTest
{

    /**
     * The name of the primary test case file containing code in the global namespace.
     *
     * @var string
     */
    const TEST_FILE = 'RemovedNamespacedAssertUnitTest.1.inc';

    /**
     * The name of a secondary test case file containing code in a unscoped namespace.
     *
     * @var string
     */
    const TEST_FILE_NAMESPACED = 'RemovedNamespacedAssertUnitTest.2.inc';

    /**
     * Test deprecation of namespaced free-standing assert() function declaration.
     *
     * @dataProvider dataIsDeprecated
     *
     * @param string $testFile The file to test.
     * @param int    $line     The line number where the error should occur.
     *
     * @return void
     */
    public function testRemovedNamespacedAssert($testFile, $line)
    {
        $error = 'Declaring a namespaced function called assert() is deprecated since PHP 7.3';
        $file  = $this->sniffFile(__DIR__ . '/' . $testFile, '7.3');
        $this->assertWarning($file, $line, $error);

        $error .= ' and will throw a fatal error since PHP 8.0';
        $file   = $this->sniffFile(__DIR__ . '/' . $testFile, '8.0');
        $this->assertError($file, $line, $error);
    }

    /**
     * dataIsDeprecated
     *
     * @see testIsDeprecated()
     *
     * @return array
     */
    public function dataIsDeprecated()
    {
        return [
            [self::TEST_FILE, 22],
            [self::TEST_FILE_NAMESPACED, 5],
            [self::TEST_FILE_NAMESPACED, 25],
            [self::TEST_FILE_NAMESPACED, 30],
        ];
    }

    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param string $testFile The file to test.
     * @param int    $line     The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($testFile, $line)
    {
        $file = $this->sniffFile(__DIR__ . '/' . $testFile, '7.3');
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
        return [
            [self::TEST_FILE, 3],
            [self::TEST_FILE, 6],
            [self::TEST_FILE, 10],
            [self::TEST_FILE, 14],
            [self::TEST_FILE, 18],
            [self::TEST_FILE, 27],
            [self::TEST_FILE, 32],
            [self::TEST_FILE_NAMESPACED, 8],
            [self::TEST_FILE_NAMESPACED, 12],
            [self::TEST_FILE_NAMESPACED, 16],
            [self::TEST_FILE_NAMESPACED, 20],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE, '7.2');
        $this->assertNoViolation($file);
    }
}
