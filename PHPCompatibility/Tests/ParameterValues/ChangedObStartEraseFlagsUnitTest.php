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
 * Test the ChangedObStartEraseFlags sniff.
 *
 * @group changedObStartEraseFlags
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\ChangedObStartEraseFlagsSniff
 *
 * @since 10.0.0
 */
class ChangedObStartEraseFlagsUnitTest extends BaseSniffTest
{

    /**
     * Test the sniff correctly detecting boolean parameter values being passed.
     *
     * @dataProvider dataChangedObStartEraseFlagsBoolean
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testChangedObStartEraseFlagsBoolean($line)
    {
        $file  = $this->sniffFile(__FILE__, '5.4');
        $error = 'The third parameter of ob_start() changed from the boolean $erase to the integer $flags in PHP 5.4.';

        $this->assertError($file, $line, $error);

        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testChangedObStartEraseFlagsBoolean()
     *
     * @return array
     */
    public function dataChangedObStartEraseFlagsBoolean()
    {
        return [
            [13],
            [14],
            [26],
        ];
    }


    /**
     * Test the sniff correctly detecting integer parameter values being passed.
     *
     * @dataProvider dataChangedObStartEraseFlagsInt
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testChangedObStartEraseFlagsInt($line)
    {
        $file  = $this->sniffFile(__FILE__, '5.3');
        $error = 'The third parameter of ob_start() changed from the boolean $erase to the integer $flags in PHP 5.4.';

        $this->assertError($file, $line, $error);

        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testChangedObStartEraseFlagsInt()
     *
     * @return array
     */
    public function dataChangedObStartEraseFlagsInt()
    {
        return [
            [17],
            [18],
            [19],
            [20],
            [21],
            [22],
            [23],
            [27],
        ];
    }


    /**
     * Verify there are no false positives on code this sniff should ignore.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '5.3-5.4');
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
        $data = [];

        // No errors expected on the first 11 lines.
        for ($line = 1; $line <= 11; $line++) {
            $data[] = [$line];
        }

        $data[] = [28];

        return $data;
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will throw warnings/errors
     * about both for above as well as below a certain version.
     */
}
