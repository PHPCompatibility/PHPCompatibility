<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Syntax;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewDynamicAccessToStatic sniff.
 *
 * @group newDynamicAccessToStatic
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\NewDynamicAccessToStaticSniff
 *
 * @since 8.1.0
 */
class NewDynamicAccessToStaticUnitTest extends BaseSniffTest
{

    /**
     * testDynamicAccessToStatic
     *
     * @dataProvider dataDynamicAccessToStatic
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testDynamicAccessToStatic($line)
    {
        $file = $this->sniffFile(__FILE__, '5.2');
        $this->assertError($file, $line, 'Static class properties and methods, as well as class constants, could not be accessed using a dynamic (variable) classname in PHP 5.2 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testDynamicAccessToStatic()
     *
     * @return array
     */
    public static function dataDynamicAccessToStatic()
    {
        return [
            [20],
            [21],
            [22],
            [25],
            [26],
            [27],
            [32],
            [34],
            [35],
            [41],
            [42],
            [43],
            [61],
            [62],
            [66],
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
        return [
            [14],
            [15],
            [16],
            [50],
            [51],
            [53],
            [54],
            [57],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertNoViolation($file);
    }
}
