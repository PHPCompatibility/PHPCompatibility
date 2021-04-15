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
 * Test the ArgumentFunctionsReportCurrentValue sniff.
 *
 * @group argumentFunctionsReportCurrentValue
 * @group functionUse
 *
 * @covers \PHPCompatibility\Sniffs\FunctionUse\ArgumentFunctionsReportCurrentValueSniff
 *
 * @since 9.1.0
 */
class ArgumentFunctionsReportCurrentValueUnitTest extends BaseSniffTest
{

    /**
     * testValueChanged.
     *
     * @dataProvider dataValueChanged
     *
     * @param array  $line         The line number where a warning is expected.
     * @param string $functionName The name of the function to which the warning applies.
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
    public function dataValueChanged()
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
    public function dataNeedsInspection()
    {
        return [
            [101, 'func_get_arg', '$c'],
            [129, 'func_get_args', '$x'],
            [134, 'debug_backtrace', '$x'],
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
    public function dataNoFalsePositives()
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
        $cases[] = [142];
        $cases[] = [143];
        $cases[] = [152];
        $cases[] = [162];
        $cases[] = [164];
        $cases[] = [173];
        $cases[] = [176];
        $cases[] = [184];
        $cases[] = [192];
        $cases[] = [200];
        $cases[] = [208];
        $cases[] = [215];

        $cases[] = [221]; // Parse error.

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
