<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\TypeCasts;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewTypeCasts sniff.
 *
 * @group newTypeCasts
 * @group typeCasts
 *
 * @covers \PHPCompatibility\Sniffs\TypeCasts\NewTypeCastsSniff
 *
 * @since 8.0.1
 */
final class NewTypeCastsUnitTest extends BaseSniffTestCase
{

    /**
     * testNewTypeCasts
     *
     * @dataProvider dataNewTypeCasts
     *
     * @param string $castDescription   The type of type cast.
     * @param string $lastVersionBefore The PHP version just *before* the type cast was introduced.
     * @param array  $lines             The line numbers in the test file which apply to this type cast.
     * @param string $okVersion         A PHP version in which the type cast was valid.
     * @param string $testVersion       Optional. A PHP version in which to test for the error if different
     *                                  from the $lastVersionBefore.
     *
     * @return void
     */
    public function testNewTypeCasts($castDescription, $lastVersionBefore, $lines, $okVersion, $testVersion = null)
    {
        $errorVersion = (isset($testVersion)) ? $testVersion : $lastVersionBefore;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "{$castDescription} is not present in PHP version {$lastVersionBefore} or earlier";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }

        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testNewTypeCasts()
     *
     * @return array
     */
    public static function dataNewTypeCasts()
    {
        return [
            ['The unset cast', '4.4', [8, 15, 17], '5.0'],
            ['The binary cast', '5.2.0', [9, 10, 11, 12, 16, 18], '5.3', '5.2'], // Test (global) namespaced function.
            ['The void cast', '8.4', [24, 25], '8.5'],
        ];
    }


    /**
     * Test functions that shouldn't be flagged by this sniff.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '4.4'); // Low version below the first addition.
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public static function dataNoFalsePositives()
    {
        return [
            [4],
            [5],
            [21],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '99.0'); // High version beyond newest addition.
        $this->assertNoViolation($file);
    }

    /**
     * Ignore legacy feature diagnostics inside attributes, including mixed targets.
     *
     * @return void
     */
    public function testAttributeLegacyFeatures()
    {
        $fileName = __DIR__ . '/NewTypeCastsUnitTest.1.inc';

        foreach (['5.2', '5.2-8.5'] as $version) {
            $file = $this->sniffFile($fileName, $version);
            foreach ([4] as $line) {
                $this->assertNoViolation($file, $line);
            }
        }
    }

    /**
     * Retain the corresponding diagnostics in ordinary executable code.
     *
     * @return void
     */
    public function testExecutableLegacyFeaturesRemainReported()
    {
        $fileName = __DIR__ . '/NewTypeCastsUnitTest.1.inc';

        foreach (['5.2', '5.2-8.5'] as $version) {
            $file = $this->sniffFile($fileName, $version);
            foreach ([8] as $line) {
                $this->assertError($file, $line, 'The binary cast is not present');
            }
        }
    }

    /**
     * Accept the clean examples on PHP 8.5, where attribute closures are available.
     *
     * @return void
     */
    public function testAttributeExamplesOnModernVersion()
    {
        $fileName = __DIR__ . '/NewTypeCastsUnitTest.1.inc';

        $file = $this->sniffFile($fileName, '8.5');
        foreach ([4, 8] as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Preserve diagnostics for newer features or invalid usage inside attributes.
     *
     * @return void
     */
    public function testAttributeDiagnosticsArePreserved1()
    {
        $fileName = __DIR__ . '/NewTypeCastsUnitTest.1.inc';

        foreach (['8.4', '5.2-8.5'] as $version) {
            $file = $this->sniffFile($fileName, $version);
            $this->assertError($file, 11, 'The void cast is not present');
        }
    }
}
