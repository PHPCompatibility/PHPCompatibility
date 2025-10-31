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
     * Ensure a warning message when found in versions that sof-deprecated the magic method.
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
            ['8.5', '__sleep()', '__serialize', [47, 53, 59, 64]],
            ['8.5', '__wakeup()', '__unserialize', [48, 54, 60, 65]],
        ];
    }

    /**
     * Ensure no false positives due to misleading syntax out excluded scopes.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param string $version     Target version
     * @param string $method      Method name
     * @param int[]  $lineNumbers The line numbers of the violation
     *
     * @return void
     */
    public function testNoFalsePositives($version, $method, $lineNumbers)
    {
        $file = $this->sniffFile(__FILE__, $version);
        foreach ($lineNumbers as $lineNumber) {
            $this->assertNoViolation($file, $lineNumber);
        }
    }

    /**
     * All versions are invalid for the associated constants, but the snippet should not be picked up by the sniff.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public static function dataNoFalsePositives()
    {
        return [
            // misleading syntax
            ['8.4', '__sleep()', [5, 6, 7]],

            // inapplicable contexts
            ['8.4', '__sleep()', [12, 17, 23, 33, 39]],
            ['8.4', '__wakeup()', [13, 18, 24, 33, 40]],

            // parse error
            ['8.4', '__sleep()', [72]],
        ];
    }

    /**
     * Ensure no messages when found in supported versions.
     *
     * @dataProvider dataNoViolationsOnValidVersion
     *
     * @param string $version     Target version
     * @param string $method      Method name
     * @param int[]  $lineNumbers The line numbers of the violation
     *
     * @return void
     */
    public function testNoViolationsOnValidVersion($version, $method, $lineNumbers)
    {
        $file = $this->sniffFile(__FILE__, $version);
        foreach ($lineNumbers as $lineNumber) {
            $this->assertNoViolation($file, $lineNumber);
        }
    }

    /**
     * All versions are valid for the associated magic methods, and the sniff shouldn't flag warnings or errors
     *
     * @see testNoViolationsOnValidVersion()
     *
     * @return array
     */
    public static function dataNoViolationsOnValidVersion()
    {
        return [
            ['8.4', '__sleep()', [47, 53, 59, 64]],
            ['8.4', '__wakeup()', [48, 54, 60, 65]],
        ];
    }
}
