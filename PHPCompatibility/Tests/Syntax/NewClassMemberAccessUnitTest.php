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
 * Test the NewClassMemberAccess sniff.
 *
 * @group newClassMemberAccess
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\NewClassMemberAccessSniff
 *
 * @since 8.2.0
 */
class NewClassMemberAccessUnitTest extends BaseSniffTest
{

    /**
     * testNewClassMemberAccess
     *
     * @dataProvider dataNewClassMemberAccess
     *
     * @param int  $line            The line number.
     * @param bool $skipNoViolation Optional. Whether or not to test for no violation.
     *                              Defaults to false.
     *
     * @return void
     */
    public function testNewClassMemberAccess($line, $skipNoViolation = false)
    {
        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertError($file, $line, 'Class member access on object instantiation was not supported in PHP 5.3 or earlier');

        if ($skipNoViolation === false) {
            $file = $this->sniffFile(__FILE__, '5.4');
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider dataNewClassMemberAccess.
     *
     * @see testNewClassMemberAccess()
     *
     * @return array
     */
    public static function dataNewClassMemberAccess()
    {
        return [
            [41],
            [42],
            [43],
            [45],
            [47],
            [48],
            [49],
            [51],
            [52],
            [54],
            [58],
            [60],
            [61],
            [62],
            [65],
            [70],
            [76],
            [79],
            [82],
            [87],
            [91],
            [96],
            [117, true],
            [120],
        ];
    }


    /**
     * testNewClassMemberAccessUsingCurlies
     *
     * @dataProvider dataNewClassMemberAccessUsingCurlies
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewClassMemberAccessUsingCurlies($line)
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertError($file, $line, 'Class member access on object instantiation using curly braces was not supported in PHP 5.6 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testNewClassMemberAccessUsingCurlies()
     *
     * @return array
     */
    public static function dataNewClassMemberAccessUsingCurlies()
    {
        return [
            [111],
            [112], // Error x 2.
            [117],
        ];
    }


    /**
     * testCloneClassMemberAccess
     *
     * @dataProvider dataCloneClassMemberAccess
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testCloneClassMemberAccess($line)
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertError($file, $line, 'Class member access on object cloning was not supported in PHP 5.6 or earlier');
    }

    /**
     * Data provider dataCloneClassMemberAccess.
     *
     * @see testCloneClassMemberAccess()
     *
     * @return array
     */
    public static function dataCloneClassMemberAccess()
    {
        return [
            [101],
            [103],
            [105],
            [114],
            [118], // Error x 2.
        ];
    }


    /**
     * testNoFalsePositives
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '5.3');

        // No errors expected on the first 37 lines.
        for ($line = 1; $line <= 37; $line++) {
            $this->assertNoViolation($file, $line);
        }
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertNoViolation($file);
    }
}
