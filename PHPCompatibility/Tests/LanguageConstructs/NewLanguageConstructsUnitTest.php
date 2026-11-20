<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\LanguageConstructs;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewLanguageConstructs sniff.
 *
 * @group newLanguageConstructs
 * @group languageConstructs
 *
 * @covers \PHPCompatibility\Sniffs\LanguageConstructs\NewLanguageConstructsSniff
 *
 * @since 5.6
 */
final class NewLanguageConstructsUnitTest extends BaseSniffTestCase
{

    /**
     * PHP 5.3: namespace separator.
     *
     * @dataProvider dataNamespaceSeparator
     *
     * @param int $line The line number where an error is expected.
     *
     * @return void
     */
    public function testNamespaceSeparator($line)
    {
        $file = $this->sniffFile(__FILE__, '5.2');
        $this->assertError($file, $line, 'The \ operator (for namespaces) is not present in PHP version 5.2 or earlier');

        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public static function dataNamespaceSeparator()
    {
        return [
            [3],
            [7],
            [8],
            [10],
        ];
    }


    /**
     * PHP 5.6: variadic functions using ...
     *
     * @return void
     */
    public function testEllipsis()
    {
        $file = $this->sniffFile(__FILE__, '5.5');
        $this->assertError($file, 5, 'The ... spread operator is not present in PHP version 5.5 or earlier');

        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertNoViolation($file, 5);
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
     * Ignore attribute names and arguments while preserving executable syntax errors.
     *
     * @return void
     */
    public function testAttributeLanguageConstructs()
    {
        foreach (['5.2', '5.2-8.0'] as $version) {
            $file = $this->sniffFile(__FILE__, $version);
            foreach ([15, 18, 21, 22, 23, 27, 32] as $line) {
                $this->assertNoViolation($file, $line);
            }
            $this->assertError($file, 29, 'The \ operator (for namespaces) is not present in PHP version 5.2 or earlier');
            $this->assertError($file, 34, 'The ... spread operator is not present in PHP version 5.5 or earlier');
        }
    }
}
