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
final class ArgumentFunctionsUsageUnitTest extends BaseSniffTestCase
{

    /**
     * Test that use of the functions nested within a function call is correctly detected.
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
     * Data provider.
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
     * Test that there are no false positives for the "nested in function call" check.
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
        $data = [];

        for ($line = 24; $line <= 38; $line++) {
            $data[] = [$line];
        }

        for ($line = 55; $line <= 58; $line++) {
            $data[] = [$line];
        }

        for ($line = 64; $line <= 67; $line++) {
            $data[] = [$line];
        }

        $data[] = [70];

        for ($line = 73; $line <= 79; $line++) {
            $data[] = [$line];
        }

        return $data;
    }


    /**
     * Test that use of the functions in the global scope is correctly detected.
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
     * Data provider.
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
     * Test that there are no false positives for the "usage in global scope" check.
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
        $data = self::dataNoFalsePositivesUseAsParameter();

        // Tests specific for this error.
        for ($line = 47; $line <= 53; $line++) {
            $data[] = [$line];
        }

        for ($line = 60; $line <= 62; $line++) {
            $data[] = [$line];
        }

        return $data;
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will throw warnings/errors
     * about the use of these functions in the global scope independently of the PHP version.
     */
}
