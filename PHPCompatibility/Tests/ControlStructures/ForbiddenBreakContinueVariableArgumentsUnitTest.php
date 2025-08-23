<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ControlStructures;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the ForbiddenBreakContinueVariableArguments sniff.
 *
 * @group forbiddenBreakContinueVariableArguments
 * @group controlStructures
 *
 * @covers \PHPCompatibility\Sniffs\ControlStructures\ForbiddenBreakContinueVariableArgumentsSniff
 *
 * @since 5.5
 */
final class ForbiddenBreakContinueVariableArgumentsUnitTest extends BaseSniffTestCase
{

    /**
     * Error message snippet for the variable argument error.
     *
     * @var string
     */
    const ERROR_TYPE_VARIABLE = 'a variable argument';

    /**
     * Error message snippet for the zero argument error.
     *
     * @var string
     */
    const ERROR_TYPE_ZERO = '0 as an argument';

    /**
     * testBreakAndContinueVariableArgument
     *
     * @dataProvider dataBreakAndContinueVariableArgument
     *
     * @param int    $line      The line number.
     * @param string $errorType The error type.
     *
     * @return void
     */
    public function testBreakAndContinueVariableArgument($line, $errorType)
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertError($file, $line, "Using {$errorType} on break or continue is forbidden since PHP 5.4");
    }

    /**
     * Data provider.
     *
     * @see testBreakAndContinueVariableArgument()
     *
     * @return array
     */
    public static function dataBreakAndContinueVariableArgument()
    {
        $data = [
            [53, self::ERROR_TYPE_VARIABLE],
            [57, self::ERROR_TYPE_VARIABLE],
            [62, self::ERROR_TYPE_VARIABLE],
            [66, self::ERROR_TYPE_VARIABLE],
            [71, self::ERROR_TYPE_VARIABLE],
            [75, self::ERROR_TYPE_VARIABLE],
            [80, self::ERROR_TYPE_VARIABLE],
            [84, self::ERROR_TYPE_VARIABLE],
            [89, self::ERROR_TYPE_VARIABLE],
            [93, self::ERROR_TYPE_VARIABLE],
            [98, self::ERROR_TYPE_VARIABLE],
            [102, self::ERROR_TYPE_VARIABLE],
            [107, self::ERROR_TYPE_ZERO],
            [111, self::ERROR_TYPE_ZERO],
            [118, self::ERROR_TYPE_ZERO],
            [122, self::ERROR_TYPE_VARIABLE],
            [133, self::ERROR_TYPE_ZERO],
            [141, self::ERROR_TYPE_ZERO],
            [149, self::ERROR_TYPE_ZERO],
            [160, self::ERROR_TYPE_ZERO],
            [172, self::ERROR_TYPE_VARIABLE],
            [176, self::ERROR_TYPE_VARIABLE],
            [203, self::ERROR_TYPE_VARIABLE],
            [206, self::ERROR_TYPE_VARIABLE],
            [211, self::ERROR_TYPE_VARIABLE],
        ];

        return $data;
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
        $file = $this->sniffFile(__FILE__, '5.4');
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

        // No errors expected on the first 50 lines.
        for ($line = 1; $line <= 50; $line++) {
            $data[] = [$line];
        }

        $data[] = [126];
        $data[] = [137];
        $data[] = [145];
        $data[] = [153];
        $data[] = [164];

        for ($line = 180; $line <= 200; $line++) {
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
        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertNoViolation($file);
    }
}
