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
     * @param string $method      Method name
     * @param string $alternative Alternative proposed
     * @param int[]  $lineNumbers The line numbers of the violation
     *
     * @return void
     */
    public function testSoftDeprecations($method, $alternative, $lineNumbers)
    {
        $file            = $this->sniffFile(__FILE__, '8.5');
        $expectedMessage = sprintf(
            'Magic method %s is maintained for backward compatibility since PHP 8.5. Use %s instead.',
            $method,
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
            ['__sleep()', '__serialize', [47, 53, 59, 64]],
            ['__wakeup()', '__unserialize', [48, 54, 60, 65]],
        ];
    }

    /**
     * Verify there are no false positives on valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param string $method      Method name
     * @param int[]  $lineNumbers The line numbers of the violation
     *
     * @return void
     */
    public function testNoFalsePositives($method, $lineNumbers)
    {
        $file = $this->sniffFile(__FILE__, '8.5');
        foreach ($lineNumbers as $lineNumber) {
            $this->assertNoViolation($file, $lineNumber);
        }
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public static function dataNoFalsePositives()
    {
        return [
            // misleading syntax
            ['__sleep()', [5, 6, 7]],

            // inapplicable contexts
            ['__sleep()', [12, 17, 23, 33, 39]],
            ['__wakeup()', [13, 18, 24, 34, 40]],
        ];
    }

    /**
     * Verify no notices are thrown at all on PHP versions on which the syntax is supported.
     *
     * @return void
     */
    public function testNoViolationsOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertNoViolation($file);
    }
}
