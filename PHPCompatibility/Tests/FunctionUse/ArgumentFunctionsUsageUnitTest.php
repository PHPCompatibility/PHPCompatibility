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
 * Test the ArgumentFunctionsUsage sniff.
 *
 * @group argumentFunctions
 * @group functionUse
 *
 * @covers \PHPCompatibility\Sniffs\FunctionUse\ArgumentFunctionsUsageSniff
 *
 * @since 8.2.0
 */
class ArgumentFunctionsUsageUnitTest extends BaseSniffTestCase
{

    /**
     * testArgumentFunctionsUseAsParameter
     *
     * @dataProvider dataArgumentFunctionsUseAsParameter
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testArgumentFunctionsUseAsParameter($line)
    {
        $file = $this->sniffFile(__FILE__, '5.2');
        $this->assertError($file, $line, '() could not be used in parameter lists prior to PHP 5.3.');

        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider dataArgumentFunctionsUseAsParameter.
     *
     * @see testArgumentFunctionsUseAsParameter()
     *
     * @return array
     */
    public static function dataArgumentFunctionsUseAsParameter()
    {
        return [
            [7],
            [8],
            [12],
            [17],
            [18],
            [19],
        ];
    }


    /**
     * testNoFalsePositivesUseAsParameter
     *
     * @dataProvider dataNoFalsePositivesUseAsParameter
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositivesUseAsParameter($line)
    {
        $file = $this->sniffFile(__FILE__, '5.2');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesUseAsParameter()
     *
     * @return array
     */
    public static function dataNoFalsePositivesUseAsParameter()
    {
        return [
            [25],
            [26],
            [27],
            [29],
            [30],
            [31],
            [32],
            [33],
            [36],
        ];
    }


    /**
     * testArgumentFunctionsUseOutsideFunctionScope
     *
     * @dataProvider dataArgumentFunctionsUseOutsideFunctionScope
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testArgumentFunctionsUseOutsideFunctionScope($line)
    {
        $file = $this->sniffFile(__FILE__, '5.0');
        $this->assertWarning($file, $line, '() outside of a user-defined function is only supported if the file is included from within a user-defined function in another file prior to PHP 5.3.');

        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertError($file, $line, '() outside of a user-defined function is only supported if the file is included from within a user-defined function in another file prior to PHP 5.3. As of PHP 5.3, it is no longer supported at all.');
    }

    /**
     * Data provider dataArgumentFunctionsUseOutsideFunctionScope.
     *
     * @see testArgumentFunctionsUseOutsideFunctionScope()
     *
     * @return array
     */
    public static function dataArgumentFunctionsUseOutsideFunctionScope()
    {
        return [
            [43],
            [44],
            [45],
        ];
    }


    /**
     * testNoFalsePositivesUseOutsideFunctionScope
     *
     * @dataProvider dataNoFalsePositivesUseOutsideFunctionScope
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositivesUseOutsideFunctionScope($line)
    {
        $file = $this->sniffFile(__FILE__);
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesUseOutsideFunctionScope()
     *
     * @return array
     */
    public static function dataNoFalsePositivesUseOutsideFunctionScope()
    {
        return [
            [48],
            [49],
            [50],
            [51],
        ];
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will throw warnings/errors
     * about the use of these functions in the global scope independently of the PHP version.
     */
}
