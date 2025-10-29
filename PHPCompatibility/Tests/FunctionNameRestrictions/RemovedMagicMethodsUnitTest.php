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
     * @dataProvider dataViolation
     *
     * @param int $lineNumber The line number of the violation
     * @param string $method
     * @param string $alternative
     *
     * @return void
     */
    public function testViolation($lineNumber, $method, $alternative)
    {
        $file = $this->sniffFile(__FILE__, '8.5');

        $expectedMessage = sprintf(
            'Magic method %s is soft-deprecated since PHP 8.5. Use %s instead.',
            $method,
            $alternative
        );
        $this->assertWarning($file, $lineNumber, $expectedMessage);
    }

    /**
     * Data provider.
     *
     * @see testViolation()
     *
     * @return array
     */
    public static function dataViolation()
    {
        return [
            [5, '__sleep()', '__serialize'],
            [6, '__wakeup()', '__unserialize'],
        ];
    }
}
