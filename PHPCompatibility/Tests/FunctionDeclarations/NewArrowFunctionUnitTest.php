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
 * New Arrow Function Sniff tests
 *
 * @group newArrowFunction
 * @group functionDeclarations
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\NewArrowFunctionSniff
 *
 * @since 10.0.0
 */
class NewArrowFunctionUnitTest extends BaseSniffTestCase
{

    /**
     * Test recognizing arrow functions correctly.
     *
     * @dataProvider dataNewArrowFunction
     *
     * @param array $line The line number on which the error should occur.
     *
     * @return void
     */
    public function testNewArrowFunction($line)
    {
        $file = $this->sniffFile(__FILE__, '7.3');
        $this->assertError($file, $line, 'Arrow functions are not available in PHP 7.3 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testNewArrowFunction()
     *
     * @return array
     */
    public static function dataNewArrowFunction()
    {
        return [
            [24],
            [26],
            [28],
            [31],
            [32],
            [35],
        ];
    }


    /**
     * Verify there are no false positives for a PHP version on which this sniff throws errors.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.3');
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

        // No issues expected on the first 21 lines.
        for ($i = 1; $i <= 21; $i++) {
            $data[] = [$i];
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
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertNoViolation($file);
    }
}
