<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionUse;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewNamedParameters sniff.
 *
 * @group newNamedParameters
 * @group functionuse
 *
 * @covers \PHPCompatibility\Sniffs\FunctionUse\NewNamedParametersSniff
 *
 * @since 10.0.0
 */
class NewNamedParametersUnitTest extends BaseSniffTest
{

    /**
     * Verify that function calls using named parameters are detected correctly.
     *
     * @dataProvider dataNewNamedParameters
     *
     * @param int    $line The line number.
     * @param string $name The parameter name detected.
     *
     * @return void
     */
    public function testNewNamedParameters($line, $name)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertError($file, $line, "Using named arguments in function calls is not supported in PHP 7.4 or earlier. Found: \"$name");
    }

    /**
     * Data provider.
     *
     * @see testNewNamedParameters()
     *
     * @return array
     */
    public function dataNewNamedParameters()
    {
        return [
            [17, 'start_index'],
            [17, 'count'],
            [17, 'value'],
            [20, 'start_index'],
            [21, 'count'],
            [22, 'value'],
            [26, 'start_index'],
            [26, 'count'],
            [26, 'value'],
            [28, 'double_encode'],
            [31, 'start_index'],
            [31, 'skip'],
            [32, 'count'],
            [32, 'array_or_countable'],
            [33, 'value'],

            [36, 'label'],
            [36, 'more'],
            [37, 'label'],
            [37, 'more'],
            [38, 'label'],
            [38, 'more'],
            [39, 'label'],
            [39, 'more'],
            [40, 'label'],
            [40, 'more'],
            [41, 'label'],
            [41, 'more'],
            [42, 'label'],
            [42, 'more'],
            [43, 'label'],
            [43, 'more'],
            [45, 'label'],
            [45, 'more'],

            // Temporarily disabled. These are currently false negatives.
            // Not (yet) supported by PHPCSUtils. Uncomment once support has been added.
            // [50, 'label'],
            // [50, 'more'],
            // [51, 'label'],
            // [51, 'more'],

            [54, '💩💩💩'],
            [54, 'Пасха'],
            [54, '_valid'],

            [56, 'label'],
            [56, 'more'],

            [58, 'label'],
            [58, 'more'],

            [62, 'abstract'],
            [63, 'function'],
            [64, 'protected'],
            [65, 'object'],
            [66, 'parent'],

            [70, 'param'],
            [73, 'param'], // Same error for duplicate label.
            [76, 'start_index'],
            [79, 'param'],
        ];
    }


    /**
     * Verify no false positives are thrown for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
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
        $data = [];

        // No errors expected on the first 13 lines.
        for ($line = 1; $line <= 13; $line++) {
            $data[] = [$line];
        }

        $data[] = [85];
        $data[] = [88];
        $data[] = [91];
        $data[] = [94];
        $data[] = [97];

        return $data;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertNoViolation($file);
    }
}
