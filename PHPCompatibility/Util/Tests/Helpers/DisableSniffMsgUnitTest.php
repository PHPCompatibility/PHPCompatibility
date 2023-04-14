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

use PHPCompatibility\Helpers\DisableSniffMsg;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * Tests for the DisableSniffMsgTrait sniff helper.
 *
 * @group helpers
 *
 * @covers \PHPCompatibility\Helpers\DisableSniffMsg
 *
 * @since 10.0.0
 */
final class DisableSniffMsgUnitTest extends TestCase
{

    /**
     * Test adding "disable sniff notice" to a message.
     *
     * @return void
     */
    public function testCreate()
    {
        $expectedPattern = '`' . \PHP_EOL . \PHP_EOL
            . 'To disable this notice, add --exclude=Stnd\.Cat\.Sniff to your command or'
            . ' add <exclude name="Stnd\.Cat\.Sniff\.Code"/> to your custom ruleset\.'
             . \PHP_EOL . \PHP_EOL
            . 'Thank you for using PHPCompatibility!`';

        $result = DisableSniffMsg::create('Stnd.Cat.Sniff', 'Code');

        $this->assertMatchesRegularExpression($expectedPattern, $result);
    }
}
