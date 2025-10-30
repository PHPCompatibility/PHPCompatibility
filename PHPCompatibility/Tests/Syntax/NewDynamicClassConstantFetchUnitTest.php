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

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewDynamicClassConstantFetch sniff.
 *
 * @group newDynamicClassConstantFetch
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\NewDynamicClassConstantFetchSniff
 *
 * @since 10.0.0
 */
final class NewDynamicClassConstantFetchUnitTest extends BaseSniffTestCase
{

    /**
     * Ensure a warning message when found syntax on earlier versions.
     *
     * @dataProvider dataUnsupportedVersion
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testUnsupportedVersion($line)
    {
        $file = $this->sniffFile(__FILE__, '8.2');
        $this->assertError($file, $line, 'Dynamic class constant fetch is not available in PHP 8.2 or earlier.');
    }

    /**
     * Data provider.
     *
     * @return array
     * @see    testUnsupportedVersion()
     */
    public static function dataUnsupportedVersion()
    {
        return [
            [3],
            [4],
            [5],
        ];
    }

    /**
     * Ensure a warning message in NOT found syntax on supported versions.
     *
     * @dataProvider dataSupportedVersion
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testSupportedVersion($line)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @return array
     * @see    testSupportedVersion()
     */
    public static function dataSupportedVersion()
    {
        return [
            [3],
            [4],
            [5],
        ];
    }
}
