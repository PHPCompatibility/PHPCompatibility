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
     * @dataProvider dataDeprecated
     *
     * @param string $version    Target version
     * @param string $constant   Class constant
     * @param int    $lineNumber The line number of the violation
     *
     * @return void
     */
    public function testDeprecated($version, $constant, $lineNumber)
    {
        $file            = $this->sniffFile(__FILE__, $version);
        $expectedMessage = sprintf('Class constant %s is deprecated since PHP %s.', $constant, $version);
        $this->assertWarning($file, $lineNumber, $expectedMessage);
    }

    /**
     * Data provider.
     *
     * @return array
     * @see    testDeprecated()
     */
    public static function dataDeprecated()
    {
        return [
            ['8.3', 'NumberFormatter::TYPE_CURRENCY', 3],
        ];
    }

    /**
     * Ensure NO messages when found in supported versions.
     * Ensure NO false positives due to misleading syntax
     *
     * @dataProvider dataNoViolations
     *
     * @param string $version    Target version
     * @param int    $lineNumber The line number of the violation
     *
     * @return void
     */
    public function testNoViolations($version, $lineNumber)
    {
        $file = $this->sniffFile(__FILE__, $version);
        $this->assertNoViolation($file, $lineNumber);
    }

    /**
     * Data provider.
     *
     * @return array
     * @see    testNoViolations()
     */
    public static function dataNoViolations()
    {
        return [
            ['8.2', 3],
            ['8.3', 4],
        ];
    }
}
