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

use PHPCompatibility\Tests\BaseSniffTest;
use PHPCSUtils\BackCompat\Helper;

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
class ForbiddenBreakContinueVariableArgumentsUnitTest extends BaseSniffTest
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
    public function dataBreakAndContinueVariableArgument()
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
        ];

        if (version_compare(Helper::getVersion(), '3.5.3', '!=')) {
            $data[] = [160, self::ERROR_TYPE_ZERO];
        }

        return $data;
    }


    /**
     * testNoFalsePositives
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
    public function dataNoFalsePositives()
    {
        return [
            [8],
            [12],
            [17],
            [21],
            [26],
            [30],
            [35],
            [39],
            [44],
            [48],
            [126],
            [137],
            [145],
            [153],
            [164],
        ];
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
