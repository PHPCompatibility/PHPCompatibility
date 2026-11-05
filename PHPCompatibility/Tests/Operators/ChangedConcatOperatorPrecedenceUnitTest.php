<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Operators;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the ChangedConcatOperatorPrecedence sniff.
 *
 * @group changedConcatOperatorPrecedence
 * @group operators
 *
 * @covers \PHPCompatibility\Sniffs\Operators\ChangedConcatOperatorPrecedenceSniff
 *
 * @since 9.2.0
 */
final class ChangedConcatOperatorPrecedenceUnitTest extends BaseSniffTestCase
{

    /**
     * testChangedConcatOperatorPrecedence
     *
     * @dataProvider dataChangedConcatOperatorPrecedence
     *
     * @param int $line The line number on which the warning/error should occur.
     *
     * @return void
     */
    public function testChangedConcatOperatorPrecedence($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertWarning($file, $line, 'Using an unparenthesized expression containing a "." before a "+" or "-" has been deprecated in PHP 7.4');

        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, $line, 'Using an unparenthesized expression containing a "." before a "+" or "-" has been deprecated in PHP 7.4 and removed in PHP 8.0');
    }

    /**
     * Data provider.
     *
     * @see testChangedConcatOperatorPrecedence()
     *
     * @return array
     */
    public static function dataChangedConcatOperatorPrecedence()
    {
        return [
            [59],
            [60],
            [61],
            [68],
            [74],
            [85],
            [92],
            [95],
        ];
    }


    /**
     * Verify the sniff doesn't throw false positives.
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '7.4');

        for ($line = 1; $line < 57; $line++) {
            $this->assertNoViolation($file, $line);
        }
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.3');
        $this->assertNoViolation($file);
    }

    /**
     * Ignore legacy feature diagnostics inside attributes, including mixed targets.
     *
     * @return void
     */
    public function testAttributeLegacyFeatures()
    {
        $fileName = __DIR__ . '/ChangedConcatOperatorPrecedenceUnitTest.1.inc';

        foreach (['7.4', '7.4-8.5'] as $version) {
            $file = $this->sniffFile($fileName, $version);
            foreach ([3, 7] as $line) {
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
        $fileName = __DIR__ . '/ChangedConcatOperatorPrecedenceUnitTest.1.inc';

        foreach (['7.4', '7.4-8.5'] as $version) {
            $file = $this->sniffFile($fileName, $version);
            foreach ([11] as $line) {
                if ($version === '7.4') {
                    $this->assertWarning($file, $line, 'Using an unparenthesized expression containing a "." before a "+" or "-"');
                } else {
                    $this->assertError($file, $line, 'Using an unparenthesized expression containing a "." before a "+" or "-"');
                }
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
        $fileName = __DIR__ . '/ChangedConcatOperatorPrecedenceUnitTest.1.inc';

        $file = $this->sniffFile($fileName, '8.5');
        foreach ([3, 7] as $line) {
            $this->assertNoViolation($file, $line);
        }
    }
}
