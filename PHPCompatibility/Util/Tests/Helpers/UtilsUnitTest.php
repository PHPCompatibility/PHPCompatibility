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

use PHPCompatibility\Helpers\Utils;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the Utils sniff helper.
 *
 * @group helpers
 *
 * @covers \PHPCompatibility\Helpers\Utils
 *
 * @since 10.0.0
 */
final class UtilsUnitTest extends TestCase
{

    /**
     * Perfunctory test of the trim wrapper.
     *
     * @dataProvider dataTrim
     *
     * @param string $input    The input to pass to the function.
     * @param string $expected The expected function output.
     *
     * @return void
     */
    public function testTrim($input, $expected)
    {
        $this->assertSame($expected, Utils::trim($input));
    }

    /**
     * Safeguard that the trim wrapper allows for overwritting the default characters.
     *
     * @return void
     */
    public function testTrimAllowsForCustomCharacters()
    {
        $this->assertSame(' b ', Utils::trim('a b a', 'a'));
    }

    /**
     * Perfunctory test of the ltrim wrapper.
     *
     * @dataProvider dataTrim
     *
     * @param string $input    The input to pass to the function.
     * @param string $expected The expected function output.
     *
     * @return void
     */
    public function testLtrim($input, $expected)
    {
        $this->assertSame($expected, Utils::ltrim($input));
    }

    /**
     * Safeguard that the ltrim wrapper allows for overwritting the default characters.
     *
     * @return void
     */
    public function testLtrimAllowsForCustomCharacters()
    {
        $this->assertSame(' b a', Utils::ltrim('a b a', 'a'));
    }

    /**
     * Perfunctory test of the rtrim wrapper.
     *
     * @dataProvider dataTrim
     *
     * @param string $input    The input to pass to the function.
     * @param string $expected The expected function output.
     *
     * @return void
     */
    public function testRtrim($input, $expected)
    {
        $this->assertSame($expected, Utils::rtrim($input));
    }

    /**
     * Safeguard that the rtrim wrapper allows for overwritting the default characters.
     *
     * @return void
     */
    public function testRtrimAllowsForCustomCharacters()
    {
        $this->assertSame('a b ', Utils::rtrim('a b a', 'a'));
    }

    /**
     * Data provider.
     *
     * @return array<string, array<string, string>>
     */
    public static function dataTrim()
    {
        return [
            'everything should be stripped' => [
                'input'    => " \f\n\r\t\v\x00",
                'expected' => '',
            ],
            'nothing should be stripped' => [
                'input'    => "abc \f\n\r\t\v\x00 def",
                'expected' => "abc \f\n\r\t\v\x00 def",
            ],
        ];
    }
}
