<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Classes;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the ForbiddenExtendingFinalPHPClass sniff.
 *
 * @group forbiddenExtendingFinalPHPClass
 * @group classes
 *
 * @covers \PHPCompatibility\Sniffs\Classes\ForbiddenExtendingFinalPHPClassSniff
 *
 * @since 10.0.0
 */
class ForbiddenExtendingFinalPHPClassUnitTest extends BaseSniffTestCase
{

    /**
     * Verify an error is thrown when a PHP native class which became final is being extended.
     *
     * @dataProvider dataForbiddenExtendingFinalPHPClass
     *
     * @param string $className Class name.
     * @param string $finalIn   The PHP version in which the class was made final.
     * @param array  $line      The line number in the test file which apply to this class.
     * @param string $okVersion A PHP version in which the class was ok to be extended.
     *
     * @return void
     */
    public function testForbiddenExtendingFinalPHPClass($className, $finalIn, $line, $okVersion)
    {
        $file = $this->sniffFile(__FILE__, $finalIn);
        $this->assertError($file, $line, "The built-in class {$className} is final since PHP {$finalIn} and cannot be extended");

        $file = $this->sniffFile(__FILE__, $okVersion);
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testForbiddenExtendingFinalPHPClass()
     *
     * @return array
     */
    public static function dataForbiddenExtendingFinalPHPClass()
    {
        return [
            ['__PHP_Incomplete_Class', '8.0', 12, '7.4'],
            ['__PHP_Incomplete_Class', '8.0', 13, '7.4'],
            ['__PHP_Incomplete_Class', '8.0', 24, '7.4'],
        ];
    }


    /**
     * Verify the sniff does not throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
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
        return [
            [5],
            [6],
            [7],
            [8],
            [9],
            [18],
            [19],
            [20],
            [21],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.4'); // Low version before the first change to final.
        $this->assertNoViolation($file);
    }
}
