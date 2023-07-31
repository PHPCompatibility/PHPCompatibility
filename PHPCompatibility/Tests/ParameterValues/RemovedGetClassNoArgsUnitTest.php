<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2022 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedGetClassNoArgs sniff.
 *
 * @group removedGetClassNoArgs
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedGetClassNoArgsSniff
 *
 * @since 10.0.0
 */
final class RemovedGetClassNoArgsUnitTest extends BaseSniffTestCase
{

    /**
     * Test receiving an expected warning for calling get_[parent_]class() without arguments.
     *
     * @dataProvider dataRemovedGetClassNoArgs
     *
     * @param int    $line         Line number where the warning should occur.
     * @param string $functionName Expected function name.
     * @param string $argName      Expected function parameter name.
     *
     * @return void
     */
    public function testRemovedGetClassNoArgs($line, $functionName, $argName)
    {
        $file    = $this->sniffFile(__FILE__, '8.3');
        $warning = \sprintf('Calling %s() without the $%s argument is deprecated since PHP 8.3.', $functionName, $argName);
        $this->assertWarning($file, $line, $warning);
    }

    /**
     * Data provider.
     *
     * @see testRemovedGetClassNoArgs()
     *
     * @return array
     */
    public static function dataRemovedGetClassNoArgs()
    {
        return [
            [30, 'get_class', 'object'],
            [31, 'get_parent_class', 'object_or_class'],
            [32, 'get_class', 'object'],
            [33, 'get_parent_class', 'object_or_class'],
            [38, 'get_class', 'object'],
            [39, 'get_parent_class', 'object_or_class'],
        ];
    }


    /**
     * Test that there are no false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file, $line);
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
        $data = [];

        // No errors expected on the first 26 lines.
        for ($line = 1; $line <= 26; $line++) {
            $data[] = [$line];
        }

        return $data;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.2');
        $this->assertNoViolation($file);
    }
}
