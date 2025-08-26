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

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the ArgumentFunctionsReportCurrentValue sniff.
 *
 * @group argumentFunctionsReportCurrentValue
 * @group functionUse
 *
 * @covers \PHPCompatibility\Sniffs\FunctionUse\ArgumentFunctionsReportCurrentValueSniff
 *
 * @since 9.1.0
 */
class ArgumentFunctionsReportCurrentValueUnitTest extends BaseSniffTestCase
{

    /**
     * testValueChanged.
     *
     * @dataProvider dataValueChanged
     *
     * @param int    $line         The line number where an error is expected.
     * @param string $functionName The name of the function to which the error applies.
     * @param string $variableName The variable which was detected as having been changed.
     *
     * @return void
     */
    public function testValueChanged($line, $functionName, $variableName)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertError($file, $line, "Since PHP 7.0, functions inspecting arguments, like {$functionName}(), no longer report the original value as passed to a parameter, but will instead provide the current value. The parameter \"{$variableName}\" was changed on line");
    }

    /**
     * Data provider.
     *
     * @see testValueChanged()
     *
     * @return array
     */
    public static function dataValueChanged()
    {
        return [
            [84, 'func_get_arg', '$x'],
            [85, 'func_get_args', '$x'],
            [86, 'debug_backtrace', '$x'],
            [95, 'func_get_arg', '$b'],
            [97, 'func_get_args', '$b'],
            [100, 'func_get_arg', '$b'],
            [102, 'func_get_args', '$b'],
            [105, 'func_get_arg', '$b'],
            [106, 'func_get_arg', '$c'],
            [107, 'debug_backtrace', '$b'],
            [108, 'debug_print_backtrace', '$b'],
            [109, 'debug_backtrace', '$b'],
            [110, 'debug_backtrace', '$b'],
            [120, 'func_get_arg', '$a'],
            [122, 'func_get_arg', '$a'],
            [161, 'func_get_args', '$string'],
            [230, 'func_get_args', '$x'],
            [243, 'func_get_args', '$stuff'],
            [261, 'func_get_arg', '$x'],
            [262, 'func_get_arg', '$y'],
            [263, 'func_get_args', '$x'],
            [264, 'debug_backtrace', '$x'],
            [284, 'debug_backtrace', '$a'],
            [285, 'debug_print_backtrace', '$a'],
            [286, 'debug_backtrace', '$a'],
            [292, 'debug_backtrace', '$a'],
            [293, 'debug_backtrace', '$a'],
            [294, 'debug_backtrace', '$a'],
            [300, 'debug_print_backtrace', '$a'],
            [307, 'func_get_args', '$string'],
            [316, 'func_get_args', '$string'],
            [317, 'func_get_args', '$string'],
            [318, 'func_get_args', '$string'],
            [319, 'func_get_args', '$string'],
            [320, 'func_get_args', '$string'],
            [321, 'func_get_args', '$string'],
            [322, 'func_get_args', '$string'],
            [324, 'func_get_args', '$string'],
            [325, 'func_get_args', '$string'],
            [326, 'func_get_args', '$string'],
            [327, 'func_get_args', '$string'],
            [328, 'func_get_args', '$string'],
            [329, 'func_get_args', '$string'],
            [330, 'func_get_args', '$string'],
            [348, 'debug_backtrace', '$x'],
            [353, 'func_get_args', '$x'],
            [358, 'func_get_args', '$y'],
            [363, 'debug_print_backtrace', '$y'],
            [368, 'func_get_args', '$y'],
            [373, 'debug_print_backtrace', '$y'],
            [378, 'func_get_args', '$z'],
            [383, 'debug_print_backtrace', '$z'],
            [449, 'func_get_args', '$n2'],
            [461, 'func_get_args', '$n3'],
            [462, 'func_get_args', '$n3'],
            [463, 'func_get_args', '$n3'],
            [476, 'func_get_arg', '$named'],
            [477, 'func_get_arg', '$named'],
            [478, 'func_get_arg', '$named'],
            [480, 'func_get_args', '$named'],
            [481, 'func_get_args', '$named'],
            [482, 'func_get_args', '$named'],
            [497, 'func_get_arg', '$n16'],
            [498, 'func_get_arg', '$n16'],
            [499, 'func_get_arg', '$n16'],
            [500, 'func_get_arg', '$n16'],
            [502, 'func_get_args', '$n16'],
            [503, 'func_get_args', '$n16'],
            [504, 'func_get_args', '$n16'],
            [505, 'func_get_args', '$n16'],
            [513, 'func_get_arg', '$n1'],
            [514, 'func_get_args', '$n1'],
            [525, 'func_get_arg', '$a0'],
            [526, 'func_get_arg', '$a1'],
            [527, 'func_get_arg', '$a2'],
            [528, 'func_get_arg', '$a3'],
            [529, 'func_get_arg', '$a4'],
            [564, 'func_get_arg', '$c8'],
            [565, 'func_get_arg', '$c9'],
            [566, 'func_get_arg', '$c10'],
            [569, 'func_get_arg', '$c13'],
        ];
    }


    /**
     * testNeedsInspection.
     *
     * @dataProvider dataNeedsInspection
     *
     * @param int    $line         The line number where a warning is expected.
     * @param string $functionName The name of the function to which the warning applies.
     * @param string $variableName The variable which was detected as having been used.
     *
     * @return void
     */
    public function testNeedsInspection($line, $functionName, $variableName)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertWarning($file, $line, "Since PHP 7.0, functions inspecting arguments, like {$functionName}(), no longer report the original value as passed to a parameter, but will instead provide the current value. The parameter \"{$variableName}\" was used, and possibly changed (by reference), on line");
    }

    /**
     * Data provider.
     *
     * @see testNeedsInspection()
     *
     * @return array
     */
    public static function dataNeedsInspection()
    {
        return [
            [101, 'func_get_arg', '$c'],
            [129, 'func_get_args', '$x'],
            [134, 'debug_backtrace', '$x'],
            [249, 'func_get_args', '$stuff'],
            [255, 'func_get_args', '$matches'],
            [411, 'func_get_args', '$b'],
            [417, 'debug_print_backtrace', '$b'],
            [423, 'debug_backtrace', '$c'],
            [429, 'func_get_args', '$c'],
            [435, 'debug_backtrace', '$x'],
            [440, 'func_get_args', '$x'],
            [530, 'func_get_arg', '$a5'],
            [567, 'func_get_arg', '$c11'],
            [568, 'func_get_arg', '$c12'],
        ];
    }

    /**
     * testNoFalsePositives.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
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
        // No errors expected on the first 81 lines.
        for ($line = 1; $line <= 81; $line++) {
            $cases[] = [$line];
        }

        $cases[] = [90];
        $cases[] = [94];
        $cases[] = [96];
        $cases[] = [99];
        $cases[] = [104];
        $cases[] = [121];
        $cases[] = [123];

        for ($line = 137; $line <= 160; $line++) {
            $cases[] = [$line];
        }

        for ($line = 162; $line <= 227; $line++) {
            $cases[] = [$line];
        }

        for ($line = 233; $line <= 240; $line++) {
            $cases[] = [$line];
        }

        for ($line = 266; $line <= 283; $line++) {
            $cases[] = [$line];
        }

        for ($line = 295; $line <= 299; $line++) {
            $cases[] = [$line];
        }

        $cases[] = [301];

        for ($line = 308; $line <= 314; $line++) {
            $cases[] = [$line];
        }

        for ($line = 333; $line <= 345; $line++) {
            $cases[] = [$line];
        }

        for ($line = 386; $line <= 406; $line++) {
            $cases[] = [$line];
        }

        for ($line = 442; $line <= 448; $line++) {
            $cases[] = [$line];
        }

        for ($line = 451; $line <= 460; $line++) {
            $cases[] = [$line];
        }

        for ($line = 465; $line <= 475; $line++) {
            $cases[] = [$line];
        }

        for ($line = 484; $line <= 496; $line++) {
            $cases[] = [$line];
        }

        for ($line = 507; $line <= 512; $line++) {
            $cases[] = [$line];
        }

        for ($line = 516; $line <= 524; $line++) {
            $cases[] = [$line];
        }

        for ($line = 532; $line <= 563; $line++) {
            $cases[] = [$line];
        }

        $cases[] = [575]; // Parse error.

        return $cases;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertNoViolation($file);
    }
}
