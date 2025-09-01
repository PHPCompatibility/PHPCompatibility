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
 * Test the NewAssertCustomException sniff.
 *
 * @group newAssertCustomException
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\NewAssertCustomExceptionSniff
 *
 * @since 10.0.0
 */
final class NewAssertCustomExceptionUnitTest extends BaseSniffTestCase
{

    /**
     * Test the sniff correctly detects an custom exception being passed as the description.
     *
     * @dataProvider dataNewAssertCustomException
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testNewAssertCustomException($line)
    {
        $file  = $this->sniffFile(__FILE__, '5.6');
        $error = 'Passing a Throwable object as the second parameter to assert() is not supported in PHP 5.6 or earlier.';

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testNewAssertCustomException()
     *
     * @return array
     */
    public static function dataNewAssertCustomException()
    {
        return [
            [18],
            [19],
        ];
    }


    /**
     * Verify there are no false positives on code this sniff should ignore.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '5.6');
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
        $cases = [];

        // No errors expected on the first 15 lines.
        for ($line = 1; $line <= 15; $line++) {
            $cases[] = [$line];
        }

        for ($line = 21; $line <= 28; $line++) {
            $cases[] = [$line];
        }

        return $cases;
    }

    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertNoViolation($file);
    }
}
