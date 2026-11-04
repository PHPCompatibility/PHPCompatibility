<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ControlStructures;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedDeclareEncodingBlockMode sniff.
 *
 * @group removedDeclareEncodingBlockMode
 * @group controlStructures
 *
 * @covers \PHPCompatibility\Sniffs\ControlStructures\RemovedDeclareEncodingBlockModeSniff
 *
 * @since 10.0.0
 */
final class RemovedDeclareEncodingBlockModeParseError1UnitTest extends BaseSniffTestCase
{

    /**
     * Verify that the sniff stays silent for incomplete declare statements (live coding/parse error).
     *
     * @return void
     */
    public function testIncompleteDirective()
    {
        $file = $this->sniffFile(__FILE__, '8.7');
        $this->assertNoViolation($file);
    }
}
