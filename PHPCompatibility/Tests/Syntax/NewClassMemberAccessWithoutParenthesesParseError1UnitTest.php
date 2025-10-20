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
 * Test the NewClassMemberAccessWithoutParentheses sniff.
 *
 * @group newClassMemberAccessWithoutParentheses
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\NewClassMemberAccessWithoutParenthesesSniff
 *
 * @since 10.0.0
 */
final class NewClassMemberAccessWithoutParenthesesParseError1UnitTest extends BaseSniffTestCase
{

    /**
     * Verify the sniff will silently ignore live coding.
     *
     * @return void
     */
    public function testSniffStaysSilentOnLiveCoding()
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file);
    }

    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertNoViolation($file);
    }
}
