<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\UseDeclarations;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewUseConstFunction sniff.
 *
 * @group newUseConstFunction
 * @group useDeclarations
 *
 * @covers \PHPCompatibility\Sniffs\UseDeclarations\NewUseConstFunctionSniff
 *
 * @since 10.0.0
 */
final class NewUseConstFunctionParseError1UnitTest extends BaseSniffTestCase
{

    /**
     * Verify the sniff will silently ignore live coding.
     *
     * @return void
     */
    public function testSniffStaysSilentOnLiveCoding()
    {
        $file = $this->sniffFile(__FILE__, '5.5');
        $this->assertNoViolation($file);
    }

    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertNoViolation($file);
    }
}
