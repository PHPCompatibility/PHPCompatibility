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

use PHP_CodeSniffer\Config;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCSUtils\BackCompat\Helper;
use ReflectionMethod;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * Tests for the ScannedCode sniff helper.
 *
 * @group helpers
 *
 * @since 10.0.0
 */
final class ScannedCodeUnitTest extends TestCase
{

    /**
     * PHPCS Config object.
     *
     * @var \PHP_CodeSniffer\Config
     */
    private static $config;

    /**
     * Reflection object for the ScannedCode::getTestVersion() method.
     *
     * @var \ReflectionMethod
     */
    private static $reflMethod;

    /**
     * Sets up a PHPCS Config object and make one of the methods under test accessible.
     *
     * @beforeClass
     *
     * @return void
     */
    public static function initializeConfig()
    {
        self::$config = new Config();

        self::$reflMethod = new ReflectionMethod('PHPCompatibility\Helpers\ScannedCode', 'getTestVersion');
        self::$reflMethod->setAccessible(true);
    }

    /**
     * Clean up after finished test.
     *
     * @after
     *
     * @return void
     */
    protected function resetTestVersion()
    {
        Helper::setConfigData('testVersion', null, true, self::$config);
        Helper::setConfigData('testversion', null, true, self::$config);
    }


    /**
     * Test retrieving the `testVersion` configuration variable.
     *
     * @dataProvider dataGetTestVersion
     *
     * @covers \PHPCompatibility\Helpers\ScannedCode::getTestVersion
     *
     * @param string $testVersion The testVersion as normally set via the command line or ruleset.
     * @param string $expected    The expected testVersion array.
     *
     * @return void
     */
    public function testGetTestVersion($testVersion, $expected)
    {
        if (isset($testVersion)) {
            Helper::setConfigData('testVersion', $testVersion, true, self::$config);
        }

        $this->assertSame($expected, self::$reflMethod->invoke(null));

        // Verify that the caching of the function results is working correctly.
        $this->assertSame($expected, self::$reflMethod->invoke(null));
    }

    /**
     * Test retrieving the `testversion` configuration variable (lowercase).
     *
     * @dataProvider dataGetTestVersion
     *
     * @covers \PHPCompatibility\Helpers\ScannedCode::getTestVersion
     *
     * @param string $testVersion The testVersion as normally set via the command line or ruleset.
     * @param string $expected    The expected testVersion array.
     *
     * @return void
     */
    public function testGetTestVersionCaseLowercase($testVersion, $expected)
    {
        if (isset($testVersion)) {
            Helper::setConfigData('testversion', $testVersion, true, self::$config);
        }

        $this->assertSame($expected, self::$reflMethod->invoke(null));

        // Verify that the caching of the function results is working correctly.
        $this->assertSame($expected, self::$reflMethod->invoke(null));
    }

    /**
     * Data provider.
     *
     * @see testGetTestVersion()
     *
     * @return array
     */
    public static function dataGetTestVersion()
    {
        return [
            'no_testVersion'                            => [null, [null, null]],
            'single_version_1'                          => ['5.0', ['5.0', '5.0']],
            'single_version_2'                          => ['7.1', ['7.1', '7.1']],
            'version_range_1'                           => ['4.0-99.0', ['4.0', '99.0']],
            'version_range_2'                           => ['5.1-5.5', ['5.1', '5.5']],
            'version_range_3'                           => ['7.0-7.5', ['7.0', '7.5']],
            'version_range_min-max-same'                => ['5.6-5.6', ['5.6', '5.6']],
            'version_range_spaces_around_dash'          => ['4.0 - 99.0', ['4.0', '99.0']],
            'version_range_no_minimum'                  => ['-5.6', ['4.0', '5.6']],
            'version_range_no_maximum'                  => ['7.0-', ['7.0', '99.9']],

            // Whitespace tests.  Shouldn't really come up in standard command-line use,
            // but could occur if command-line argument is quoted or added via
            // ruleset.xml.
            'single_version_leading_whitespace'         => [' 5.0', ['5.0', '5.0']],
            'single_version_trailing_whitespace'        => ['5.0 ', ['5.0', '5.0']],
            'version_range_inner_whitespace'            => ['5.1 - 5.5', ['5.1', '5.5']],
            'version_range_leading_trailing_whitespace' => [' 5.1 - 5.5 ', ['5.1', '5.5']],
        ];
    }


    /**
     * Test that a warning is thrown when an invalid testVersion range is provided.
     *
     * @dataProvider dataGetTestVersionInvalidRange
     *
     * @covers \PHPCompatibility\Helpers\ScannedCode::getTestVersion
     *
     * @param string $testVersion The testVersion as normally set via the command line or ruleset.
     *
     * @return void
     */
    public function testGetTestVersionInvalidRange($testVersion)
    {
        $message = \sprintf('Invalid range in provided PHPCompatibility testVersion: \'%s\'', $testVersion);

        $this->expectException('PHPCompatibility\Exceptions\InvalidTestVersionRange');
        $this->expectExceptionMessage($message);

        $this->testGetTestVersion($testVersion, [null, null]);
    }

    /**
     * Data provider invalid ranges.
     *
     * @see testGetTestVersionInvalidRange()
     *
     * @return array
     */
    public static function dataGetTestVersionInvalidRange()
    {
        return [
            'mininum_more_than_max'              => ['5.6-5.4'],
            'maximum_more_than_absolute_minimum' => ['-3.0'],   // Absolute minimum is 4.0.
            'minimum_more_than_absolute_maximum' => ['105.0-'], // Absolute maximum is 99.9.
        ];
    }


    /**
     * Test that a warning is thrown when an invalid single testVersion is provided.
     *
     * @dataProvider dataGetTestVersionInvalidVersion
     *
     * @covers \PHPCompatibility\Helpers\ScannedCode::getTestVersion
     *
     * @param string $testVersion The testVersion as normally set via the command line or ruleset.
     *
     * @return void
     */
    public function testGetTestVersionInvalidVersion($testVersion)
    {
        $message = \sprintf('Invalid PHPCompatibility testVersion provided: \'%s\'', \trim($testVersion));

        $this->expectException('PHPCompatibility\Exceptions\InvalidTestVersion');
        $this->expectExceptionMessage($message);

        $this->testGetTestVersion($testVersion, [null, null]);
    }

    /**
     * Data provider invalid versions.
     *
     * @see testGetTestVersionInvalidVersion()
     *
     * @return array
     */
    public static function dataGetTestVersionInvalidVersion()
    {
        return [
            'not_major_minor_only_major_1'          => ['5'],
            'not_major_minor_only_major_2'          => ['568'],
            'not_major_minor_major.minor.patch'     => ['5.6.28'],
            'non_numeric'                           => ['seven.one'],

            'only_dash'                             => ['-'],
            'multiple-dashes'                       => ['5.4-5.5-5.6'],

            'range_not_major_minor_left_invalid_1'  => ['5-7.0'],
            'range_not_major_minor_left_invalid_2'  => ['5.1.2-7.0'],
            'range_not_major_minor_left_invalid_3'  => ['5AndJunk-7.0'],

            'range_not_major_minor_right_invalid_1' => ['5.5-7'],
            'range_not_major_minor_right_invalid_2' => ['5.5-7.0.5'],
            'range_not_major_minor_right_invalid_3' => ['5.5-7AndJunk'],
        ];
    }


    /**
     * Test the shouldRunOnOrAbove() method.
     *
     * @dataProvider dataShouldRunOnOrAbove
     *
     * @covers \PHPCompatibility\Helpers\ScannedCode::shouldRunOnOrAbove
     *
     * @param string $phpVersion  The PHP version we want to test.
     * @param string $testVersion The testVersion as normally set via the command line or ruleset.
     * @param bool   $expected    Expected result.
     *
     * @return void
     */
    public function testShouldRunOnOrAbove($phpVersion, $testVersion, $expected)
    {
        if (isset($testVersion)) {
            Helper::setConfigData('testVersion', $testVersion, true, self::$config);
        }

        $this->assertSame($expected, ScannedCode::shouldRunOnOrAbove($phpVersion));
    }

    /**
     * Data provider.
     *
     * @see testShouldRunOnOrAbove()
     *
     * @return array
     */
    public static function dataShouldRunOnOrAbove()
    {
        return [
            'valid_no_testversion_low'           => ['5.0', null, true],
            'valid_testVersion_single_version'   => ['5.0', '5.2', true],
            'valid_testVersion_range_1'          => ['5.0', '5.1-5.4', true],
            'valid_testVersion_range_2'          => ['5.0', '5.3-7.0', true],

            'valid_no_testversion_high'          => ['7.1', null, true],
            'invalid_testVersion_single_version' => ['7.1', '5.2', false],
            'invalid_testVersion_range_1'        => ['7.1', '5.1-5.4', false],
            'invalid_testVersion_range_2'        => ['7.1', '5.3-7.0', false],
        ];
    }


    /**
     * Test the shouldRunOnOrBelow() method.
     *
     * @dataProvider dataShouldRunOnOrBelow
     *
     * @covers \PHPCompatibility\Helpers\ScannedCode::shouldRunOnOrBelow
     *
     * @param string $phpVersion  The PHP version we want to test.
     * @param string $testVersion The testVersion as normally set via the command line or ruleset.
     * @param bool   $expected    Expected result.
     *
     * @return void
     */
    public function testShouldRunOnOrBelow($phpVersion, $testVersion, $expected)
    {
        if (isset($testVersion)) {
            Helper::setConfigData('testVersion', $testVersion, true, self::$config);
        }

        $this->assertSame($expected, ScannedCode::shouldRunOnOrBelow($phpVersion));
    }

    /**
     * Data provider.
     *
     * @see testShouldRunOnOrBelow()
     *
     * @return array
     */
    public static function dataShouldRunOnOrBelow()
    {
        return [
            'invalid_no_testversion_low'         => ['5.0', null, false],
            'invalid_testVersion_single_version' => ['5.0', '5.2', false],
            'invalid_testVersion_range_1'        => ['5.0', '5.1-5.4', false],
            'invalid_testVersion_range_2'        => ['5.0', '5.3-7.0', false],

            'invalid_no_testversion_high'        => ['7.1', null, false],
            'valid_testVersion_single_version'   => ['7.1', '5.2', true],
            'valid_testVersion_range_1'          => ['7.1', '5.1-5.4', true],
            'valid_testVersion_range_2'          => ['7.1', '5.3-7.0', true],
        ];
    }
}
