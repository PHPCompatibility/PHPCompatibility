<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Util\Tests\Helpers;

use PHPCompatibility\Util\Tests\CoreMethodTestFrame;
use PHPCompatibility\Helpers\DisableSniffMsgTrait;
use Yoast\PHPUnitPolyfills\Polyfills\AssertionRenames;

/**
 * Tests for the DisableSniffMsgTrait sniff helper.
 *
 * @group helpers
 *
 * @covers \PHPCompatibility\Helpers\DisableSniffMsgTrait
 *
 * @since 10.0.0
 */
class DisableSniffMsgTraitUnitTest extends CoreMethodTestFrame
{
    use AssertionRenames;
    use DisableSniffMsgTrait;

    /**
     * Test adding "disable sniff notice" to a message.
     *
     * @return void
     */
    public function testCreateDisableSniffNotice()
    {
        $expectedPattern = '`-{40,}'
            . ' To disable this notice, add --exclude=Stnd\.Cat\.Sniff to your command or'
            . ' add <exclude name="Stnd\.Cat\.Sniff\.Code"/> to your custom ruleset\.'
            . ' -{40,}'
            . ' Thank you for using PHPCompatibility!`';

        $result = $this->createDisableSniffNotice(self::$phpcsFile, 'Stnd.Cat.Sniff', 'Code');

        $this->assertMatchesRegularExpression($expectedPattern, $result);
    }
}
