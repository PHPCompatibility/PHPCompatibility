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
 * Test the ForbiddenGetClassNoArgsOutsideOO sniff.
 *
 * @group forbiddenGetClassNoArgsOutsideOO
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\ForbiddenGetClassNoArgsOutsideOOSniff
 *
 * @since 10.0.0
 */
final class ForbiddenGetClassNoArgsOutsideOOUnitTest extends BaseSniffTestCase
{

    /**
     * Test receiving an error for calling get_class() without arguments outside class scope.
     *
     * @dataProvider dataForbiddenGetClassNoArgsOutsideOO
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testForbiddenGetClassNoArgsOutsideOO($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, $line, ' from outside of an OO scope, will throw an Error since PHP 8.0.');
    }

    /**
     * Data provider.
     *
     * @see testForbiddenGetClassNoArgsOutsideOO()
     *
     * @return array
     */
    public static function dataForbiddenGetClassNoArgsOutsideOO()
    {
        return [
            [82],
            [83],
            [86],
            [87],
            [90],
            [93],
            [97],
            [98],
            [101],
            [102],
            [107],
            [108],
            [116],
            [117],
            [125],
            [126],
            [134],
            [135],
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
        $file = $this->sniffFile(__FILE__, '8.0');
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

        // No errors expected on the first 78 lines.
        for ($line = 1; $line <= 78; $line++) {
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
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertNoViolation($file);
    }
}
