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
 * Test the ForbiddenGetClassNull sniff.
 *
 * @group forbiddenGetClassNull
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\ForbiddenGetClassNullSniff
 *
 * @since 9.0.0
 */
final class ForbiddenGetClassNullUnitTest extends BaseSniffTestCase
{

    /**
     * testGetClassNull
     *
     * @dataProvider dataGetClassNull
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testGetClassNull($line)
    {
        $file = $this->sniffFile(__FILE__, '7.2');
        $this->assertError($file, $line, 'Passing "null" as the $object to get_class() is not allowed since PHP 7.2.');
    }

    /**
     * Data provider.
     *
     * @see testGetClassNull()
     *
     * @return array
     */
    public static function dataGetClassNull()
    {
        return [
            [11],
            [12],
            [15],
            [29],
            [30],
        ];
    }


    /**
     * Verify there are no false positives on valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.2');
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

        // No errors expected on the first 9 lines.
        for ($line = 1; $line <= 9; $line++) {
            $cases[] = [$line];
        }

        for ($line = 20; $line <= 26; $line++) {
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
        $file = $this->sniffFile(__FILE__, '7.1');
        $this->assertNoViolation($file);
    }
}
