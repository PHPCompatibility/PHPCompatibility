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
 * Test the NewReadonlyProperties sniff.
 *
 * @group newReadonlyProperties
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Classes\NewReadonlyPropertiesSniff
 *
 * @since 10.0.0
 */
final class NewReadonlyPropertiesParseError1UnitTest extends BaseSniffTestCase
{

    /**
     * Verify the sniff will still thrown an error during live coding.
     *
     * @return void
     */
    public function testSniffThrowsErrorDuringLiveCoding()
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, 5, 'Readonly properties are not supported in PHP 8.0 or earlier.');
    }

    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertNoViolation($file);
    }
}
