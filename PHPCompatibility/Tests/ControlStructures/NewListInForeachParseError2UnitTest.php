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
 * Test the NewListInForeach sniff.
 *
 * @group newListInForeach
 * @group controlStructures
 *
 * @covers \PHPCompatibility\Sniffs\ControlStructures\NewListInForeachSniff
 *
 * @since 10.0.0
 */
final class NewListInForeachParseError2UnitTest extends BaseSniffTestCase
{

    /**
     * Verify the sniff will silently ignore live coding.
     *
     * @return void
     */
    public function testSniffStaysSilentOnLiveCoding()
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertNoViolation($file);
    }

    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.5');
        $this->assertNoViolation($file);
    }
}
