<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Constants;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedClassConstants sniff.
 *
 * @group removedClassConstants
 * @group constants
 *
 * @covers \PHPCompatibility\Sniffs\Constants\RemovedClassConstantsSniff
 *
 * @since 10.0.0
 */
final class RemovedClassConstantsUnitTest extends BaseSniffTestCase
{

    /**
     * Ensure a warning message when found in versions that deprecated the class constant.
     *
     * @dataProvider dataDeprecatedConstants
     *
     * @param string $version    Target version
     * @param string $constant   Class constant
     * @param int    $lineNumber The line number of the violation
     *
     * @return void
     */
    public function testDeprecatedConstants($version, $constant, $lineNumber)
    {
        $file            = $this->sniffFile(__FILE__, $version);
        $expectedMessage = sprintf('Class constant %s is deprecated since PHP %s.', $constant, $version);
        $this->assertWarning($file, $lineNumber, $expectedMessage);
    }

    /**
     * Data provider.
     *
     * @return array
     * @see    testDeprecatedConstants()
     */
    public static function dataDeprecatedConstants()
    {
        return [
            ['8.3', 'NumberFormatter::TYPE_CURRENCY', 15],
        ];
    }

    /**
     * Ensure NO false positives due to misleading syntax
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param string $version    Target version
     * @param int    $lineNumber The line number of the violation
     *
     * @return void
     */
    public function testNoFalsePositives($version, $lineNumber)
    {
        $file = $this->sniffFile(__FILE__, $version);
        $this->assertNoViolation($file, $lineNumber);
    }

    /**
     * All versions are _invalid_ for the associated constants, but the snippet should not be picked up by the sniff.
     *
     * @return array
     * @see    testNoFalsePositives()
     */
    public static function dataNoFalsePositives()
    {
        // The constant name is here for test readability purposes
        return [
            ['8.3', 'NumberFormatter::TYPE_CURRENCY', 6],
            ['8.3', 'NumberFormatter::TYPE_CURRENCY', 7],
            ['8.3', 'NumberFormatter::TYPE_CURRENCY', 8],
        ];
    }

    /**
     * Ensure NO messages when found in supported versions.
     *
     * @dataProvider dataNoViolationsOnValidVersion
     *
     * @param string $version    Target version
     * @param int    $lineNumber The line number of the violation
     *
     * @return void
     */
    public function testNoViolationsOnValidVersion($version, $lineNumber)
    {
        $file = $this->sniffFile(__FILE__, $version);
        $this->assertNoViolation($file, $lineNumber);
    }

    /**
     * All versions are _valid_ for the associated constants, and the sniff shouldn't flag warnings or errors
     *
     * @return array
     * @see    testNoViolationsOnValidVersion()
     */
    public static function dataNoViolationsOnValidVersion()
    {
        // The constant name is here for test readability purposes
        return [
            ['8.2', 'NumberFormatter::TYPE_CURRENCY', 6],
            ['8.2', 'NumberFormatter::TYPE_CURRENCY', 7],
            ['8.2', 'NumberFormatter::TYPE_CURRENCY', 8],
        ];
    }
}
