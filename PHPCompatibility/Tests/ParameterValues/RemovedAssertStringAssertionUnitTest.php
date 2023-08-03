<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedAssertStringAssertion sniff.
 *
 * @group removedAssertStringAssertion
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedAssertStringAssertionSniff
 *
 * @since 10.0.0
 */
class RemovedAssertStringAssertionUnitTest extends BaseSniffTestCase
{

    /**
     * Test the sniff correctly detects assertions passed as strings.
     *
     * @dataProvider dataRemovedAssertStringAssertion
     *
     * @param int $line Line number where the warning should occur.
     *
     * @return void
     */
    public function testRemovedAssertStringAssertion($line)
    {
        $file  = $this->sniffFile(__FILE__, '7.2');
        $error = 'Using a string as the assertion passed to assert() is deprecated since PHP 7.2';
        $this->assertWarning($file, $line, $error);

        $file  = $this->sniffFile(__FILE__, '8.0');
        $error = 'Using a string as the assertion passed to assert() is deprecated since PHP 7.2 and removed since PHP 8.0';
        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testRemovedAssertStringAssertion()
     *
     * @return array
     */
    public static function dataRemovedAssertStringAssertion()
    {
        return [
            [18],
            [19],
            [20],
            [25],
        ];
    }


    /**
     * Verify there are no false positives on code this sniff should ignore.
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '7.2');

        // No errors expected on the first 16 lines.
        for ($line = 1; $line <= 16; $line++) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.1');
        $this->assertNoViolation($file);
    }
}
