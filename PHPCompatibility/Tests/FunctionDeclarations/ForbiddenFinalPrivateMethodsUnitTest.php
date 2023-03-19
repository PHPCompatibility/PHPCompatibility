<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionDeclarations;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the ForbiddenFinalPrivateMethods sniff.
 *
 * @group forbiddenFinalPrivateMethods
 * @group functiondeclarations
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\ForbiddenFinalPrivateMethodsSniff
 *
 * @since 10.0.0
 */
class ForbiddenFinalPrivateMethodsUnitTest extends BaseSniffTest
{

    /**
     * Verify that the sniff throws a warning for non-construct final private methods for PHP 8.0+.
     *
     * @dataProvider dataForbiddenFinalPrivateMethods
     *
     * @param int $line The line number where a warning is expected.
     *
     * @return void
     */
    public function testForbiddenFinalPrivateMethods($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertWarning($file, $line, 'Private methods should not be declared as final since PHP 8.0');
    }

    /**
     * Data provider.
     *
     * @see testForbiddenFinalPrivateMethods()
     *
     * @return array
     */
    public function dataForbiddenFinalPrivateMethods()
    {
        return [
            [34],
            [35],
            [39],
            [40],
            [45],
            [46],
            [54],
            [60],
        ];
    }


    /**
     * Verify the sniff does not throw false positives for valid code.
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
    public function dataNoFalsePositives()
    {
        $cases = [];
        // No errors expected on the first 28 lines.
        for ($line = 1; $line <= 28; $line++) {
            $cases[] = [$line];
        }

        $cases[] = [50];
        $cases[] = [59];

        return $cases;
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
