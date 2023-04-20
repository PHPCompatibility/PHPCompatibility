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
use PHPCompatibility\Helpers\ComplexVersionDeprecatedRemovedFeatureTrait;

/**
 * Tests for the ComplexVersionDeprecatedRemovedFeatureTrait sniff helper.
 *
 * @group helpers
 *
 * @since 10.0.0
 */
final class ComplexVersionDeprecatedRemovedFeatureTraitUnitTest extends TestCase
{
    use ComplexVersionDeprecatedRemovedFeatureTrait;

    /**
     * Verify PHP native handling of invalid parameter type being passed.
     *
     * @covers \PHPCompatibility\Helpers\ComplexVersionDeprecatedRemovedFeatureTrait::getVersionInfo
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
     * Test retrieving the "deprecated", "removed" and/or "alternative" information
     * from an array with arbitrary contents.
     *
     * @dataProvider dataGetVersionInfo
     *
     * @covers \PHPCompatibility\Helpers\ComplexVersionDeprecatedRemovedFeatureTrait::getVersionInfo
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
    public function dataGetVersionInfo()
    {
        return [
            'empty-array' => [
                'itemArray' => [],
                'expected'  => [
                    'deprecated'  => '',
                    'removed'     => '',
                    'alternative' => '',
                ],
            ],
            'array-without-keys' => [
                'itemArray' => [
                    false,
                    true,
                ],
                'expected'  => [
                    'deprecated'  => '',
                    'removed'     => '',
                    'alternative' => '',
                ],
            ],
            'array-without-version-keys' => [
                'itemArray' => [
                    'name'        => 'foo',
                    'description' => 'bar',
                    'alternative' => 'something else',
                ],
                'expected'  => [
                    'deprecated'  => '',
                    'removed'     => '',
                    'alternative' => 'something else',
                ],
            ],
            'version-keys-array-non-string-keys' => [
                'itemArray' => [
                    5 => false,
                    6 => true,
                ],
                'expected'  => [
                    'deprecated'  => '',
                    'removed'     => '',
                    'alternative' => '',
                ],
            ],
            'version-keys-array-non-boolean-values' => [
                'itemArray' => [
                    '5.2' => 'bar',
                    '5.3' => [],
                    '7.4' => 123,
                ],
                'expected'  => [
                    'deprecated'  => '',
                    'removed'     => '',
                    'alternative' => '',
                ],
            ],
            'version-keys-array-deprecated-not-removed' => [
                'itemArray' => [
                    '5.2' => 'bar',
                    '5.3' => false,
                    '7.4' => 123,
                ],
                'expected'  => [
                    'deprecated'  => '5.3',
                    'removed'     => '',
                    'alternative' => '',
                ],
            ],
            'version-keys-array-removed-not-deprecated' => [
                'itemArray' => [
                    '5.2' => true,
                    '5.3' => 'bar',
                    '7.4' => 123,
                ],
                'expected'  => [
                    'deprecated'  => '',
                    'removed'     => '5.2',
                    'alternative' => '',
                ],
            ],
            'version-keys-array-deprecated-and-removed' => [
                'itemArray' => [
                    '5.4' => false,
                    '7.0' => true,
                ],
                'expected'  => [
                    'deprecated'  => '5.4',
                    'removed'     => '7.0',
                    'alternative' => '',
                ],
            ],
            'version-keys-array-multiple-false-values' => [
                'itemArray' => [
                    '5.2' => true,
                    '5.3' => false,
                    '7.4' => false,
                ],
                'expected'  => [
                    'deprecated'  => '5.3',
                    'removed'     => '5.2',
                    'alternative' => '',
                ],
            ],
            'version-keys-array-multiple-true-values' => [
                'itemArray' => [
                    '7.1' => false,
                    '7.3' => true,
                    '8.0' => true,
                ],
                'expected'  => [
                    'deprecated'  => '7.1',
                    'removed'     => '7.3',
                    'alternative' => '',
                ],
            ],
            'version-keys-array-patch-versions' => [
                'itemArray' => [
                    '5.2.11' => false,
                    '5.2.12' => true,
                    '5.3.4'  => false,
                    '5.3.5'  => true,
                ],
                'expected'  => [
                    'deprecated'  => '5.2.11',
                    'removed'     => '5.2.12',
                    'alternative' => '',
                ],
            ],
            'version-keys-array-normal-mixed-with-non-version-keys' => [
                'itemArray' => [
                    'name'        => 'foo',
                    '7.2'         => false,
                    '7.3'         => true,
                    'alternative' => 'something else',
                    'extension'   => 'extension',
                ],
                'expected'  => [
                    'deprecated'  => '7.2',
                    'removed'     => '7.3',
                    'alternative' => 'something else',
                ],
            ],
            'alternative-non-string-value' => [
                'itemArray' => [
                    'alternative' => 1234,
                ],
                'expected'  => [
                    'deprecated'  => '',
                    'removed'     => '',
                    'alternative' => '1234',
                ],
            ],
        ];
    }

    /**
     * Verify PHP native handling of invalid parameter type being passed.
     *
     * @covers \PHPCompatibility\Helpers\ComplexVersionDeprecatedRemovedFeatureTrait::getMessageInfo
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
     * Test overruling of the error message and alternative option templates and using a non-typical error code base.
     *
     * @covers \PHPCompatibility\Helpers\ComplexVersionDeprecatedRemovedFeatureTrait::getMessageInfo
     *
     * @return void
     */
    public function testGetMessageInfoOverruledTemplatesAndAwkwardErrorCode()
    {
        $originalMsgTemplate = $this->msgTemplate;
        $originalAltTemplate = $this->alternativeOptionTemplate;

        $this->msgTemplate               = 'Some %s is ';
        $this->alternativeOptionTemplate = '; Replace with %s';

        $expected = [
            'message'   => 'Some %s is deprecated since PHP %s; Replace with %s',
            'errorcode' => 'n_a__mDeprecated',
            'data'      => [
                'name',
                '7.0',
                'bar',
            ],
        ];

        $result = $this->getMessageInfo(
            'name',
            'N&a%-M',
            [
                'deprecated'  => '7.0',
                'removed'     => '',
                'alternative' => 'bar',
            ]
        );

        $this->assertSame($expected, $result);

        // Reset message template.
        $this->msgTemplate               = $originalMsgTemplate;
        $this->alternativeOptionTemplate = $originalAltTemplate;
    }

    /**
     * Test retrieving array with data for an error message.
     *
     * @dataProvider dataGetMessageInfo
     *
     * @covers \PHPCompatibility\Helpers\ComplexVersionDeprecatedRemovedFeatureTrait::getMessageInfo
     *
     * @param string   $itemName     Item name, normally name of the function or class detected.
     * @param string   $itemBaseCode The basis for the error code.
     * @param string[] $versionInfo  Array of version info as received from the getVersionInfo() method.
     * @param array    $expected     The expected function output.
     *
     * @return void
     */
    public function testGetMessageInfo($itemName, $itemBaseCode, $versionInfo, $expected)
    {
        $result = $this->getMessageInfo($itemName, $itemBaseCode, $versionInfo);

        $this->assertSame($expected, $result);
    }

    /**
     * Data provider.
     *
     * @see testGetMessageInfo()
     *
     * @return array
     */
    public function dataGetMessageInfo()
    {
        return [
            // This should never happen as the sniff should have bowed out before.
            'no-usable-data' => [
                'itemName'     => 'name',
                'itemBaseCode' => 'name',
                'versionInfo'  => [
                    'deprecated'  => '',
                    'removed'     => '',
                    'alternative' => '',
                ],
                'expected'     => [
                    'message'   => '%',
                    'errorcode' => 'name',
                    'data'      => [
                        'name',
                    ],
                ],
            ],
            'deprecated-not-removed' => [
                'itemName'     => 'name',
                'itemBaseCode' => 'foo',
                'versionInfo'  => [
                    'deprecated'  => '5.3',
                    'removed'     => '',
                    'alternative' => '',
                ],
                'expected'     => [
                    'message'   => '%s is deprecated since PHP %s',
                    'errorcode' => 'fooDeprecated',
                    'data'      => [
                        'name',
                        '5.3',
                    ],
                ],
            ],
            'removed-not-deprecated' => [
                'itemName'     => 'name',
                'itemBaseCode' => 'bar',
                'versionInfo'  => [
                    'deprecated'  => '',
                    'removed'     => '7.0',
                    'alternative' => '',
                ],
                'expected'     => [
                    'message'   => '%s is removed since PHP %s',
                    'errorcode' => 'barRemoved',
                    'data'      => [
                        'name',
                        '7.0',
                    ],
                ],
            ],
            'deprecated-and-removed' => [
                'itemName'     => 'name',
                'itemBaseCode' => 'foobar',
                'versionInfo'  => [
                    'deprecated'  => '7.2',
                    'removed'     => '8.0',
                    'alternative' => '',
                ],
                'expected'     => [
                    'message'   => '%s is deprecated since PHP %s and removed since PHP %s',
                    'errorcode' => 'foobarDeprecatedRemoved',
                    'data'      => [
                        'name',
                        '7.2',
                        '8.0',
                    ],
                ],
            ],
            'deprecated-not-removed-with-alternative' => [
                'itemName'     => 'name',
                'itemBaseCode' => 'foo',
                'versionInfo'  => [
                    'deprecated'  => '8.0',
                    'removed'     => '',
                    'alternative' => 'alternative',
                ],
                'expected'     => [
                    'message'   => '%s is deprecated since PHP %s; Use %s instead',
                    'errorcode' => 'fooDeprecated',
                    'data'      => [
                        'name',
                        '8.0',
                        'alternative',
                    ],
                ],
            ],
            'removed-not-deprecated-with-alternative' => [
                'itemName'     => 'name',
                'itemBaseCode' => 'bar',
                'versionInfo'  => [
                    'deprecated'  => '',
                    'removed'     => '7.2',
                    'alternative' => 'something else',
                ],
                'expected'     => [
                    'message'   => '%s is removed since PHP %s; Use %s instead',
                    'errorcode' => 'barRemoved',
                    'data'      => [
                        'name',
                        '7.2',
                        'something else',
                    ],
                ],
            ],
            'deprecated-and-removed-with-alternative' => [
                'itemName'     => 'name',
                'itemBaseCode' => 'foobar',
                'versionInfo'  => [
                    'deprecated'  => '5.5',
                    'removed'     => '7.1',
                    'alternative' => 'other feature',
                ],
                'expected'     => [
                    'message'   => '%s is deprecated since PHP %s and removed since PHP %s; Use %s instead',
                    'errorcode' => 'foobarDeprecatedRemoved',
                    'data'      => [
                        'name',
                        '5.5',
                        '7.1',
                        'other feature',
                    ],
                ],
            ],
        ];
    }
}
