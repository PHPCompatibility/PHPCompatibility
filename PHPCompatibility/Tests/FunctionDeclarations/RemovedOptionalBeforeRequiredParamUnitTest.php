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

use PHPCompatibility\Tests\BaseSniffTestCase;

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
class RemovedOptionalBeforeRequiredParamUnitTest extends BaseSniffTestCase
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
        $this->assertWarning($file, $line, 'Declaring an optional parameter before a required parameter is deprecated since PHP 8.0');
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
            [13],
            [14],
            [16],
            [17],
            [20],
            [31],
            [38],
            [51],
            [57],
            [58],
            [59],
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
            $cases['line ' . $line] = [$line];
        }

        // Don't error on variadic parameters.
        $cases['line 23 - variadic params'] = [23];
        $cases['line 24 - variadic params'] = [24];
        $cases['line 26 - variadic params'] = [26];

        // Constructor property promotion - valid example.
        $cases['line 46 - constructor property promotion'] = [46];

        // Constant expression containing null in default value for optional param.
        $cases['line 52 - constant expression'] = [52];

        // New in initializers tests.
        $cases['line 60 - new in initializers'] = [60];
        $cases['line 61 - new in initializers'] = [61];

        // Add parse error test case.
        $cases['line 65 - parse error'] = [65];

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
