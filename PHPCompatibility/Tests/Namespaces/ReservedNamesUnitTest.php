<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Namespaces;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the ReservedNames sniff.
 *
 * @group reservedNames
 * @group namespaces
 *
 * @covers \PHPCompatibility\Sniffs\Namespaces\ReservedNamesSniff
 *
 * @since 10.0.0
 */
class ReservedNamesUnitTest extends BaseSniffTestCase
{

    /**
     * Verify correctly detecting reserved namespace names.
     *
     * @dataProvider dataReservedNames
     *
     * @param int    $line      The line number in the test file.
     * @param string $name      The reserved name which should be detected.
     * @param string $version   The PHP version in which the name became reserved.
     * @param string $okVersion A PHP version in which the name was not reserved.
     *
     * @return void
     */
    public function testReservedNames($line, $name, $version, $okVersion)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        $this->assertNoViolation($file, $line);

        $file  = $this->sniffFile(__FILE__, $version);
        $error = "The top-level namespace name \"{$name}\" is reserved by and in use by PHP since PHP version {$version}.";
        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testReservedNames()
     *
     * @return array
     */
    public static function dataReservedNames()
    {
        return [
            [18, 'FFI', '7.4', '7.3'],
            [19, 'FFI', '7.4', '7.3'],
        ];
    }

    /**
     * Verify correctly detecting reserved namespace name PHP (which is special cased).
     *
     * @dataProvider dataReservedNamePHP
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testReservedNamePHP($line)
    {
        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertWarning($file, $line, ' is discouraged; PHP has reserved the namespace name "PHP" and compound names starting with "PHP" for internal language use.');
    }

    /**
     * Data provider.
     *
     * @see testReservedNamePHP()
     *
     * @return array
     */
    public static function dataReservedNamePHP()
    {
        return [
            [11],
            [12],
            [13],
        ];
    }


    /**
     * Verify the sniff does not throw false positives on valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '5.3');
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
            [4],
            [5],
            [6],
            [22],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.2');
        $this->assertNoViolation($file);
    }
}
