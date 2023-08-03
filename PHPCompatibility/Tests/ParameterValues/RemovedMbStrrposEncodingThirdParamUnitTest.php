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

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedMbStrrposEncodingThirdParam sniff.
 *
 * @group removedMbStrrposEncodingThirdParam
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedMbStrrposEncodingThirdParamSniff
 *
 * @since 9.3.0
 */
class RemovedMbStrrposEncodingThirdParamUnitTest extends BaseSniffTestCase
{

    /**
     * testRemovedMbStrrposEncodingThirdParam
     *
     * @dataProvider dataRemovedMbStrrposEncodingThirdParam
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testRemovedMbStrrposEncodingThirdParam($line)
    {
        $file  = $this->sniffFile(__FILE__, '5.2');
        $error = 'Passing the encoding to mb_strrpos() as third parameter is soft deprecated since PHP 5.2';
        $this->assertWarning($file, $line, $error);

        $file    = $this->sniffFile(__FILE__, '7.4');
        $error74 = $error . ' and hard deprecated since PHP 7.4.';
        $this->assertWarning($file, $line, $error74);

        $file    = $this->sniffFile(__FILE__, '8.0');
        $error80 = $error . ', hard deprecated since PHP 7.4 and removed since PHP 8.0';
        $this->assertError($file, $line, $error80);
    }

    /**
     * Data provider.
     *
     * @see testRemovedMbStrrposEncodingThirdParam()
     *
     * @return array
     */
    public static function dataRemovedMbStrrposEncodingThirdParam()
    {
        return [
            [22],
            [23],
            [24],
            [28],
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
        $file = $this->sniffFile(__FILE__, '5.2');
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

        // No errors expected on the first 20 lines.
        for ($line = 1; $line <= 20; $line++) {
            $data[] = [$line];
        }

        $data[] = [27];

        return $data;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.1');
        $this->assertNoViolation($file);
    }
}
