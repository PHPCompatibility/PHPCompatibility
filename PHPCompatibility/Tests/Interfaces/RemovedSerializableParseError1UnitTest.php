<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2022 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Interfaces;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedSerializable sniff.
 *
 * @group removedSerializable
 * @group interfaces
 *
 * @covers \PHPCompatibility\Sniffs\Interfaces\RemovedSerializableSniff
 *
 * @since 10.0.0
 */
final class RemovedSerializableParseError1UnitTest extends BaseSniffTestCase
{

    /**
     * Verify the sniff will silently ignore live coding.
     *
     * @return void
     */
    public function testSniffStaysSilentOnLiveCoding()
    {
        $file = $this->sniffFile(__FILE__, '7.3-8.0');
        $this->assertNoViolation($file);
    }
}
