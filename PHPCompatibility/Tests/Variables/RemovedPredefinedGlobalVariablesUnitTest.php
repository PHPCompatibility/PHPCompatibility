<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Variables;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the RemovedPredefinedGlobalVariables sniff.
 *
 * @group removedPredefinedGlobalVariables
 * @group variables
 *
 * @covers \PHPCompatibility\Sniffs\Variables\RemovedPredefinedGlobalVariablesSniff
 *
 * @since 5.5   Introduced as LongArraysSniffTest.
 * @since 7.0   RemovedVariablesSniffTest.
 * @since 7.1.3 Merged to one sniff & test.
 */
class RemovedPredefinedGlobalVariablesUnitTest extends BaseSniffTest
{

    /**
     * testRemovedGlobalVariables
     *
     * @dataProvider dataRemovedGlobalVariables
     *
     * @param string $varName      The name of the removed global variable.
     * @param string $deprecatedIn The PHP version in which the global variable was deprecated.
     * @param string $removedIn    The PHP version in which the global variable was removed.
     * @param array  $lines        The line numbers in the test file which apply to this variable.
     * @param string $alternative  What to use as an alternative.
     * @param string $okVersion    A PHP version in which the global variable was ok to be used.
     *
     * @return void
     */
    public function testRemovedGlobalVariables($varName, $deprecatedIn, $removedIn, $lines, $alternative, $okVersion)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $file  = $this->sniffFile(__FILE__, $deprecatedIn);
        $error = "Global variable '$" . $varName . "' is deprecated since PHP {$deprecatedIn}; Use {$alternative} instead";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }

        $file  = $this->sniffFile(__FILE__, $removedIn);
        $error = "Global variable '$" . $varName . "' is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}; Use {$alternative} instead";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testRemovedGlobalVariables()
     *
     * @return array
     */
    public function dataRemovedGlobalVariables()
    {
        return [
            ['HTTP_POST_VARS', '5.3', '5.4', [9, 31, 71, 91], '$_POST', '5.2'],
            ['HTTP_GET_VARS', '5.3', '5.4', [10, 32, 51, 72], '$_GET', '5.2'],
            ['HTTP_ENV_VARS', '5.3', '5.4', [11, 33, 52, 73], '$_ENV', '5.2'],
            ['HTTP_SERVER_VARS', '5.3', '5.4', [12, 34, 74, 92], '$_SERVER', '5.2'],
            ['HTTP_COOKIE_VARS', '5.3', '5.4', [13, 35, 75], '$_COOKIE', '5.2'],
            ['HTTP_SESSION_VARS', '5.3', '5.4', [14, 36, 76, 93], '$_SESSION', '5.2'],
            ['HTTP_POST_FILES', '5.3', '5.4', [15, 37, 77], '$_FILES', '5.2'],

            ['HTTP_RAW_POST_DATA', '5.6', '7.0', [3, 38, 53, 78], 'php://input', '5.5'],
        ];
    }


    /**
     * testDeprecatedRemovedPHPErrorMsg
     *
     * @dataProvider dataDeprecatedRemovedPHPErrorMsg
     *
     * @param array $line The line number in the test file where a warning is expected.
     *
     * @return void
     */
    public function testDeprecatedRemovedPHPErrorMsg($line)
    {
        $file = $this->sniffFile(__FILE__, '7.1');
        $this->assertNoViolation($file, $line);

        $file  = $this->sniffFile(__FILE__, '7.2');
        $error = 'The variable \'$php_errormsg\' is deprecated since PHP 7.2; Use error_get_last() instead';
        $this->assertWarning($file, $line, $error);

        $file  = $this->sniffFile(__FILE__, '8.0');
        $error = 'The variable \'$php_errormsg\' is deprecated since PHP 7.2 and removed since PHP 8.0; Use error_get_last() instead';
        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedPHPErrorMsg()
     *
     * @return array
     */
    public function dataDeprecatedRemovedPHPErrorMsg()
    {
        return [
            [101],
            [110],
            [111],
            [126],
            [140],
            [141],
            [156],
            [179],
        ];
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
        $file = $this->sniffFile(__FILE__, '99.0'); // High version beyond latest deprecation.
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
            // Variable names are case-sensitive.
            [5],
            [6],

            // Issue #268 - class properties named after long array variables.
            [20],
            [21],
            [22],
            [23],
            [24],
            [25],
            [26],
            [27],

            [41],
            [42],
            [43],
            [44],
            [45],
            [46],
            [47],
            [48],

            // Issue #333 - class properties named after long array variables in anonymous classes.
            [60],
            [61],
            [62],
            [63],
            [64],
            [65],
            [66],
            [67],

            [81],
            [82],
            [83],
            [84],
            [85],
            [86],
            [87],
            [88],

            // PHP 7.2 deprecated $php_errormsg.
            [106],
            [114],
            [116],
            [118],
            [121],
            [123],
            [127],
            [130],
            [132],
            [133],
            [134],
            [143],
            [145],
            [146],
            [147],
            [150],
            [151],
            [165],
            [169],

            // Static property use outside class context.
            [172],
            [173],

            [176],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.2'); // Low version below the first deprecation.
        $this->assertNoViolation($file);
    }
}
