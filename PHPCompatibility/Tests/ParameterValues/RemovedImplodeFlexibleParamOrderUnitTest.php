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
 * Test the RemovedImplodeFlexibleParamOrder sniff.
 *
 * @group removedImplodeFlexibleParamOrder
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedImplodeFlexibleParamOrderSniff
 *
 * @since 9.3.0
 */
class RemovedImplodeFlexibleParamOrderUnitTest extends BaseSniffTest
{

    /**
     * testRemovedImplodeFlexibleParamOrder
     *
     * @dataProvider dataRemovedImplodeFlexibleParamOrder
     *
     * @param int    $line         Line number where the error should occur.
     * @param string $functionName The function name.
     *
     * @return void
     */
    public function testRemovedImplodeFlexibleParamOrder($line, $functionName)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertWarning($file, $line, 'Passing the $glue and $pieces parameters in reverse order to ' . $functionName . ' has been deprecated since PHP 7.4; $glue should be the first parameter and $pieces the second');

        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, $line, 'Passing the $glue and $pieces parameters in reverse order to ' . $functionName . ' has been deprecated since PHP 7.4 and is removed since PHP 8.0; $glue should be the first parameter and $pieces the second');
    }

    /**
     * dataRemovedImplodeFlexibleParamOrder
     *
     * @see testRemovedImplodeFlexibleParamOrder()
     *
     * @return array
     */
    public function dataRemovedImplodeFlexibleParamOrder()
    {
        return [
            [29, 'implode'],
            [30, 'join'],
            [32, 'implode'],
            [33, 'join'],
            [35, 'implode'],
            [36, 'join'],
            [37, 'implode'],
            [38, 'implode'],
            [40, 'join'],
            [46, 'implode'],
            [47, 'implode'],
            [48, 'implode'],
            [49, 'implode'],
            [52, 'implode'],
            [53, 'implode'],
            [68, 'implode'],
        ];
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
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

        // No errors expected on the first 27 lines.
        for ($line = 1; $line <= 27; $line++) {
            $data[] = [$line];
        }

        $data[] = [57];
        $data[] = [64];
        $data[] = [67];
        $data[] = [71];
        $data[] = [72];

        return $data;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.3');
        $this->assertNoViolation($file);
    }
}
