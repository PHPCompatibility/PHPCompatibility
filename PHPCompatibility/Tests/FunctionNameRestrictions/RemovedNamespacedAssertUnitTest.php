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
 * @covers \PHPCompatibility\Sniff::determineNamespace
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
    public function testIsDeprecated($testFile, $line)
    {
        $file = $this->sniffFile(__DIR__ . '/' . $testFile, '7.3');
        $this->assertWarning($file, $line, 'Declaring a free-standing function called assert() is deprecated since PHP 7.3');
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
        return array(
            array(self::TEST_FILE, 22),
            array(self::TEST_FILE_NAMESPACED, 5),
        );
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
        return array(
            array(self::TEST_FILE, 3),
            array(self::TEST_FILE, 6),
            array(self::TEST_FILE, 10),
            array(self::TEST_FILE, 14),
            array(self::TEST_FILE, 18),
            array(self::TEST_FILE_NAMESPACED, 8),
            array(self::TEST_FILE_NAMESPACED, 12),
            array(self::TEST_FILE_NAMESPACED, 16),
            array(self::TEST_FILE_NAMESPACED, 20),
        );
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
