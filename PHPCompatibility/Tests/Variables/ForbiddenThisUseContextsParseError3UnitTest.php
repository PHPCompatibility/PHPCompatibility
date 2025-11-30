<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2021 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Variables;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the ForbiddenThisUseContexts sniff.
 *
 * @group forbiddenThisUseContexts
 * @group variables
 *
 * @covers \PHPCompatibility\Sniffs\Variables\ForbiddenThisUseContextsSniff
 *
 * @since 10.0.0
 */
final class ForbiddenThisUseContextsParseError3UnitTest extends BaseSniffTestCase
{

    /**
     * Verify the sniff will silently ignore live coding.
     *
     * @return void
     */
    public function testSniffStaysSilentOnLiveCoding()
    {
        $file = $this->sniffFile(__FILE__, '7.1');
        $this->assertNoViolation($file);
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
