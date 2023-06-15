<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the ChangedIntToBoolParamType sniff.
 *
 * @group changedIntToBoolParamType
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\ChangedIntToBoolParamTypeSniff
 *
 * @since 10.0.0
 */
class ChangedIntToBoolParamTypeUnitTest extends BaseSniffTest
{

    /**
     * Test that numeric values being passed as $auto_release to sem_get() throw an error.
     *
     * @dataProvider dataChangedIntToBoolParamType
     *
     * @param int    $line         Line number where the error should occur.
     * @param string $since        The PHP version in which the change was made.
     * @param string $paramName    The name of the parameter the error relates to.
     * @param string $functionName The name of the function the error relates to.
     *
     * @return void
     */
    public function testChangedIntToBoolParamType($line, $since, $paramName, $functionName)
    {
        $file  = $this->sniffFile(__FILE__, $since);
        $error = "The {$paramName} parameter of {$functionName}() expects a boolean value instead of an integer since PHP {$since}";

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testChangedIntToBoolParamType)
     *
     * @return array
     */
    public static function dataChangedIntToBoolParamType()
    {
        return [
            [21, '8.0', '$auto_release', 'sem_get'],
            [22, '8.0', '$auto_release', 'sem_get'],
            [23, '8.0', '$auto_release', 'sem_get'],
            [24, '8.0', '$auto_release', 'sem_get'],
            [25, '8.0', '$auto_release', 'sem_get'],
            [26, '8.0', '$auto_release', 'sem_get'],
            [27, '8.0', '$enable', 'ob_implicit_flush'],
            [28, '8.0', '$enable', 'ob_implicit_flush'],
        ];
    }


    /**
     * Verify no false positives are thrown for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '99.0');
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

        // No errors expected on the first 19 lines.
        for ($line = 1; $line <= 19; $line++) {
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
        $file = $this->sniffFile(__FILE__, '7.4'); // Version before first change.
        $this->assertNoViolation($file);
    }
}
