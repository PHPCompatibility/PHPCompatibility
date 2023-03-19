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
 * Test the RemovedOptionalBeforeRequiredParam sniff.
 *
 * @group removedOptionalBeforeRequiredParam
 * @group functiondeclarations
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\RemovedOptionalBeforeRequiredParamSniff
 *
 * @since 10.0.0
 */
class RemovedOptionalBeforeRequiredParamUnitTest extends BaseSniffTest
{

    /**
     * Verify that the sniff throws a warning for optional parameters before required.
     *
     * @dataProvider dataRemovedOptionalBeforeRequiredParam
     *
     * @param int $line The line number where a warning is expected.
     *
     * @return void
     */
    public function testRemovedOptionalBeforeRequiredParam($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertWarning($file, $line, 'Declaring a required parameter after an optional one is deprecated since PHP 8.0');
    }

    /**
     * Data provider.
     *
     * @see testRemovedOptionalBeforeRequiredParam()
     *
     * @return array
     */
    public static function dataRemovedOptionalBeforeRequiredParam()
    {
        return [
            [13], // Warning x 2.
            [14],
            [16],
            [17],
            [20],
            [32],
            [39],
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
    public static function dataNoFalsePositives()
    {
        $cases = [];
        // No errors expected on the first 9 lines.
        for ($line = 1; $line <= 9; $line++) {
            $cases[] = [$line];
        }

        // Don't error on variadic parameters.
        $cases[] = [23];
        $cases[] = [24];
        $cases[] = [26];

        // Constructor property promotion - valid example.
        $cases[] = [46];

        // Add parse error test case.
        $cases[] = [51];

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
