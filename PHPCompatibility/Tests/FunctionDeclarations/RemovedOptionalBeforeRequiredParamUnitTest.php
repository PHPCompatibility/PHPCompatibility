<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionDeclarations;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedOptionalBeforeRequiredParam sniff.
 *
 * @group removedOptionalBeforeRequiredParam
 * @group functiondeclarations
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\RemovedOptionalBeforeRequiredParamSniff
 *
 * @since 10.0.0
 */
class RemovedOptionalBeforeRequiredParamUnitTest extends BaseSniffTestCase
{

    /**
     * Base message for the PHP 8.0 deprecation.
     *
     * @var string
     */
    const PHP80_MSG = 'Declaring an optional parameter before a required parameter is deprecated since PHP 8.0.';

    /**
     * Base message for the PHP 8.1 deprecation.
     *
     * @var string
     */
    const PHP81_MSG = 'Declaring an optional parameter with a nullable type before a required parameter is soft deprecated since PHP 8.0 and hard deprecated since PHP 8.1';

    /**
     * Verify that the sniff throws a warning for optional parameters before required.
     *
     * @dataProvider dataRemovedOptionalBeforeRequiredParam80
     *
     * @param int $line The line number where a warning is expected.
     *
     * @return void
     */
    public function testRemovedOptionalBeforeRequiredParam80($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertWarning($file, $line, self::PHP80_MSG);
    }

    /**
     * Data provider.
     *
     * @see testRemovedOptionalBeforeRequiredParam80()
     *
     * @return array
     */
    public static function dataRemovedOptionalBeforeRequiredParam80()
    {
        return [
            [13],
            [14],
            [16],
            [17],
            [20],
            [31],
            [38],
            [51],
            [57],
            [58],
            [59],
            [82],
        ];
    }


    /**
     * Verify the sniff does not throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives80
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives80($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives80()
     *
     * @return array
     */
    public static function dataNoFalsePositives80()
    {
        $cases = [];
        // No errors expected on the first 9 lines.
        for ($line = 1; $line <= 9; $line++) {
            $cases['line ' . $line] = [$line];
        }

        // Don't error on variadic parameters.
        $cases['line 23 - variadic params'] = [23];
        $cases['line 24 - variadic params'] = [24];
        $cases['line 26 - variadic params'] = [26];

        // Constructor property promotion - valid example.
        $cases['line 46 - constructor property promotion'] = [46];

        // Constant expression containing null in default value for optional param.
        $cases['line 52 - constant expression'] = [52];

        // New in initializers tests.
        $cases['line 60 - new in initializers'] = [60];
        $cases['line 61 - new in initializers'] = [61];

        // Not deprecated, false positive checks for PHP 8.1 deprecation.
        $cases['line 67 - related to PHP 8.1 deprecation'] = [67];
        $cases['line 68 - related to PHP 8.1 deprecation'] = [68];

        // Deprecated, but only flagged as of PHP 8.1.
        $cases['line 71 - deprecated in PHP 8.1'] = [71];
        $cases['line 75 - deprecated in PHP 8.1'] = [75];
        $cases['line 81 - deprecated in PHP 8.1'] = [81];

        // Add parse error test case.
        $cases['line 87 - parse error'] = [87];

        return $cases;
    }


    /**
     * Verify that the sniff throws a warning for optional parameters with a nullable type before required.
     *
     * @dataProvider dataRemovedOptionalBeforeRequiredParam81
     *
     * @param int    $line The line number where a warning is expected.
     * @param string $msg  The expected warning message.
     *
     * @return void
     */
    public function testRemovedOptionalBeforeRequiredParam81($line, $msg = self::PHP80_MSG)
    {
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertWarning($file, $line, $msg);
    }

    /**
     * Data provider.
     *
     * @see testRemovedOptionalBeforeRequiredParam81()
     *
     * @return array
     */
    public static function dataRemovedOptionalBeforeRequiredParam81()
    {
        $data   = self::dataRemovedOptionalBeforeRequiredParam80();
        $data[] = [71, self::PHP81_MSG];
        $data[] = [75, self::PHP81_MSG];
        $data[] = [81, self::PHP81_MSG];
        return $data;
    }


    /**
     * Verify the sniff does not throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives81
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives81($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives81()
     *
     * @return array
     */
    public static function dataNoFalsePositives81()
    {
        $cases = self::dataNoFalsePositives80();
        unset(
            $cases['line 71 - deprecated in PHP 8.1'],
            $cases['line 75 - deprecated in PHP 8.1'],
            $cases['line 81 - deprecated in PHP 8.1']
        );

        return $cases;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertNoViolation($file);
    }
}
