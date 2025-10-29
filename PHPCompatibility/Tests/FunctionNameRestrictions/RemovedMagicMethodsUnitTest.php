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

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedMagicMethodsSniff sniff.
 *
 * @group removedMagicMethods
 * @group functionNameRestrictions
 *
 * @covers \PHPCompatibility\Sniffs\FunctionNameRestrictions\RemovedMagicMethodsSniff
 *
 * @since 10.0.0
 */
final class RemovedMagicMethodsUnitTest extends BaseSniffTestCase
{

    /**
     * testViolation
     *
     * @dataProvider dataSoftDeprecations
     *
     * @param string $version     Target version
     * @param string $method      Method name
     * @param string $alternative Alternative proposed
     * @param int[]  $lineNumbers The line numbers of the violation
     *
     * @return void
     */
    public function testSoftDeprecations($version, $method, $alternative, $lineNumbers)
    {
        $file            = $this->sniffFile(__FILE__, $version);
        $expectedMessage = sprintf(
            'Magic method %s is maintained for backward compatibility since PHP %s. Use %s instead.',
            $method,
            $version,
            $alternative
        );
        foreach ($lineNumbers as $lineNumber) {
            $this->assertWarning($file, $lineNumber, $expectedMessage);
        }
    }

    /**
     * Data provider.
     *
     * @see testSoftDeprecations()
     *
     * @return array
     */
    public static function dataSoftDeprecations()
    {
        return [
            ['8.5', '__sleep()', '__serialize', [13, 19, 30]],
            ['8.5', '__wakeup()', '__unserialize', [14, 20, 31]],
        ];
    }

    /**
     * testViolation
     *
     * @dataProvider dataNoViolation
     *
     * @param string $version     Target version
     * @param string $method      Method name
     * @param int[]  $lineNumbers The line numbers of the violation
     *
     * @return void
     */
    public function testNoViolation($version, $method, $lineNumbers)
    {
        $file = $this->sniffFile(__FILE__, $version);
        foreach ($lineNumbers as $lineNumber) {
            $this->assertNoViolation($file, $lineNumber);
        }
    }

    /**
     * Data provider.
     *
     * @see testSoftDeprecations()
     *
     * @return array
     */
    public static function dataNoViolation()
    {
        return [
            // Pre-deprecation/removal
            ['8.4', '__sleep()', [13]],
            ['8.4', '__wakeup()', [14]],
            // Post-deprecation/removal
            ['8.5', '__sleep()', [5, 25, 38, 44]],
            ['8.5', '__wakeup()', [6, 26, 39, 45]],
        ];
    }
}
