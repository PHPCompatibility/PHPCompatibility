<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Util\Tests\Helpers\TokenGroup;

use PHPCompatibility\Helpers\TokenGroup;
use PHPCompatibility\Util\Tests\CoreMethodTestFrame;

/**
 * Tests for the `isNumber()`, `isPositiveNumber()` and `isNegativeNumber()` utility functions.
 *
 * @group utilityIsNumber
 * @group utilityFunctions
 *
 * @since 8.2.0
 */
final class IsNumberUnitTest extends CoreMethodTestFrame
{

    /**
     * Verify that the `isNumber()` function returns false when an invalid start pointer is passed.
     *
     * @covers \PHPCompatibility\Helpers\TokenGroup::isNumber
     *
     * @return void
     */
    public function testIsNumberInvalidTokenStart()
    {
        $result = TokenGroup::isNumber(self::$phpcsFile, -1, 10, true);
        $this->assertFalse($result);
    }

    /**
     * Verify that the `isNumber()` function returns false when an invalid end pointer is passed.
     *
     * @covers \PHPCompatibility\Helpers\TokenGroup::isNumber
     *
     * @return void
     */
    public function testIsNumberInvalidTokenEnd()
    {
        $result = TokenGroup::isNumber(self::$phpcsFile, 3, 100000, true);
        $this->assertFalse($result);
    }

    /**
     * Verify the functionality of the `isNumber()` function.
     *
     * @dataProvider dataIsNumber
     *
     * @covers \PHPCompatibility\Helpers\TokenGroup::isNumber
     *
     * @param string     $commentString The comment which prefaces the target snippet in the test file.
     * @param bool       $allowFloats   Testing the snippets for integers only or floats as well ?
     * @param float|bool $isNumber      The expected return value for isNumber().
     *
     * @return void
     */
    public function testIsNumber($commentString, $allowFloats, $isNumber)
    {
        $start = ($this->getTargetToken($commentString, \T_EQUAL) + 1);
        $end   = ($this->getTargetToken($commentString, \T_SEMICOLON) - 1);

        $result = TokenGroup::isNumber(self::$phpcsFile, $start, $end, true, $allowFloats);
        $this->assertSame($isNumber, $result);
    }

    /**
     * Verify the functionality of the `isPositiveNumber()` function.
     *
     * @dataProvider dataIsNumber
     *
     * @covers \PHPCompatibility\Helpers\TokenGroup::isPositiveNumber
     *
     * @param string     $commentString    The comment which prefaces the target snippet in the test file.
     * @param bool       $allowFloats      Testing the snippets for integers only or floats as well ?
     * @param float|bool $isNumber         Not used by this test.
     * @param bool       $isPositiveNumber The expected return value for isPositiveNumber().
     *
     * @return void
     */
    public function testIsPositiveNumber($commentString, $allowFloats, $isNumber, $isPositiveNumber)
    {
        $start = ($this->getTargetToken($commentString, \T_EQUAL) + 1);
        $end   = ($this->getTargetToken($commentString, \T_SEMICOLON) - 1);

        $result = TokenGroup::isPositiveNumber(self::$phpcsFile, $start, $end, true, $allowFloats);
        $this->assertSame($isPositiveNumber, $result);
    }

    /**
     * Verify the functionality of the `isNegativeNumber()` function.
     *
     * @dataProvider dataIsNumber
     *
     * @covers \PHPCompatibility\Helpers\TokenGroup::isNegativeNumber
     *
     * @param string     $commentString    The comment which prefaces the target snippet in the test file.
     * @param bool       $allowFloats      Testing the snippets for integers only or floats as well ?
     * @param float|bool $isNumber         Not used by this test.
     * @param bool       $isPositiveNumber Not used by this test.
     * @param bool       $isNegativeNumber The expected return value for isNegativeNumber().
     *
     * @return void
     */
    public function testIsNegativeNumber($commentString, $allowFloats, $isNumber, $isPositiveNumber, $isNegativeNumber)
    {
        $start = ($this->getTargetToken($commentString, \T_EQUAL) + 1);
        $end   = ($this->getTargetToken($commentString, \T_SEMICOLON) - 1);

        $result = TokenGroup::isNegativeNumber(self::$phpcsFile, $start, $end, true, $allowFloats);
        $this->assertSame($isNegativeNumber, $result);
    }

    /**
     * Data provider.
     *
     * @see testIsNumber()
     *
     * {@internal Case I13 is not tested here on purpose as the result depends on the
     * `testVersion` which we don't use in the utility tests.
     * For a `testVersion` with a minimum of PHP 7.0, the result will be false.
     * For a `testVersion` which includes any PHP 5 version, the result will be true.}
     *
     * @return array
     */
    public function dataIsNumber()
    {
        return [
            'Not a number - array'                                             => ['/* test 1 */', true, false, false, false],
            'Not a number - variable'                                          => ['/* test 2 */', true, false, false, false],
            'Not a number - operator (parse error)'                            => ['/* test 4 */', true, false, false, false],
            'Not a number - object instantiation'                              => ['/* test 5 */', true, false, false, false],
            'Not a number - integer with operator after (parse error)'         => ['/* test 6 */', true, false, false, false],
            'Not a number - float with operator after(parse error)'            => ['/* test 7 */', true, false, false, false],
            'Not a number - calculation 1'                                     => ['/* test 8 */', true, false, false, false],
            'Not a number - calculation 2'                                     => ['/* test 9 */', true, false, false, false],
            'Not a number - string concat'                                     => ['/* test 10 */', true, false, false, false],

            'Evals to 0, no floats: int 0'                                     => ['/* test ZI1 */', false, 0, false, false],
            'Evals to 0, no floats: + sign with int 0'                         => ['/* test ZI2 */', false, 0, false, false],
            'Evals to 0, no floats: - sign with bool false'                    => ['/* test ZI3 */', false, -0, false, false],
            'Evals to 0, no floats: string 0'                                  => ['/* test ZI4 */', false, 0, false, false],
            'Evals to 0, no floats: - sign with string starting with 0'        => ['/* test ZI5 */', false, -0, false, false],
            'Evals to 0, no floats: null'                                      => ['/* test ZI6 */', false, 0, false, false],
            'Evals to 0, no floats: - sign with text string'                   => ['/* test ZI7 */', false, 0, false, false],

            'Evals to 0, incl floats: int 0'                                   => ['/* test ZI1 */', true, 0.0, false, false],
            'Evals to 0, incl floats: + sign with int 0'                       => ['/* test ZI2 */', true, 0.0, false, false],
            'Evals to 0, incl floats: - sign with bool false'                  => ['/* test ZI3 */', true, -0.0, false, false],
            'Evals to 0, incl floats: string 0'                                => ['/* test ZI4 */', true, 0.0, false, false],
            'Evals to 0, incl floats: - sign with string starting with 0'      => ['/* test ZI5 */', true, -0.0, false, false],
            'Evals to 0, incl floats: null'                                    => ['/* test ZI6 */', true, 0.0, false, false],
            'Evals to 0, incl floats: - sign with text string'                 => ['/* test ZI7 */', true, 0.0, false, false],

            'Evals to 0, no floats: float 0.0'                                 => ['/* test ZF1 */', false, false, false, false],
            'Evals to 0, no floats: - sign with float 0.0'                     => ['/* test ZF2 */', false, false, false, false],

            'Evals to 0, incl floats: float 0.0'                               => ['/* test ZF1 */', true, 0.0, false, false],
            'Evals to 0, incl floats: - sign with float 0.0'                   => ['/* test ZF2 */', true, -0.0, false, false],

            'Evals to int, no floats: int 1'                                   => ['/* test I1 */', false, 1, true, false],
            'Evals to int, no floats: - sign with int 10'                      => ['/* test I2 */', false, -10, false, true],
            'Evals to int, no floats: + sign with int 10 and whitespace'       => ['/* test I3 */', false, 10, true, false],
            'Evals to int, no floats: - sign with int 10 and comment'          => ['/* test I4 */', false, -10, false, true],
            'Evals to int, no floats: + sign with int 10 and comment, multi-line'
                                                                               => ['/* test I5 */', false, 10, true, false],
            'Evals to int, no floats: text string 10'                          => ['/* test I6 */', false, 10, true, false],
            'Evals to int, no floats: + sign with text string and comment'     => ['/* test I7 */', false, 10, true, false],
            'Evals to int, no floats: - sign with text starting with number'   => ['/* test I8 */', false, -10, false, true],
            'Evals to int, no floats: heredoc containing int'                  => ['/* test I9 */', false, 10, true, false],
            'Evals to int, no floats: multiline heredoc containing ints'       => ['/* test I10 */', false, -1, false, true],
            'Evals to int, no floats: text starting with number with leading whitespace'
                                                                               => ['/* test I11 */', false, 10, true, false],
            'Evals to int, no floats: + sign with text starting with number with leading whitespace'
                                                                               => ['/* test I12 */', false, 10, true, false],
            'Evals to int, no floats: - sign with bool true'                   => ['/* test I14 */', false, -1, false, true],
            'Evals to int, no floats: + sign with text starting with octal number with leading whitespace'
                                                                               => ['/* test I15 */', false, 123, true, false],
            'Evals to int, no floats: multiple signs with int'                 => ['/* test I16 */', false, 10, true, false],

            'Evals to int, incl floats: int 1'                                 => ['/* test I1 */', true, 1.0, true, false],
            'Evals to int, incl floats: - sign with int 10'                    => ['/* test I2 */', true, -10.0, false, true],
            'Evals to int, incl floats: + sign with int 10 and whitespace'     => ['/* test I3 */', true, 10.0, true, false],
            'Evals to int, incl floats: - sign with int 10 and comment'        => ['/* test I4 */', true, -10.0, false, true],
            'Evals to int, incl floats: + sign with int 10 and comment, multi-line'
                                                                               => ['/* test I5 */', true, 10.0, true, false],
            'Evals to int, incl floats: text string 10'                        => ['/* test I6 */', true, 10.0, true, false],
            'Evals to int, incl floats: + sign with text string and comment'   => ['/* test I7 */', true, 10.0, true, false],
            'Evals to int, incl floats: - sign with text starting with number' => ['/* test I8 */', true, -10.0, false, true],
            'Evals to int, incl floats: heredoc containing int'                => ['/* test I9 */', true, 10.0, true, false],
            'Evals to int, incl floats: multiline heredoc containing ints'     => ['/* test I10 */', true, -1.0, false, true],
            'Evals to int, incl floats: text starting with number with leading whitespace'
                                                                               => ['/* test I11 */', true, 10.0, true, false],
            'Evals to int, incl floats: + sign with text starting with number with leading whitespace'
                                                                               => ['/* test I12 */', true, 10.0, true, false],
            'Evals to int, incl floats: - sign with bool true'                 => ['/* test I14 */', true, -1.0, false, true],
            'Evals to int, incl floats: + sign with text starting with octal number with leading whitespace'
                                                                               => ['/* test I15 */', true, 123.0, true, false],
            'Evals to int, incl floats: multiple signs with int'               => ['/* test I16 */', true, 10.0, true, false],

            'Evals to float, no floats: float 1.23'                            => ['/* test F1 */', false, false, false, false],
            'Evals to float, no floats: float -10.123'                         => ['/* test F2 */', false, false, false, false],
            'Evals to float, no floats: float +10.123 with whitespace'         => ['/* test F3 */', false, false, false, false],
            'Evals to float, no floats: float -10.123 with comment'            => ['/* test F4 */', false, false, false, false],
            'Evals to float, no floats: float +10.123 with phpcs comment'      => ['/* test F5 */', false, false, false, false],
            'Evals to float, no floats: string 10.123'                         => ['/* test F6 */', false, false, false, false],
            'Evals to float, no floats: + sign with text string and comment'   => ['/* test F7 */', false, false, false, false],
            'Evals to float, no floats: - sign with text starting with number in scientific notation (uppercase)'
                                                                               => ['/* test F8 */', false, false, false, false],
            'Evals to float, no floats: - sign with text starting with number in scientific notation (lowercase)'
                                                                               => ['/* test F9 */', false, false, false, false],
            'Evals to float, no floats: heredoc containing float'              => ['/* test F10 */', false, false, false, false],
            'Evals to float, no floats: + sign with text string'               => ['/* test F11 */', false, false, false, false],

            'Evals to float, incl floats: float 1.23'                          => ['/* test F1 */', true, 1.23, true, false],
            'Evals to float, incl floats: float -10.123'                       => ['/* test F2 */', true, -10.123, false, true],
            'Evals to float, incl floats: float +10.123 with whitespace'       => ['/* test F3 */', true, 10.123, true, false],
            'Evals to float, incl floats: float -10.123 with comment'          => ['/* test F4 */', true, -10.123, false, true],
            'Evals to float, incl floats: float +10.123 with phpcs comment'    => ['/* test F5 */', true, 10.123, true, false],
            'Evals to float, incl floats: string 10.123'                       => ['/* test F6 */', true, 10.123, true, false],
            'Evals to float, incl floats: + sign with text string and comment' => ['/* test F7 */', true, 10.123, true, false],
            'Evals to float, incl floats: - sign with text starting with number in scientific notation (uppercase)'
                                                                               => ['/* test F8 */', true, -10E3, false, true],
            'Evals to float, incl floats: - sign with text starting with number in scientific notation (lowercase)'
                                                                               => ['/* test F9 */', true, -10e8, false, true],
            'Evals to float, incl floats: heredoc containing float'            => ['/* test F10 */', true, 10.123, true, false],
            'Evals to float, incl floats: + sign with text string'             => ['/* test F11 */', true, 0.123, true, false],
        ];
    }

    /**
     * Edge case: test that the `isNumber()` method bows out when the "end" token is a heredoc/nowdoc opener.
     *
     * @covers \PHPCompatibility\Helpers\TokenGroup::isNumber
     *
     * @return void
     */
    public function testIsNumberWithIncorrectEndStartHeredoc()
    {
        $start = ($this->getTargetToken('/* testHeredocNoEnd */', \T_EQUAL) + 1);
        $end   = $this->getTargetToken('/* testHeredocNoEnd */', \T_START_HEREDOC);

        $result = TokenGroup::isNumber(self::$phpcsFile, $start, $end, true);
        $this->assertFalse($result);
    }

    /**
     * Edge case: test that the `isNumber()` method bows out when the "end" token is before the heredoc/nowdoc closer.
     *
     * @covers \PHPCompatibility\Helpers\TokenGroup::isNumber
     *
     * @return void
     */
    public function testIsNumberWithIncorrectEndWithinHeredoc()
    {
        $start = ($this->getTargetToken('/* testHeredocNoEnd */', \T_EQUAL) + 1);
        $end   = $this->getTargetToken('/* testHeredocNoEnd */', \T_HEREDOC);

        $result = TokenGroup::isNumber(self::$phpcsFile, $start, $end, true);
        $this->assertFalse($result);
    }
}
