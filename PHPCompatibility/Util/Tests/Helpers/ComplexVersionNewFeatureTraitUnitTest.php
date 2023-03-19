<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2021 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Util\Tests\Helpers;

use PHPUnit\Framework\TestCase;
use PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait;

/**
 * Tests for the ComplexVersionNewFeatureTrait sniff helper.
 *
 * @group helpers
 *
 * @since 10.0.0
 */
final class ComplexVersionNewFeatureTraitUnitTest extends TestCase
{
    use ComplexVersionNewFeatureTrait;

    /**
     * Verify PHP native handling of invalid parameter type being passed.
     *
     * @covers \PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait::getVersionInfo
     *
     * @return void
     */
    public function testGetVersionInfoInvalidParamType()
    {
        if (\method_exists($this, 'expectException')) {
            // PHPUnit 5+.
            if (\PHP_VERSION_ID >= 70000) {
                $this->expectException('TypeError');
            } else {
                $this->expectException('PHPUnit_Framework_Error');
            }
        } else {
            // PHPUnit 4.
            $this->setExpectedException('PHPUnit_Framework_Error');
        }

        $this->getVersionInfo(null);
    }

    /**
     * Test retrieving the "last version before" information from an array with arbitrary contents.
     *
     * @dataProvider dataGetVersionInfo
     *
     * @covers \PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait::getVersionInfo
     *
     * @param array    $itemArray A (non-)typical input array as expected for complex version array sniffs.
     * @param string[] $expected  The expected function output.
     *
     * @return void
     */
    public function testGetVersionInfo($itemArray, $expected)
    {
        $this->assertSame($expected, $this->getVersionInfo($itemArray));
    }

    /**
     * Data provider.
     *
     * @see testGetVersionInfo()
     *
     * @return array
     */
    public static function dataGetVersionInfo()
    {
        return [
            'empty-array' => [
                'itemArray' => [],
                'expected'  => ['not_in_version' => ''],
            ],
            'array-without-keys' => [
                'itemArray' => [
                    false,
                    true,
                ],
                'expected'  => ['not_in_version' => ''],
            ],
            'array-without-version-keys' => [
                'itemArray' => [
                    'name'        => 'foo',
                    'description' => 'bar',
                    'alternative' => 'something else',
                ],
                'expected'  => ['not_in_version' => ''],
            ],
            'version-keys-array-no-false-value' => [
                'itemArray' => [
                    '5.2' => true,
                    '5.3' => 'bar',
                    '7.4' => 123,
                ],
                'expected'  => ['not_in_version' => ''],
            ],
            'version-keys-array-multiple-false-values' => [
                'itemArray' => [
                    '5.2' => true,
                    '5.3' => false,
                    '7.4' => false,
                ],
                'expected'  => ['not_in_version' => '5.3'],
            ],
            'version-keys-array-normal' => [
                'itemArray' => [
                    '5.2' => false,
                    '5.3' => true,
                ],
                'expected'  => ['not_in_version' => '5.2'],
            ],
            'version-keys-array-patch-versions' => [
                'itemArray' => [
                    '5.2.11' => false,
                    '5.2.12' => true,
                    '5.3.4'  => false,
                    '5.3.5'  => true,
                ],
                'expected'  => ['not_in_version' => '5.2.11'],
            ],
            'version-keys-array-normal-mixed-with-non-version-keys' => [
                'itemArray' => [
                    'name'        => 'foo',
                    '7.2'         => false,
                    '7.3'         => true,
                    'alternative' => 'something else',
                    'extension'   => 'extension',
                ],
                'expected'  => ['not_in_version' => '7.2'],
            ],
        ];
    }

    /**
     * Verify PHP native handling of invalid parameter type being passed.
     *
     * @covers \PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait::getMessageInfo
     *
     * @return void
     */
    public function testGetMessageInvalidParamType()
    {
        if (\method_exists($this, 'expectException')) {
            // PHPUnit 5+.
            if (\PHP_VERSION_ID >= 70000) {
                $this->expectException('TypeError');
            } else {
                $this->expectException('PHPUnit_Framework_Error');
            }
        } else {
            // PHPUnit 4.
            $this->setExpectedException('PHPUnit_Framework_Error');
        }

        $this->getMessageInfo(null, null, null);
    }

    /**
     * Test overruling of the error message template and using a non-typical error code base.
     *
     * @covers \PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait::getMessageInfo
     *
     * @return void
     */
    public function testGetMessageInfoOverruledTemplateAndAwkwardErrorCode()
    {
        $this->msgTemplate = 'Some %s is different %s';

        $expected = [
            'message'   => 'Some %s is different %s',
            'errorcode' => 'n_a__mFound',
            'data'      => [
                'name',
                '7.0',
            ],
        ];

        $result = $this->getMessageInfo('name', 'N&a%-M', ['not_in_version' => '7.0']);

        $this->assertSame($expected, $result);
    }

    /**
     * Test retrieving array with data for an error message.
     *
     * @covers \PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait::getMessageInfo
     *
     * @return void
     */
    public function testGetMessageInfo()
    {
        $expected = [
            'message'   => '%s is not present in PHP version %s or earlier',
            'errorcode' => 'function_paramFound',
            'data'      => [
                'name',
                '5.6',
            ],
        ];

        $result = $this->getMessageInfo('name', 'function_param', ['not_in_version' => '5.6']);

        $this->assertSame($expected, $result);
    }
}
