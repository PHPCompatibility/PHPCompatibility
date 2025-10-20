<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Util\Tests\Helpers\ResolveHelper;

use PHPCompatibility\Helpers\ResolveHelper;
use PHPCSUtils\TestUtils\UtilityMethodTestCase;

/**
 * Tests for the `getFQClassNameFromNewToken()` utility function.
 *
 * @group utilityGetFQClassNameFromNewToken
 * @group utilityFunctions
 *
 * @since 10.0.0
 *
 * @covers \PHPCompatibility\Helpers\ResolveHelper::getFQClassNameFromNewToken
 */
final class GetFQClassNameFromNewTokenParseError1UnitTest extends UtilityMethodTestCase
{

    /**
     * Test handling of live coding/code with parse errors.
     *
     * @return void
     */
    public function testLiveCoding()
    {
        $stackPtr = $this->getTargetToken('/* testLiveCoding */', \T_NEW);
        $result   = ResolveHelper::getFQClassNameFromNewToken(self::$phpcsFile, $stackPtr);
        $this->assertSame('', $result);
    }
}
