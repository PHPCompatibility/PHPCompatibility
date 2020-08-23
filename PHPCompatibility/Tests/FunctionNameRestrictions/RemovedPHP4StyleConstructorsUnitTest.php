<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionNameRestrictions;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the RemovedPHP4StyleConstructors sniff.
 *
 * @group removedPHP4StyleConstructors
 * @group functionNameRestrictions
 *
 * @covers \PHPCompatibility\Sniffs\FunctionNameRestrictions\RemovedPHP4StyleConstructorsSniff
 *
 * @since 7.0.0
 */
class RemovedPHP4StyleConstructorsUnitTest extends BaseSniffTest
{

    /**
     * Test PHP4 style constructors.
     *
     * @dataProvider dataIsDeprecated
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testIsDeprecated($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertWarning($file, $line, 'Declaration of a PHP4 style class constructor is deprecated since PHP 7.0');

        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, $line, 'Declaration of a PHP4 style class constructor is deprecated since PHP 7.0 and removed since PHP 8.0');
    }

    /**
     * dataIsDeprecated
     *
     * @see testIsDeprecated()
     *
     * @return array
     */
    public function dataIsDeprecated()
    {
        return [
            [3],
            [18],
            [33],
            [66],
            [86],
        ];
    }


    /**
     * Test valid methods with the same name as the class.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataNoFalsePositives
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return [
            [9],
            [12],
            [26],
            [37],
            [41],
            [42],
            [47],
            [51],
            [53],
            [65],
            [87],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertNoViolation($file);
    }
}
