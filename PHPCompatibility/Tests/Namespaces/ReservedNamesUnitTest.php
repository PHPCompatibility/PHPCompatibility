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
        $error = "The top-level namespace name \"{$name}\" is reserved for, and in use by, PHP since PHP version {$version}.";
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
            [22, 'Random', '8.2', '8.1'],
            [23, 'Random', '8.2', '8.1'],
            [27, 'rANdOm', '8.2', '8.1'],
            [35, 'FTP', '8.1', '8.0'],
            [36, 'FTP', '8.1', '8.0'],
            [37, 'IMAP', '8.1', '8.0'],
            [38, 'LDAP', '8.1', '8.0'],
            [39, 'PgSql', '8.1', '8.0'],
            [40, 'PSpell', '8.1', '8.0'],
            [76, 'DBA', '8.4', '8.3'],
            [77, 'Odbc', '8.4', '8.3'],
            [78, 'Soap', '8.4', '8.3'],
            [79, 'Pdo', '8.4', '8.3'],
            [80, 'BcMath', '8.4', '8.3'],
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
            [26],
        ];
    }


    /**
     * Verify correctly detecting reserved namespace name for a PECL extension.
     *
     * @dataProvider dataReservedNamePECL
     *
     * @param int    $line      The line number in the test file.
     * @param string $name      The reserved name which should be detected.
     * @param string $version   The PHP version in which the name became reserved.
     * @param string $okVersion A PHP version in which the name was not reserved.
     *
     * @return void
     */
    public function testReservedNamePECL($line, $name, $version = '5.3', $okVersion = '5.2')
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        $this->assertNoViolation($file, $line);

        $file    = $this->sniffFile(__FILE__, $version);
        $warning = "The top-level namespace name \"{$name}\" is reserved for, and in use by, a PECL extension";
        $this->assertWarning($file, $line, $warning);
    }

    /**
     * Data provider.
     *
     * @see testReservedNamePECL()
     *
     * @return array
     */
    public static function dataReservedNamePECL()
    {
        return [
            [43, 'CommonMark'],
            [44, 'Componere'],
            [45, 'Gender'],
            [46, 'HRTime'],
            [47, 'MongoDB'],
            [48, 'mysql_xdevapi'],
            [49, 'parallel'],
            [50, 'Swoole'],
            [51, 'UI'],
            [52, 'wkhtmltox'],
            [53, 'XMLDiff'],
            [54, 'Aerospike'],
            [55, 'Cassandra'],
            [56, 'Couchbase'],
            [57, 'Crypto'],
            [58, 'Decimal'],
            [59, 'Grpc'],
            [60, 'HTTP'],
            [61, 'Mosquitto'],
            [62, 'pcov'],
            [63, 'pq'],
            [64, 'RdKafka'],
            [65, 'Zstd'],
            [68, 'Ds', '7.0', '5.6'],
            [69, 'Vtiful', '7.0', '5.6'],
            [70, 'Vtiful', '7.0', '5.6'],
            [73, 'Parle', '7.4', '7.3'],
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
            [30],
            [31],
            [32],
            [81],
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
