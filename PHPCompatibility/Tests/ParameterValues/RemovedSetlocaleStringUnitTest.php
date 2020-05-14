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
 * Test the RemovedSetlocaleString sniff.
 *
 * @group removedSetlocaleString
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedSetlocaleStringSniff
 *
 * @since 9.0.0
 */
class RemovedSetlocaleStringUnitTest extends BaseSniffTest
{

    /**
     * testSetlocaleString
     *
     * @dataProvider dataSetlocaleString
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testSetlocaleString($line)
    {
        $file = $this->sniffFile(__FILE__, '4.2');
        $this->assertWarning($file, $line, 'Passing the $category as a string to setlocale() has been deprecated since PHP 4.2; Pass one of the LC_* constants instead.');

        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertError($file, $line, 'Passing the $category as a string to setlocale() has been deprecated since PHP 4.2 and is removed since PHP 7.0; Pass one of the LC_* constants instead.');
    }

    /**
     * dataSetlocaleString
     *
     * @see testSetlocaleString()
     *
     * @return array
     */
    public function dataSetlocaleString()
    {
        return array(
            array(9),
            array(10),
        );
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
        $file = $this->sniffFile(__FILE__, '7.0');
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
        $data = array();

        // No errors expected on the first 7 lines.
        for ($line = 1; $line <= 7; $line++) {
            $data[] = array($line);
        }

        $data[] = array(13);
        $data[] = array(14);

        return $data;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '4.1');
        $this->assertNoViolation($file);
    }
}
